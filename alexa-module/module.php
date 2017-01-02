<?
    // Klassendefinition
    class ModulnameXYZ extends IPSModule {
 
        // Der Konstruktor des Moduls
        // Überschreibt den Standard Kontruktor von IPS
        public function __construct($InstanceID) {
            // Diese Zeile nicht löschen
            parent::__construct($InstanceID);
 
            // Selbsterstellter Code
        }
 
        // Überschreibt die interne IPS_Create($id) Funktion
        public function Create() {
            // Diese Zeile nicht löschen.
            parent::Create();
 
        }
 
        // Überschreibt die intere IPS_ApplyChanges($id) Funktion
        public function ApplyChanges() {
            // Diese Zeile nicht löschen
            parent::ApplyChanges();
            
            $sid = $this->RegisterScript("AlexaHook", "Alexa Hook", "<?\n\nAlexa_handleRequest($_GET);\n\n?>");
			$this->RegisterHook("/hook/alexa", $sid);
        }
        
        /**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        * Alexa_handleDiscoverRequest($id);
        *
        */
        public function handleRequest($get) {
            $deviceId = $get['action'];            
            
            if ( "discover" == $action ) {
                return $this->handleDiscoverRequest();
            } else if ( "control" == $action ) {
                return $this->handleControlRequest($get['id'], $get['value']);
            } else if ( "health" == $action ) {
                return $this->handleHealthRequest();
            }
        }
 

        private function handleDiscoverRequest() {
            //TODO
        }
        
        private function handleHealthRequest() {
            //TODO
        }
        
        private function handleControlRequest($deviceId, $value) {
            //TODO
        }
        
        /**
        * Following function is was original developed by Paresy: https://github.com/paresy/SymconMisc/blob/master/EgiGeoZone/module.php        
        */        
        private function RegisterHook($HookEndpoint, $TargetID) {
			$ids = IPS_GetInstanceListByModuleID("{015A6EB8-D6E5-4B93-B496-0D3F77AE9FE1}");
			if(sizeof($ids) > 0) {
				$hooks = json_decode(IPS_GetProperty($ids[0], "Hooks"), true);
				$found = false;
				foreach($hooks as $index => $hook) {
					if($hook['Hook'] == $HookEndpoint) {
						if($hook['TargetID'] == $TargetID)
							return;
						$hooks[$index]['TargetID'] = $TargetID;
						$found = true;
					}
				}
				if(!$found) {
					$hooks[] = Array("Hook" => $HookEndpoint, "TargetID" => $TargetID);
				}
				IPS_SetProperty($ids[0], "Hooks", json_encode($hooks));
				IPS_ApplyChanges($ids[0]);
			}
		}        
    }
?>