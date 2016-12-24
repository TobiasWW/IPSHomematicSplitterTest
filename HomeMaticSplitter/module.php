<?
    class HomeMaticSplitter extends IPSModule
	{
        public function Create()
		{
			//Never delete this line!
			parent::Create();
			
			// Verbinde mit vorhandenem Splitter oder erstelle gegebenfalls einen neuen
			$this->ConnectParent("{A151ECE9-D733-4FB9-AA15-7F7DD10C58AF}");
		}	

		public function Destroy()
		{
			//Never delete this line!
			parent::Destroy();
		}

		public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();
		}

		//Daten vom I/O
		public function ReceiveData($JSONString) 
		{
			$DeviceRX = "{2B26E867-7B89-44B2-9DA9-6D40D91AA37A}";
			
			IPS_LogMessage(__CLASS__ . "." . __FUNCTION__ , $JSONString);		
			
			//Einfach die Daten weiterleiten
			//Empfangene Daten vom I/O
			$data = json_decode($JSONString);
			$data->DataID = $DeviceRX;
			
			$this->SendDataToChildren(json_encode($data));
		}	
	
		//Daten der Devices
		public function ForwardData($JSONString) 
		{
			$DeviceTX = "{62287761-A688-4952-811B-03CBB8767A6A}";
			$IOTX = "{75B6B237-A7B0-46B9-BBCE-8DF0CFE6FA52}";
			
			IPS_LogMessage(__CLASS__ . "." . __FUNCTION__ , $JSONString);		
			$resultat = false;
			
			/* Array Format */
			$bspArray = Array
			(
				"DataID" => $DeviceTX,
				"Protocol" => 0, //0 = Funk, 1 = RS485, 2 = IP
				"Address" => "XXX9999990:0", //Die HM-Adresse mit :0 bzw. :1 usw.
				"VariableName" => "SETPOINT", //Name der Variable die gesetzt werden soll, im Zweifel wird ein Upper gemacht...
				"VariableType" => "float",    //Von welchem Typ ist die Variable... wegen Aufruf HM_Set.... bla -> lower machen
				"VariableValue" => "20"       //Wert, der gesetzt werden soll
			);
			//Empfangene Daten von der Device Instanz
			$data = json_decode($JSONString);
			
			if ($data->DataID <> $DeviceTX)
			{
				IPS_LogMessage(__CLASS__ . "." . __FUNCTION__, "Falsche Daten-GUID");
				return false;
			}
			
			$dummyID = 56667;
			IPS_SetProperty($dummyID, "Protocol", $data->Protocol);
			IPS_SetProperty($dummyID, "Address", $data->Address);
			IPS_ApplyChanges($dummyID);
			
			switch ($data->VariableType)
			{
				case "float" : 
				{
					$resultat = HM_WriteValueFloat($dummyID, $data->VariableName, $data->VariableValue);
					break;
				}
				case "bool" : 
				{
					$resultat = HM_WriteValueBoolean($dummyID, $data->VariableName, $data->VariableValue);
					break;
				}
				case "string" : 
				{
					$resultat = HM_WriteValueString($dummyID, $data->VariableName, $data->VariableValue);
					break;
				}
				case "int" : 
				{
					$resultat = HM_WriteValueInteger($dummyID, $data->VariableName, $data->VariableValue);
					break;
				}
				default :
				{
					$data->DataID = $IOTX;
					$resultat = $this->SendDataToParent($data);
				}
			}
			
			return $resultat;
		 
		}	
    }
?>