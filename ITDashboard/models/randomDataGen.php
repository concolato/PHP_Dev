<?php
//By Claude Concolato - eDip, Fall 2015
require("mapData.php");

//Not to become part of the codebase
//Utility class for development and testing of data.
class dummyDataProcess extends data{
	public function insertDataBroDeployOverseas(){
		//Loop 260 times in for loop
		for($i=0; $i < 240; $i++){
			$query = "INSERT INTO 
				public.BroDeployOverseas (
					region, post, regularworkstation, 
					regworkdeployed, regworkremaining,
					regworkpercentcompleted, vSencompatableworkstation, 
					vSendeployed, vSenremaining, vSenpercentcompleted, 
					latitude, longitude) 
				VALUES (
					'".trim($this->randomRegion())."', '".trim($this->randomPost())."',".rand(100, 1000).",
					".rand(10, 100).",".rand(100, 1000).",
					".rand(1, 100).",".rand(100, 1000).",
					".rand(10, 100).",".rand(100, 1000).",".rand(1, 100)."
					,".$this->generateRandomFloat(90).",".$this->generateRandomFloat(180)."
			);";

			$this->connectPG($query);
		}			
	}

	public function insertDataBrodeploylocal(){
		//Loop 260 times in for loop
		for($i=0; $i < 62; $i++){
			$query = "INSERT INTO 
				public.brodeploylocal (
					bureau, regularworkstation, 
					regworkdeployed, regworkremaining,
					regworkpercentcompleted, vSencompatableworkstation, 
					vSendeployed, vSenremaining, vSenpercentcompleted
				)VALUES (
					'".trim($this->randomBureau())."',".rand(100, 1000).",
					".rand(10, 100).",".rand(100, 1000).",
					".rand(1, 100).",".rand(100, 1000).",
					".rand(10, 100).",".rand(100, 1000).",".rand(1, 100)."
			);";
			error_log($query);
			$this->connectPG($query);
		}			
	}

	public function insertDataBandwidth(){
		//Loop 260 times in for loop
		for($i=0; $i < 517; $i++){ //517
			$query = "INSERT INTO 
				public.bandwidth (
					region, post, seats, 
					avgpkwkld, growth,
					circuittype, capacity, 
					subscriptions
				)VALUES (
					'".trim($this->randomRegion())."', '".trim($this->randomPost())."',".rand(10, 100).",
					".rand(10000, 1000000).",".rand(-10, 5000).",
					'".$this->randomCircuitType()."',".rand(10000, 1000000).",
					".rand(1, 100)."
			);";
			//error_log($query);
			$this->connectPG($query);
		}			
	}

	public function randomBureau(){
		$Bureaus = array("A", "IRM", "EUR", "J", "CA","CT", "AF", "DS", "IO-NonConsolodated", "Miscellaniouse-NonConsolodated", "R_CSCCC");
		$new = array_rand($Bureaus, 1);
		
		return $Bureaus[$new];
	}

	public function randomPost(){
		$post = array("London", "Paris", "Abu Dabi", "Mexico City", "Prague","Tel Aviv", "Rome", "Petoria");
		$new = array_rand($post, 1);
		
		return $post[$new];
	}

	public function randomRegion(){
		$post = array("EAP", "NEA", "SCA", "WHA", "AF");
		$new = array_rand($post, 1);
		
		return $post[$new];
	}

	public function randomCircuitType(){
		$circuit = array("ISP", "DTS-PO");
		$new = array_rand($circuit, 1);
		
		return $circuit[$new];
	}

	public function generateRandomString($length, $type) {
		
		if($type == 1){
			$characters = 'abcdefghijklmnopqrstuvwxyz';
		}else{
			$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}
	    
	    $charactersLength = strlen($characters);
	    $randomString = '';

	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }

	    return trim($randomString);
	}

	public function generateRandomPercent(){

		return 0;
	}

	public function generateRandomFloat($digits){   // auxiliary function
	    $numdec = (float)rand(1,$digits)/3.14;
	    // returns random number with flat distribution from 1 to $digits
	    return round($numdec,2);
  	}
}
?>