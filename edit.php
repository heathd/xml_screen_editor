<?php

require_once(dirname(__FILE__) . "/lib/utils.php");

$scenario_data = new ScenarioData();
$screen = $scenario_data->screen($_GET['screen_id']);

// Prepare template
$tpl = new Savant3();
$tpl->screen = $screen;

// Process form submission
if ($_POST) {
  try {
    $scenario_data->saveScreen($_POST);
    $message = "Saved screen '".$_POST['screenFirstName'] . "'";
    header('Location: index.php?flash=' .urlencode($message));
    exit;
  }
  catch (Exception $e) {
    $tpl->runtime_error = $e->getMessage();
  }
}

if ($screen) {
  $tpl->page_title = 'Edit screen "' . $screen->screenFirstName . '"';
}
else {
  $tpl->page_title = "Edit screen - screen " . $_GET['screen_id'] . " not found";
}
$tpl->display('templates/edit.tpl.php');
?>

