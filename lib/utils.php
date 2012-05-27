<?php

require_once(dirname(__FILE__) . "/../vendor/Savant3-3.0.1/Savant3.php");

class ScenarioData {
  public $raw_xml;
  public $xml;
  public $filename;

  function __construct($filename = null) {
    $this->filename = $filename ? $filename : dirname(__FILE__) . "/../data/scenario_1.xml";
  }

  function xml() {
    if (!isset($this->data)) {
      $this->raw_xml = file_get_contents($this->filename);
      $this->xml = new SimpleXMLElement($this->raw_xml);
    }
    return $this->xml;
  }

  function screens() {
    return $this->xml()->screens->screen;
  }

  function find_screen($screens, $screen_id) {
    foreach ($screens as $screen) {
      if ($screen['screenID'] == $screen_id) {
        return $screen;
      }
    }
    return null;
  }

  function screen($screen_id) {
    return $this->find_screen($this->screens(), $screen_id);
  }

  function saveScreen($data) {
    $new_xml = new SimpleXMLElement($this->raw_xml);
    $screen = $this->find_screen($new_xml->screens->screen, $data['screen_id']);
    $screen->screenFirstName = $data['screenFirstName'];
    $screen->screenTime = $data['screenTime'];
    foreach ($data['box'] as $i => $box_data) {
      $screen->boxs->box[$i]->$box_data['type'] = $box_data['content'];
      $screen->boxs->box[$i]->boxVariableOne = $box_data['boxVariableOne'];
      $screen->boxs->box[$i]->boxVariableTwo = $box_data['boxVariableTwo'];
    }

    $this->saveSafelyWithVersions($new_xml);
  }

  function assertWritable($path) {
    if (!is_writable($path)) {
      throw new Exception("Can't write to $path");
    }
    return $path;
  }

  function ageBackups() {
    $this->assertWritable(dirname($this->filename));
    unlink($this->filename . ".5");
    for($i=4; $i>0; $i--) {
      $from = $this->filename . "." . $i;
      $to = $this->filename . "." . ($i + 1);
      if (file_exists($from)) {
        rename($from, $to);
      }
    }
    copy($this->filename, $this->filename . ".1");
  }

  function saveSafelyWithVersions($xml) {
    $this->assertWritable($this->filename);
    $this->assertWritable(dirname($this->filename));
    $tmp_file_name = $this->filename . "." . md5(microtime());
    file_put_contents($tmp_file_name, $xml->asXml());
    $this->ageBackups();
    rename($tmp_file_name, $this->filename);
  }
}

