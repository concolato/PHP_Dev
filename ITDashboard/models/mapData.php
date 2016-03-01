<?php
//By Claude Concolato - eDip, Fall 2015
require("/Applications/MAMP/htdocs/ciodashboard/config/dataBConnect.php");

//Builds JSON data objects for map and charts on map page
class mapData extends dataBConnect{
	//Retuns Post Points and meta data
	public function getJsonBroDeployOverseas(){
		$query = "Select BroDeployOverseas.post, Sum(bandwidth.capacity) as capacity, BroDeployOverseas.regularworkstation, BroDeployOverseas.regworkdeployed, BroDeployOverseas.regworkremaining,
			BroDeployOverseas.latitude, BroDeployOverseas.longitude, BroDeployOverseas.regworkpercentcompleted, BroDeployOverseas.vsenpercentcompleted,
			BroDeployOverseas.region, BroDeployOverseas.vsencompatableworkstation, BroDeployOverseas.vsendeployed, BroDeployOverseas.vsenremaining
			From public.BroDeployOverseas, public.bandwidth
			Where BroDeployOverseas.post = bandwidth.post
			Group By BroDeployOverseas.post, BroDeployOverseas.region, 
			BroDeployOverseas.regworkpercentcompleted, BroDeployOverseas.vsenpercentcompleted, 
			BroDeployOverseas.regularworkstation, BroDeployOverseas.regworkdeployed, BroDeployOverseas.regworkremaining,
			BroDeployOverseas.vsencompatableworkstation,
			BroDeployOverseas.vsendeployed, BroDeployOverseas.vsenremaining,
			BroDeployOverseas.latitude, BroDeployOverseas.longitude; 
		"; // There might be a better way to do this.
		$result = $this->connectPG($query);			
		$rows = pg_fetch_all($result);	

		$jsonArrayOfObjs = "[";
		foreach($rows as $keys => $datums){
			//Build JSON string
			$rows2 = array_keys($rows);
			if(end($rows2) == $keys){
				//This will be the last element in the array of objects
				$jsonArrayOfObjs .= "{";
			    	$jsonArrayOfObjs .= "\"region\":\"".trim($datums['region'])."\", \"post\":\"".trim($datums['post'])."\","; 
			    	$jsonArrayOfObjs .= "\"regularworkstation\":".$datums['regularworkstation'].", \"regworkdeployed\":".$datums['regworkdeployed'].",";
			    	$jsonArrayOfObjs .= "\"regworkremaining\":".$datums['regworkremaining'].", \"regworkpercentcompleted\":".$datums['regworkpercentcompleted'].",";
			    	$jsonArrayOfObjs .= "\"vsencompatableworkstation\":".$datums['vsencompatableworkstation'].", \"vsendeployed\":".$datums['vsendeployed'].",";
			    	$jsonArrayOfObjs .= "\"vsenremaining\":".$datums['vsenremaining'].", \"vsenpercentcompleted\":".$datums['vsenpercentcompleted'].", \"capacity\":".$datums['capacity'].",";
			    	$jsonArrayOfObjs .= "\"latitude\":".$datums['latitude'].", \"longitude\":".$datums['longitude']."";
		    	$jsonArrayOfObjs .= "}";
			}else{
				$jsonArrayOfObjs .= "{";
			  		$jsonArrayOfObjs .= "\"region\":\"".trim($datums['region'])."\", \"post\":\"".trim($datums['post'])."\",";  
			    	$jsonArrayOfObjs .= "\"regularworkstation\":".$datums['regularworkstation'].", \"regworkdeployed\":".$datums['regworkdeployed'].",";
			    	$jsonArrayOfObjs .= "\"regworkremaining\":".$datums['regworkremaining'].", \"regworkpercentcompleted\":".$datums['regworkpercentcompleted'].",";
			    	$jsonArrayOfObjs .= "\"vsencompatableworkstation\":".$datums['vsencompatableworkstation'].", \"vsendeployed\":".$datums['vsendeployed'].",";
			    	$jsonArrayOfObjs .= "\"vsenremaining\":".$datums['vsenremaining'].", \"vsenpercentcompleted\":".$datums['vsenpercentcompleted'].", \"capacity\":".$datums['capacity'].",";
			    	$jsonArrayOfObjs .= "\"latitude\":".$datums['latitude'].", \"longitude\":".$datums['longitude']."";
		    	$jsonArrayOfObjs .= "},";
			}

		} 
		$jsonArrayOfObjs .= "]"; 
		//error_log($jsonArrayOfObjs);

		$cleanJsonStr = json_decode($jsonArrayOfObjs);

		//Validate the JSON
		if($cleanJsonStr === NULL){error_log("Error in JSON in getJsonBroDeployOverseas() method."); }else{return $jsonArrayOfObjs;}
	}
	//Builds GEOJSON for Density Map
	public function appendGeoJson(){
		//This might be made dynamic in the future, not sure yet.
		$table = "BroDeployOverseas"; // do be used later if tables need to be dynamic
		//Query that sums up all the regions' data.
		$query = "Select Avg(BroDeployOverseas.regworkpercentcompleted) as regworkpercentcompleted, 
			Avg(bandwidth.capacity) as Bcapacity,
			Avg(BroDeployOverseas.vsenpercentcompleted) as vsenpercentcompleted, BroDeployOverseas.region 
			from public.BroDeployOverseas, public.bandwidth
			Where BroDeployOverseas.region = bandwidth.region 
			OR BroDeployOverseas.post = bandwidth.post
			Group By BroDeployOverseas.region;";

		$result = $this->connectPG($query);			
		$rows = pg_fetch_all($result);

		$filename1 = "js/geometry/dos_region_None.geojson";
		$jsonStr1 = file_get_contents($filename1);
		$jsonObj1 = json_decode($jsonStr1);
		//var_dump($jsonObj1);

		if(file_exists($filename1)){			
			foreach($jsonObj1->features as $metrodata){
				foreach($rows as $dbData){ // Can't think of a better way to do this than O(N^2)				
					if($metrodata->properties->dos_region == trim($dbData['region'])){
						if(isset($dbData['regworkpercentcompleted'])){
							$metrodata->properties->regworkpercentcompleted = round($dbData['regworkpercentcompleted'],2);
						}else{
							$metrodata->properties->regworkpercentcompleted = 0;
						}

						if(isset($dbData['vsenpercentcompleted'])){
							$metrodata->properties->vsenpercentcompleted = round($dbData['vsenpercentcompleted'],2);
						}else{
							$metrodata->properties->vsenpercentcompleted = 0;
						}

						if(isset($dbData['bcapacity'])){
							$metrodata->properties->capacity = round($dbData['bcapacity'],2);
						}else{
							$metrodata->properties->capacity = 0;
						}						
					}else{					
						//error_log($dbData['region']);
						//error_log($metrodata->properties->dos_region);
					}
				}
			}
						
			$jsonStr1 = json_encode($jsonObj1);
			//error_log($jsonStr1);
			$cleanJsonStr = json_decode($jsonStr1);

			//Validate the JSON
			if($cleanJsonStr === NULL){
				error_log("There is an issue with your json in appendJson().\n");
			}else{
				//error_log("JSON is correct");
				return $jsonStr1;
			}
		}else{
			error_log("File: ".$filename1." does not exist in appendJson().\n");
		}
	}

	//Build data object for Bar Chart
	public function getBromiumLocal(){
		$query = "SELECT bureau, Sum(regularworkstation) as regularworkstation,
			Sum(regworkdeployed) as regworkdeployed,
			Sum(regworkremaining) as regworkremaining,
			Avg(regworkpercentcompleted) as regworkpercentcompleted,
			Sum(vsencompatableworkstation) as vsencompatableworkstation,
			Sum(vsendeployed) as vsendeployed, Sum(vsenremaining) as vsenremaining,
			Avg(vsenpercentcompleted) as vsenpercentcompleted
			From public.brodeploylocal 
			Group By brodeploylocal.bureau; ";

		$result = $this->connectPG($query);			
		$rows = pg_fetch_all($result);	

		//echo "<pre>";
		//var_dump($rows);
		//echo "</pre>";
		//$end = end($row);

		$jsonArrayOfObjs = "[";
		foreach($rows as $keys => $datums){
			//Build JSON string
			$rows2 = array_keys($rows);
			$regworkpercentcompleted = round($datums['regworkpercentcompleted'],2);
			$vsenpercentcompleted = round($datums['vsenpercentcompleted'],2);

			if(end($rows2) == $keys){
				
				//This will be the last element in the array of objects
				$jsonArrayOfObjs .= "{";
			    	$jsonArrayOfObjs .= "\"bureau\":\"".trim($datums['bureau'])."\","; 
			    	$jsonArrayOfObjs .= "\"regularworkstation\":".$datums['regularworkstation'].", \"regworkdeployed\":".$datums['regworkdeployed'].",";
			    	$jsonArrayOfObjs .= "\"regworkremaining\":".$datums['regworkremaining'].", \"regworkpercentcompleted\":".$regworkpercentcompleted.",";
			    	$jsonArrayOfObjs .= "\"vsencompatableworkstation\":".$datums['vsencompatableworkstation'].", \"vsendeployed\":".$datums['vsendeployed'].",";
			    	$jsonArrayOfObjs .= "\"vsenremaining\":".$datums['vsenremaining'].", \"vsenpercentcompleted\":".$vsenpercentcompleted."";
		    	$jsonArrayOfObjs .= "}";
			}else{
				$jsonArrayOfObjs .= "{";
			  		$jsonArrayOfObjs .= "\"bureau\":\"".trim($datums['bureau'])."\","; 
			    	$jsonArrayOfObjs .= "\"regularworkstation\":".$datums['regularworkstation'].", \"regworkdeployed\":".$datums['regworkdeployed'].",";
			    	$jsonArrayOfObjs .= "\"regworkremaining\":".$datums['regworkremaining'].", \"regworkpercentcompleted\":".$regworkpercentcompleted.",";
			    	$jsonArrayOfObjs .= "\"vsencompatableworkstation\":".$datums['vsencompatableworkstation'].", \"vsendeployed\":".$datums['vsendeployed'].",";
			    	$jsonArrayOfObjs .= "\"vsenremaining\":".$datums['vsenremaining'].", \"vsenpercentcompleted\":".$vsenpercentcompleted."";
		    	$jsonArrayOfObjs .= "},";
			}
		} 
		$jsonArrayOfObjs .= "]"; 
		//error_log($jsonArrayOfObjs);

		$cleanJsonStr = json_decode($jsonArrayOfObjs);

		//Validate the JSON
		if($cleanJsonStr === NULL){error_log("Error in JSON in getBromiumLocal() method."); }else{return $jsonArrayOfObjs;}
	}

	public function getBandwidth(){
		$query = "Select bandwidth.post, Sum(bandwidth.capacity) as capacity, bandwidth.region, 
			bandwidth.seats, bandwidth.growth
			From public.bandwidth
			Group By bandwidth.region, bandwidth.post, bandwidth.seats, bandwidth.growth
			ORDER BY bandwidth.region;
		";
		$result = $this->connectPG($query);			
		$rows = pg_fetch_all($result);	

		$regioDataAF = $this->filterRegion($rows, "AF");
		$regioDataWHA = $this->filterRegion($rows, "WHA");
		$regioDataSCA = $this->filterRegion($rows, "SCA");
		$regioDataNEA = $this->filterRegion($rows, "NEA");
		$regioDataEUR = $this->filterRegion($rows, "EUR");
		$regioDataEAP = $this->filterRegion($rows, "EAP");

		$jsonArrayOfObjs = "{\"name\": \"bandwidth\", \"children\":[";
			$jsonArrayOfObjs .= "{\"name\":\"AF\", \"children\":[";
				$jsonArrayOfObjs .= $this->bandwidthJSONSnippet($regioDataAF, "AF");
			$jsonArrayOfObjs .= "]},";
			$jsonArrayOfObjs .= "{\"name\":\"WHA\", \"children\":[";
			 	$jsonArrayOfObjs .= $this->bandwidthJSONSnippet($regioDataWHA, "WHA");
			$jsonArrayOfObjs .= "]},";
			$jsonArrayOfObjs .= "{\"name\":\"SCA\", \"children\":[";
			 	$jsonArrayOfObjs .= $this->bandwidthJSONSnippet($regioDataSCA, "SCA");
			$jsonArrayOfObjs .= "]},";
			$jsonArrayOfObjs .= "{\"name\":\"NEA\", \"children\":[";
				$jsonArrayOfObjs .= $this->bandwidthJSONSnippet($regioDataNEA, "NEA");
			$jsonArrayOfObjs .= "]},";
			$jsonArrayOfObjs .= "{\"name\":\"EUR\", \"children\":[";
				$jsonArrayOfObjs .= $this->bandwidthJSONSnippet($regioDataEUR, "EUR");
			$jsonArrayOfObjs .= "]},";
			$jsonArrayOfObjs .= "{\"name\":\"EAP\", \"children\":[";
				$jsonArrayOfObjs .= $this->bandwidthJSONSnippet($regioDataEAP, "EAP");
			$jsonArrayOfObjs .= "]}";

		$jsonArrayOfObjs .= "]}"; 
		//error_log($jsonArrayOfObjs);

		$cleanJsonStr = json_decode($jsonArrayOfObjs);

		//Validate the JSON
		if($cleanJsonStr === NULL){error_log("Error in JSON in getBandwidth() method."); }else{return $jsonArrayOfObjs;}
	}

	private function filterRegion($largerarray, $filter){
		$filteredArray = array();

		foreach($largerarray as $keys => $datums){
			if(trim($datums['region']) == $filter){
				array_push($filteredArray, $datums);
			}
		}
		return $filteredArray;
	}

	private function bandwidthJSONSnippet($data, $filter){
		$jsonArrayOfObjs = "";
		$count = count($data);
		$index = 1;

		foreach($data as $keys => $datums){
			//Build JSON string
			if(trim($datums['region']) == $filter){
				if($index == $count){		
					//This will be the last element in the array of objects
					$jsonArrayOfObjs .= "{\"post\":\"".trim($datums['post'])."\", \"size\":\"".trim($datums['capacity'])."\"}";		    	
				}else{
			  		$jsonArrayOfObjs .= "{\"post\":\"".trim($datums['post'])."\", \"size\":\"".trim($datums['capacity'])."\"},";
				}								
			}
			
			$index++;			
		}
		return $jsonArrayOfObjs;
	}

	private function minifyJson($jsonStr){
		$string = trim($jsonStr);
		return $string;
	}
}

/*
Algorithm:
1. 

*/
?>