<?
$deviceId = $_GET['id'];
$value = $_GET['value'];

IPS_RunScriptEx(23522  /*[Inventory\Scripts\alexa\control\async-rollo]*/, Array("deviceId" => $deviceId, "value" => $value));
?>