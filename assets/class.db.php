<?php

class GSSP_DB{
	function gssp_createDatabaseTables(){
	    global $wpdb;
	  
	  	$table_plugin_db = $wpdb->prefix."gssp_settings";
	    $charset_collate = $wpdb->get_charset_collate();

	    $usersTable="CREATE TABLE IF NOT EXISTS $table_plugin_db ( 
	    `id` INT(9) NOT NULL AUTO_INCREMENT, 
	    setting_name varchar(255),
	    setting_value text NOT NULL,
	    UNIQUE KEY id (id)
	    )
	    $charset_collate;";
	    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	    dbDelta( $usersTable );



	    $table_plugin_db = $wpdb->prefix."gssp_sheet_track";
	    $charset_collate = $wpdb->get_charset_collate();

	    $usersTable="CREATE TABLE IF NOT EXISTS $table_plugin_db ( 
	    `id` INT(9) NOT NULL AUTO_INCREMENT, 
	    wc_order_id varchar(255),
	    spread_sheet_range varchar(255),
	    UNIQUE KEY id (id)
	    )
	    $charset_collate;";
	    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	    dbDelta( $usersTable );


	     $wpdb->insert($wpdb->prefix."gssp_settings",array("setting_name"=>"google_sheet_auth_code","setting_value"=>""),array("%s","%s"));
	     $wpdb->insert($wpdb->prefix."gssp_settings",array("setting_name"=>"google_sheet_selected_id","setting_value"=>""),array("%s","%s"));
	     $wpdb->insert($wpdb->prefix."gssp_settings",array("setting_name"=>"user_selected_coloumns","setting_value"=>'a:10:{i:0;s:12:"date_created";i:1;s:12:"order_number";i:2;s:10:"first_name";i:3;s:9:"last_name";i:4;s:9:"address_1";i:5;s:4:"city";i:6;s:12:"item_details";i:7;s:14:"payment_method";i:8;s:6:"status";i:9;s:19:"customer_ip_address";}'),array("%s","%s"));
	     

	}

	function gssp_dropDatabaseTables(){
		global $wpdb;	
		$table_name = $wpdb->prefix."gssp_settings";
	    $sql = "DROP TABLE IF EXISTS $table_name;";
	    $wpdb->query($sql);
	    delete_option("1.0");


	    $table_name = $wpdb->prefix."gssp_sheet_track";
	    $sql = "DROP TABLE IF EXISTS $table_name;";
	    $wpdb->query($sql);
	    delete_option("1.0");
	}


	function gssp_getSettingStatus($setting_name){
		global $wpdb;

		$query='SELECT * from  '.$wpdb->prefix.'gssp_settings where setting_name ="'.$setting_name.'";';
	    
	    return $wpdb->get_results($query);
	}


	function gssp_setSettingStatus($setting_name,$value){
		global $wpdb;
		$wpdb->update($wpdb->prefix."gssp_settings", array('setting_value'=>$value), array('setting_name'=>$setting_name));
	}


	function gssp_getSelectedHeadings(){


		global $wpdb;

		$query='SELECT * from  '.$wpdb->prefix.'gssp_settings where setting_name ="user_selected_coloumns"';
	    $data=$wpdb->get_results($query);
	    $data=unserialize($data[0]->setting_value);
	    return $data;

	}




	function gssp_getLastRow(){

		global $wpdb;

		$query='SELECT * from  '.$wpdb->prefix.'gssp_sheet_track order by id desc limit 1';
	    
	    return $wpdb->get_results($query);

	}



	function gssp_insertSheetRow($wc_order_id,$spread_sheet_range){

		global $wpdb;
		$wpdb->insert($wpdb->prefix."gssp_sheet_track",array("wc_order_id"=>$wc_order_id,"spread_sheet_range"=>$spread_sheet_range),array("%s","%s"));
		return $wpdb->insert_id;

	}


	function gssp_updateSheetRow($wc_order_id,$spread_sheet_range){

		global $wpdb;
		$wpdb->update($wpdb->prefix."gssp_sheet_track", array('spread_sheet_range'=>$spread_sheet_range), array('wc_order_id'=>$wc_order_id));


	}

	function gssp_orderExistInSpreadsheet($wc_order_id){


		global $wpdb;

		$query='SELECT * from  '.$wpdb->prefix.'gssp_sheet_track where wc_order_id="'.$wc_order_id.'"';
	    
	    $response=$wpdb->get_results($query);
	    
	    if(empty($response)){
	    	return False;
	    }
	    else{


	    	return $response[0]->spread_sheet_range;
	    }

	}

}