<?php
require_once("class.db.php");
class GSSP_GoogleWordpressCommunicator{

	function gssp_create_heading($headings){


		$headingRow=[$headings];
		return $headingRow;




	}

	function gssp_calculate_last_row(){
		$db= new GSSP_DB();
		$data=$db->gssp_getLastRow();
		
		if($data[0]->spread_sheet_range == ""){
			return "SHEET1!A1";
		}
		else{
			return $data[0]->spread_sheet_range;
		}


	}


	function gssp_getOrderValues($headings,$order_id){


        $order = wc_get_order( $order_id );
        $order_data = $order->get_data(); // The Order data
        $items = $order->get_items();

        $itemDetails="";
        $count=1;
        foreach ( $items as $item ) {
                $itemDetails.= $count.". ".$item->get_name()." |QTY: ".$item->get_quantity()." | Item Total: ".$item->get_subtotal() ;
                $itemDetails.="\n";
                $count=$count+1;
        }

    

        // echo "<pre>";
        // print_r($order_data);
        // echo "</pre>";
        // die();
        $row=array();

        foreach ($headings as $key => $value) {

        	if($value=="first_name" || $value=="last_name" || $value=="company"  || $value=="address_1" || $value=="address_2" || $value=="city" || $value=="state" || $value=="postcode" || $value=="country" || $value=="email" || $value=="phone"){

        		$result=$order_data['billing'][$value];

        	}


        	elseif($value=="date_created" || $value=="date_modified"){

        		$result = $order_data[$value]->date('Y-m-d H:i:s');

        	}

        	else{
        		$result = $order_data[$value];
        	}


        	if($result ==""){
        		$result="None";
        	}

                if($value=="item_details"){
                        $result=$itemDetails;
                 
                }

                if($value == "order_number"){
                        $result=$order_id;
                }
			
			array_push($row,$result);
			// echo $str;
			
        	
        }

        // echo "<pre>";
        // print_r($row);
        // echo "</pre>";
        // die();


        $row=[$row];
        return $row;


	}


}
