<?php

require_once(dirname(__FILE__) . "/lib/utils.php");

$scenario_data = new ScenarioData();

$tpl = new Savant3();
$tpl->screens = $scenario_data->screens();
$tpl->flash = $_GET['flash'];

// Display a template using the assigned values.
$tpl->display('templates/index.tpl.php');
?>

