<?
$deviceId = $_IPS['deviceId'];
$value = $_IPS['value'];

$alexaName = GetValue (@IPS_GetVariableIDByName("alexa_name", $deviceId));
$alexaType = GetValue (@IPS_GetVariableIDByName("alexa_type", $deviceId));

IPS_LogMessage($_IPS['SELF'], "dimm " .$deviceId . " (".$alexaType.") to " . $value);

if ( $alexaType == "dimmer" ) {
	$value = $value / 100.0;
	HM_WriteValueFloat( $deviceId, "LEVEL", $value);
} else if ( $alexaType == "switch" ) {
	HM_WriteValueBoolean($deviceId, "STATE", $value == 100);
} else if ( $alexaType == "shutter" ) {
   SC_Move($deviceId, $value);
}
?>