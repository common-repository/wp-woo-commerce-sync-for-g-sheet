<?php

require __DIR__ . '/vendor/autoload.php';
class GSSP_Google_Sheets_Manager{

	private $client;
	function __construct(){

		$this->client = new Google_Client();
	    $this->client->setApplicationName('test application');
	    $this->client->setScopes(array('https://www.googleapis.com/auth/drive.readonly','https://www.googleapis.com/auth/spreadsheets'));

	    $fileUrl=__DIR__.'/information.json';
	    $this->client->setAuthConfig($fileUrl);
	    $this->client->setAccessType('offline');
	    $this->client->setPrompt('select_account consent');
	    $this->client->setApprovalPrompt("consent");
	    $this->client->setApprovalPrompt('force');

	}

	function gssp_getAuthCode(){


	    $authUrl = $this->client->createAuthUrl();

	    return $authUrl;

	}


	function gssp_getAccessToken($authCode){
		try {
		$tokenPath = __DIR__.'\token.json';
	    if (file_exists($tokenPath)) {
	        $accessToken = json_decode(file_get_contents($tokenPath), true);
	        $this->client->setAccessToken($accessToken);
	     
	        if ($this->client->isAccessTokenExpired()) {
	            // Refresh the token if possible, else fetch a new one.
		        if ($this->client->getRefreshToken()) {
		            $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
		        } else {
		            return False;
		        }

		        if (file_exists(dirname($tokenPath))) {
		           unlink($tokenPath);
		        }
		        // Save the token to a file.
		        if (!file_exists(dirname($tokenPath))) {
		            mkdir(dirname($tokenPath), 0700, true);
		        }
		        file_put_contents($tokenPath, json_encode($this->client->getAccessToken()));
		    }
	    }
	    else{
	    	return False;
	    }

        file_put_contents($tokenPath, json_encode($this->client->getAccessToken()));
			
		} catch (Exception $e) {
			return False;
		}
		


       	return $accessToken['access_token'];
	}

	function gssp_verifyAuthCode($authCode){
		try {
		$tokenPath = __DIR__.'\token.json';
		$accessToken = $this->client->fetchAccessTokenWithAuthCode(trim($authCode));
        $this->client->setAccessToken($accessToken);
     
        // Check to see if there was an error.
        if (array_key_exists('error', $accessToken)) {
        	echo "false hogaya";
            return False;
        }


        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($this->client->getAccessToken()));
			
		} catch (Exception $e) {
			return False;
		}
		


       	return $accessToken;
	}

	function gssp_getClientObject(){

		$tokenPath = __DIR__.'\token.json';
	    if (file_exists($tokenPath)) {
	    	//echo "1";
	        $accessToken = json_decode(file_get_contents($tokenPath), true);
	        //echo "2";
	        $this->client->setAccessToken($accessToken);
	        //echo "3";
	    }
	    else{
	    	//echo "4";
	    	return False;
	    }

	    // If there is no previous token or it's expired.
	    if ($this->client->isAccessTokenExpired()) {
	    	//echo "5";
	        // Refresh the token if possible, else fetch a new one.
	        if ($this->client->getRefreshToken()) {
	            //echo "6";
	            $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
	        	//echo "7";
	        } else {
	        	//echo "8";
	            return False;
	            //echo "9";
	        }
	        // Save the token to a file.
	        if (!file_exists(dirname($tokenPath))) {
	        	//echo "10";
	            mkdir(dirname($tokenPath), 0700, true);
	            //echo "11";
	        }
	        //echo "12";
	        file_put_contents($tokenPath, json_encode($this->client->getAccessToken()));
	        //echo "13";
	    }
	    //echo "14";
	    return $this->client;
	}

	function gssp_createSpreadSheet($name=False){

		// $client= $this->gssp_getClientObject();
		// if($client){


		// }

	}


	function gssp_updateSpreadSheetData($spreadsheetId,$range,$values,$valueInputOption="USER_ENTERED"){

		// $spreadsheetId="1xe0od9Hnyig5LHUXqZcBQo-bISngY1CZxynT0IDMIxs";
		// $valueInputOption="RAW";
		// $range="SHEET1!A1";
		// $values = [
		//     [
		//         "coloumn 1","coloumn2","coloumn 3"
		//     ],
		//     [
		//         "value 1","value 2","value 3"
		//     ]
		//     // Additional rows ...
		// ];
		$client=$this->gssp_getClientObject();

		if($client){



			$service = new Google_Service_Sheets($client);
			$body = new Google_Service_Sheets_ValueRange([
			    'values' => $values
			]);
			$params = [
			    'valueInputOption' => $valueInputOption
			];

			$result = $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
			return $result->getUpdatedRange();

			}
		
	}


	function gssp_appendSpreadSheetData($spreadsheetId,$values,$valueInputOption="RAW",$range='SHEET1'){
		
		$client=$this->gssp_getClientObject();

		if($client){
			$service = new Google_Service_Sheets($client);

			// The ID of the spreadsheet to update.
			

			// The A1 notation of a range to search for a logical table of data.
			// Values will be appended after the last row of the table.
		
			$params = [
			    'valueInputOption' => $valueInputOption
			];

			// TODO: Assign values to desired properties of `requestBody`:
			$requestBody = new Google_Service_Sheets_ValueRange([
			    'values' => $values
			]);

			$response = $service->spreadsheets_values->append($spreadsheetId, $range, $requestBody,$params);

			// TODO: Change code below to process the `response` object:
			return $response->getUpdates()->getUpdatedRange();

		}
	}


	function gssp_deleteSpreadSheetData($spreadsheetId,$range=false){
		$client=$this->gssp_getClientObject();
		$service = new Google_Service_Sheets($client);


		$deleteOperation = array(
                    'range' => array(
                        'sheetId'   => 0, // <======= This mean the very first sheet on worksheet
                        'dimension' => 'ROWS',
                        'startIndex'=> 0, //Identify the starting point,
                        'endIndex'  => (0) //Identify where to stop when deleting
                    )
                );

		$deletableRow[]= new Google_Service_Sheets_Request(
                        array('deleteDimension' =>  $deleteOperation)
                    );
		// TODO: Assign values to desired properties of `requestBody`:
		$requestBody = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest(
			[
				"requests"=>$deletableRow
			]

		);

		$response = $service->spreadsheets->batchUpdate($spreadsheetId, $requestBody);

		// TODO: Change code below to process the `response` object:
		echo '<pre>', var_export($response, true), '</pre>', "\n";
	}



	function gssp_clearSpreadSheetData($spreadsheetId,$range){

		$client=$this->gssp_getClientObject();
		$service = new Google_Service_Sheets($client);

		// TODO: Assign values to desired properties of `requestBody`:
		$requestBody = new Google_Service_Sheets_ClearValuesRequest();

		$response = $service->spreadsheets_values->clear($spreadsheetId, $range, $requestBody);

		// TODO: Change code below to process the `response` object:
		echo '<pre>', var_export($response, true), '</pre>', "\n";
	}

}