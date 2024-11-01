<?php

require_once("class.db.php");
require_once("class.googleSheet.php");

class GSSP_AjaxHandler{


	function gssp_authCode(){	

		$response['success']=false;
		$response['message']="Please Enter Valid Auth Code";

		if(isset($_REQUEST['authCode']) && $_REQUEST['authCode'] != ""){
			$googleSheet= new GSSP_Google_Sheets_Manager();

			$authcod=sanitize_text_field($_REQUEST['authCode']);
			$accessToken=$googleSheet->gssp_verifyAuthCode($authcod);
			if($accessToken){
				$db= new GSSP_DB();
				$db->gssp_setSettingStatus("google_sheet_auth_code",$authcod);

				$response['success']=true;
				$response['message']="Access Token Generated Successfully";			
				
			}



			


		}



		echo json_encode($response);
		wp_die();

	}


	function gssp_handle_selectDocument(){

		$response['success']=false;
		$response['message']="Inavlid Token ID";


		if(isset($_REQUEST['documentID']) && $_REQUEST['documentID'] != ""){
			
			
			$db= new GSSP_DB();
			$docid=sanitize_text_field($_REQUEST['documentID']);
			$db->gssp_setSettingStatus("google_sheet_selected_id",$docid);

			$response['success']=true;
			$response['message']="File selection Successfull";			

		}


		echo json_encode($response);
		wp_die();

	}



	function gssp_handle_userSelectedItems(){

		$response['success']=false;
		$response['message']="Please Select Any Option";




		if(isset($_REQUEST['selectedOptions']) && $_REQUEST['selectedOptions'] != ""){
			$selectedOptions=$_REQUEST['selectedOptions'];
			$selop=array();
			foreach ($selectedOptions as $key => $value) {
				array_push($selop, sanitize_text_field($value));
			}
			$selop = array_map( 'sanitize_text_field', $selop );
			$options=serialize($selop);
			$db= new GSSP_DB();
			$db->gssp_setSettingStatus("user_selected_coloumns",$options);
			
			$response['success']=true;
			$response['message']="Field selection Successfull";			

		}


		echo json_encode($response);
		wp_die();

	}



}