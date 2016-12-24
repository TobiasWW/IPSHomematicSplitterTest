<?
    class HomeMaticSplitterDevice extends IPSModule
	{
		private function v_dump($mixed = null)
		{
			$content = "IST NULL";
			if (isset($mixed))
			{
				ob_start();
				var_dump($mixed);
				$content = ob_get_contents();
				ob_end_clean();
			}
			return $content;
		}		
		
        public function __construct($InstanceID)
		{
            parent::__construct($InstanceID);
        }

        public function Create()
		{
            parent::Create();
		}

        public function ApplyChanges()
		{
            parent::ApplyChanges();
        }
		
		public function ReceiveData($JSONString) 
		{
			IPS_LogMessage(__CLASS__ . "." . __FUNCTION__, $JSONString);
		}	
	
		public function SendeArray($arr)
		{
			IPS_LogMessage(__CLASS__ . "." . __FUNCTION__, json_encode($arr));
			$res = $this->SendDataToParent(json_encode($arr));
			IPS_LogMessage(__CLASS__ . "." . __FUNCTION__, "Result: " . $this->v_dump($res));
		}	
	
		public function SendeDaten(string $JSONString)
		{
			IPS_LogMessage(__CLASS__ . "." . __FUNCTION__, $JSONString);
			$res = $this->SendDataToParent($JSONString);
			IPS_LogMessage(__CLASS__ . "." . __FUNCTION__, "Result: " . $this->v_dump($res));
		}
	
		public function SendeSetzenTest($HMID, $varTyp, $var, $wert)
		{
			$DeviceTX = "{62287761-A688-4952-811B-03CBB8767A6A}";
			
			/* Array Format */
			$arr = Array
			(
				"DataID" => $DeviceTX,
				"Protocol" => 0, //0 = Funk, 1 = RS485, 2 = IP
				"Address" => $HMID, //Die HM-Adresse mit :0 bzw. :1 usw.
				"VariableName" => $var, //Name der Variable die gesetzt werden soll, im Zweifel wird ein Upper gemacht...
				"VariableType" => $varTyp,    //Von welchem Typ ist die Variable... wegen Aufruf HM_Set.... bla -> lower machen
				"VariableValue" => $wert       //Wert, der gesetzt werden soll
			);
			
			IPS_LogMessage(__CLASS__ . "." . __FUNCTION__, json_encode($arr));
			$res = $this->SendDataToParent(json_encode($arr));
			IPS_LogMessage(__CLASS__ . "." . __FUNCTION__, "Result: " . $this->v_dump($res));
		}
    }
?>