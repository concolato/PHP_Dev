<?php
abstract class envVars{
	//Not working yet
	protected function credVars(){
		$creds = array(
			0=>"localhost", 
			1=>'5432',
			2=> 'cioDashboard', //DB name
			3=>"postgres", //DB User
			4=>'mercury12',
			5=>'/Applications/MAMP/htdocs/ciodashboard/' //App Root
		);

		return $creds;
	}
}

?>