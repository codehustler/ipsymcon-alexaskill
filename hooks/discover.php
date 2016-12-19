<?
$alexaDevices = GetAlexaDevices();
$json = generateJSONForDiscoveredAlexaDevices($alexaDevices);
echo $json;

function GetAlexaDevices()
{
	$allObjects = IPS_GetObjectList();
	$alexaObjects = array();
	foreach($allObjects as $key => $value)
	{
	   if(IPS_InstanceExists($value))
	   {
			$currentObject = IPS_GetObject($value);
			$vid = @IPS_GetVariableIDByName("alexa_name", $value);
			if($vid != null)
			{
				$alexaName = GetValue ( $vid );
			   $alexaObjects[$alexaName] = $value;
			}
		}
	}
	return $alexaObjects;
}


function generateJSONForDiscoveredAlexaDevices($alexaDevices)
{
	$json = "[";
	foreach($alexaDevices as $key => $value)
	{
		$json .= generateJSONForDevice($value) . ",";
	}
	return substr($json, 0, strlen($json)-1)."]";
}

function generateJSONForDevice($deviceId)
{
	$alexaName = GetValue (@IPS_GetVariableIDByName("alexa_name", $deviceId));
	$alexaType = GetValue (@IPS_GetVariableIDByName("alexa_type", $deviceId));
	
	$json = "{
		\"applianceId\":\"".$deviceId."\",
		\"manufacturerName\":\"Homematic\",
		\"modelName\":\"model 01\",
		\"version\":\"1.0.0\",
		\"friendlyName\":\"".$alexaName."\",
		\"friendlyDescription\":\"".$alexaName."\",
		\"isReachable\":\"True\",
		\"actions\":[
		   \"turnOn\",
		   \"turnOff\",
		   \"setPercentage\",
		   \"incrementPercentage\",
		   \"decrementPercentage\"
		],
		\"additionalApplianceDetails\":{
		   \"extraDetail1\":\"".$alexaType."\",
		   \"extraDetail2\":\"\",
		   \"extraDetail3\":\"\",
		   \"extraDetail4\":\"\"
		}
	}";
	$json = str_replace("\t", "", $json);
	$json = str_replace("\r\n", "", $json);
	return $json;
}


?>