<?php
//By Claude Concolato - eDip, Fall 2015
require("envVars.php");

//Enables connecting to Postgres or MySQL
class dataBConnect extends envVars{
	protected function connectPG($query){
		try{
			$credsData = $this->credVars();
			//error_log(var_dump($credsData));

			if($query){
				$dbconn = pg_connect("host=".$credsData[0]." port=".$credsData[1]." dbname=".$credsData[2]." user=".$credsData[3]." password=".$credsData[4]."");
				$result = pg_query($dbconn, $query);
				//error_log("$query");			
				return $result;
			}else{
				error_log("$query is empty in connectPG().");
			}		
		}catch(Exception $e){
			error_log($e);
		}
	}

	protected function connectMSQL(){
		$servername = "localhost";
		$username = "developer";
		$password = "password";

		// Create connection
		$conn = new mysqli($servername, $username, $password);

		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} 
		echo "Connected successfully";
	}
}

?>