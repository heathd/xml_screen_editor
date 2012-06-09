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
    if (!isset($this->xml)) {
      $this->loadData();
    }
    return $this->xml;
  }

  function loadData() {
    try {
      $this->raw_xml = file_get_contents($this->filename);
      $this->xml = @new SimpleXMLElement($this->raw_xml);
      return true;
    }
    catch (Exception $e) {
      return false;
    }
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

  function ageBackups($filename) {
    $this->assertWritable(dirname($filename));
    @unlink($filename . ".5");
    for($i=4; $i>0; $i--) {
      $from = $filename . "." . $i;
      $to = $filename . "." . ($i + 1);
      if (file_exists($from)) {
        rename($from, $to);
      }
    }
    copy($filename, $filename . ".1");
  }

  function rollback() {
    $this->assertWritable(dirname($this->filename));
    $this->ageBackups($this->filename . ".bad");
    @rename($this->filename, $this->filename . ".bad");
    for($i=0; $i<5; $i++) {
      $from = $this->filename . "." . ($i + 1);
      $to = $i == 0 ? $this->filename : $this->filename . "." . ($i);
      if (file_exists($from)) {
        rename($from, $to);
      }
    }
  }

  function saveSafelyWithVersions($xml) {
    $this->assertWritable($this->filename);
    $this->assertWritable(dirname($this->filename));
    $tmp_file_name = $this->filename . "." . md5(microtime());
    file_put_contents($tmp_file_name, $xml->asXml());
    $this->ageBackups($this->filename);
    rename($tmp_file_name, $this->filename);
  }
}

class Request {
  function __construct() {
    $this->_raw_get = $_GET;
    $this->_raw_post = $_POST;
    $this->_raw_server = $_SERVER;
  }

  function isPost() {
    return (boolean) $this->_raw_post;
  }

  function post($varname = null) {
    if (!isset($this->_post)) {
      $this->_post = $this->convertPost($this->_raw_post);
    }
    return $varname ? $this->_post[$varname] : $this->_post;
  }

  function get($varname) {
    return $this->_raw_get[$varname];
  }

  function convertPost($post) {
    if (is_array($post) and $post['utf8'] == '✓') {
      return $post;
    } elseif (is_array($post)) {
      $convertedPost = array();
      foreach ($post as $key => $value) {
        $convertedPost[$key] = $this->convertPost($value);
      }
      return $convertedPost;
    } else {
      return $this->convertCp1252ToUtf8($post);
    }
  }

  function convertCp1252ToUtf8($cp1252_with_numeric_entities) {
    $utf8_with_numeric_entities = mb_convert_encoding($cp1252_with_numeric_entities, 'UTF-8', 'Windows-1252');
    return mb_decode_numericentity($utf8_with_numeric_entities,
      array(0x0, 0x2FFFF, 0, 0xFFFF), 
      'UTF-8');
  }
}