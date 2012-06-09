<?php

require_once(dirname(__FILE__) . "/lib/utils.php");

class Page {
  function __construct($request) {
    $this->request = $request;
    $this->tpl = new Savant3();
    $this->loadScenario();
    if ($this->request->isPost()) {
      $this->processFormSubmission();
    }
    $this->render();
  }

  function loadScenario() {
    $this->scenario_data = new ScenarioData();
    if ($this->scenario_data->loadData()) {
      $this->screen = $this->scenario_data->screen($this->request->get('screen_id'));
      $this->tpl->screen = $this->screen;
    } elseif(!$this->request->get('rolledback')) {
      $this->scenario_data->rollback();
      header('Location: edit.php?screen_id=' . $this->request->get('screen_id') . '&rolledback=1');
      exit;
    }
  }

  function processFormSubmission() {
    try {
      $this->scenario_data->saveScreen($this->request->post());
      $message = "Saved screen '".$this->request->post('screenFirstName') . "'";
      header('Location: index.php?flash=' .urlencode($message));
      exit;
    }
    catch (Exception $e) {
      $this->tpl->runtime_error = $e->getMessage();
    }
  }

  function render() {
    if ($this->request->get('rolledback')) {
      $this->tpl->runtime_error = "Unable to load scenario data. Rolled back to an older file";
    }
    if ($this->screen) {
      $this->tpl->page_title = 'Edit screen "' . $this->screen->screenFirstName . '"';
    }
    else {
      $this->tpl->page_title = "Edit screen - screen " . $this->request->get('screen_id') . " not found";
    }
    $this->tpl->display('templates/edit.tpl.php');
  }
}

new Page(new Request());
?>

