<?php
/*
    Plugin Name: Wordpress WooCommerce Sync for Google Sheet
    Plugin URI: https://www.h3techs.com/
    Description: Connecting your Google Sheet with your WooCommerce site is just a click away. All orders received on WooCommerce Wordpress site will directly fetched in seconds to your Google Sheet.
    Author: H3 Technologies (Pvt.) Limited 
    Version: 1.0
    Author URI: https://www.h3techs.com
    WC tested up to: 3.9.0
*/

if ( ! function_exists( 'wwcsfgs_fs' ) ) {
    // Create a helper function for easy SDK access.
    function wwcsfgs_fs() {
        global $wwcsfgs_fs;

        if ( ! isset( $wwcsfgs_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $wwcsfgs_fs = fs_dynamic_init( array(
                'id'                  => '5448',
                'slug'                => 'wp-woo-commerce-sync-for-g-sheet',
                'type'                => 'plugin',
                'public_key'          => 'pk_24524b2af2b7fbb4ccceb7755d3b0',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'slug'           => 'g-s-s-p',
                    'account'        => false,
                ),
            ) );
        }

        return $wwcsfgs_fs;
    }

    // Init Freemius.
    wwcsfgs_fs();
    // Signal that SDK was initiated.
    do_action( 'wwcsfgs_fs_loaded' );
}

require_once("assets/class.db.php");
require_once("assets/class.frontEnd.php");
require_once("assets/class.googleSheet.php");
require_once("assets/class.ajaxHandler.php");
require_once("assets/class.googleWordpressCommunicator.php");

function gssp_activateFunction(){
	$database=new GSSP_DB;
    $database->gssp_createDatabaseTables();
}
function gssp_deactivationFunction(){
	$database=new GSSP_DB;
	$database->gssp_dropDatabaseTables();
}
register_activation_hook(__FILE__,'gssp_activateFunction');
register_deactivation_hook( __FILE__, 'gssp_deactivationFunction' );

function gssp_registerMenu(){
	$menu = add_menu_page('Settings Page', 'WooCommerce G Sheet Sync', 'manage_options', 'g-s-s-p', 'gssp_settingsPageNewUI',plugins_url("wp-woo-commerce-sync-for-g-sheet/images/wp-woocommerce-to-g-sheet.png"));
	add_action('admin_print_styles-'. $menu,'gssp_adminPanelCss');
}



function gssp_settingsPageNewUI(){

	if ( !class_exists( 'WooCommerce' ) ) {

		echo '<div class="alert alert-warning" role="alert">
				  Please Install WooCommerce Plugin first ! <a href="plugin-install.php?s=WooCommerce&tab=search&type=term">Click Here</a>
				</div>';

        die();

	}

	$adminPage= new GSSP_FrontEnd();
	$googleSheet=new GSSP_Google_Sheets_Manager();

	$authCode=$googleSheet->gssp_getAuthCode();
    $db= new GSSP_DB();
    $db_authCode=$db->gssp_getSettingStatus("google_sheet_auth_code")[0]->setting_value;
    $token=$googleSheet->gssp_getAccessToken($db_authCode);
    echo '<div class="container" style="float:left; margin-top: 30px;">';

     if(!$token){
            $adminPage->gssp_notAuthorized();
    }
    


    $adminPage->gssp_adminPanelIndexPage($authCode,$db_authCode,$token);	



    $options=array(
    'status',
    'currency',
    'date_created',
    'date_modified',
    'discount_total',
    'discount_tax',
    'shipping_total',
    'shipping_tax',
    'cart_tax',
    'total',
    'total_tax',
    'order_key',
    'first_name',
    'last_name',
    'company',
    'address_1',
    'address_2',
    'city',
    'state',
    'postcode',
    'country',
    'email',
    'phone',
    'payment_method',
    'payment_method_title',
    'transaction_id',
    'customer_ip_address',
    'created_via',
    'date_paid',
    'item_details',
    'order_number'
);

    $preselectedItems=array();    
    $psi=$db->gssp_getSettingStatus("user_selected_coloumns");
    if($psi[0]->setting_value != ""){

        
        $preselectedItems=unserialize($psi[0]->setting_value);

    }
   
    $unselectedItems=array_diff($options,$preselectedItems);

    if($token){
        $selectedSpreadSheet=$db->gssp_getSettingStatus("google_sheet_selected_id");
        
        $selectedSpreadSheet=$selectedSpreadSheet[0]->setting_value;
        
        if($selectedSpreadSheet !=""){
            $response =$adminPage->gssp_getAllSpreadSheetNames($token,$selectedSpreadSheet);
        }
        
        else{
            $response=$adminPage->gssp_getAllSpreadSheetNames($token);
        }
        
        //yay condition is liay lagayi hai kyun token expire hojayay tau woe gssp_getAllSpreadSheetNames walay function say pata chal ta hai agar token false hai tau neechay ka part bhi show nahi karaya hai
        if($response!=False){
            $adminPage->gssp_selectHeadingsToShow($preselectedItems,$unselectedItems);
        }
        
    }
    

    echo '</div>';


}
$GSSP_AjaxHandler = new GSSP_AjaxHandler(); 

add_action( 'wp_ajax_authCode', array($GSSP_AjaxHandler, 'gssp_authCode') );
add_action('admin_menu', 'gssp_registerMenu');
add_action( 'wp_ajax_selectDocument', array($GSSP_AjaxHandler, 'gssp_handle_selectDocument') );
add_action( 'wp_ajax_userSelectedItems', array($GSSP_AjaxHandler, 'gssp_handle_userSelectedItems') );



function gssp_adminPanelCss($hook){
    wp_enqueue_style( 'stylesheet_name_test',plugins_url( '/css/bootstrap.min.css', __FILE__ ));
    wp_enqueue_script("jquery");
    
    wp_enqueue_script( 'script-name-sweetAlert', plugins_url( '/js/sweetalert2@8.js', __FILE__ ));
   	wp_enqueue_style( 'stylesheet_name_sweetAlertCss', plugins_url( '/css/sweetalert2.min.css', __FILE__ ));
    
    wp_enqueue_script( 'ajax-script', plugins_url( '/js/js.js', __FILE__ ));
    wp_enqueue_style( 'stylesheet_name_test2', plugins_url( '/css/css.css', __FILE__ )); 


    wp_enqueue_script( 'script-name-select2', plugins_url( '/js/select2.min.js', __FILE__ ));
    wp_enqueue_style( 'stylesheet_name_select2',plugins_url( '/css/select2.min.css', __FILE__ ));


    wp_register_script('my-amazing-script',plugins_url( '/js/bootstrap.min.js', __FILE__ ),'jquery','1.1', true);
    wp_enqueue_script('my-amazing-script','jquery');
  
}



#add_action( 'woocommerce_order_status_processing', 'bsp_order_status_processing');

add_action( 'woocommerce_order_status_processing', 'gssp_order_status_processing');
add_action( 'woocommerce_order_status_on-hold', 'gssp_order_status_processing_on_hold');

add_action( 'woocommerce_order_status_completed', 'gssp_order_status_completed');
add_action( 'woocommerce_order_status_failed', 'gssp_order_status_failed');
add_action( 'woocommerce_order_status_pending', 'gssp_order_status_pending');
add_action( 'woocommerce_order_status_refunded', 'gssp_order_status_refunded');
add_action( 'woocommerce_order_status_cancelled', 'gssp_order_status_cancelled');

add_action('woocommerce_thankyou', 'gssp_order_status_thankyou', 10, 1);
function gssp_order_status_completed($order_id){
     gssp_UpdateGoogleSheet($order_id);
}
function gssp_order_status_failed($order_id){
     gssp_UpdateGoogleSheet($order_id);
}
function gssp_order_status_pending($order_id){
     gssp_UpdateGoogleSheet($order_id);
}
function gssp_order_status_refunded($order_id){
     gssp_UpdateGoogleSheet($order_id);
}
function gssp_order_status_cancelled($order_id){
     gssp_UpdateGoogleSheet($order_id);
}


function gssp_order_status_processing($order_id){

    gssp_UpdateGoogleSheet($order_id);

}
function gssp_order_status_processing_on_hold($order_id){
    gssp_UpdateGoogleSheet($order_id);
}



function gssp_order_status_thankyou($order_id){
    $order = wc_get_order( $order_id );

}


function gssp_UpdateGoogleSheet($order_id){
    $db= new GSSP_DB();

        $googleSheet= new GSSP_Google_Sheets_Manager();
        $communicator=new GSSP_GoogleWordpressCommunicator();
        $headings=$db->gssp_getSelectedHeadings();
        
        $values=$communicator->gssp_create_heading($headings);

        $sheetID=$db->gssp_getSettingStatus("google_sheet_selected_id")[0]->setting_value;
        $range="SHEET1!A1:AZ1";
        $data=$googleSheet->gssp_clearSpreadSheetData($sheetID,$range);

        $range="SHEET1!A1";
        $googleSheet->gssp_updateSpreadSheetData($sheetID,$range,$values);

        $oldOrder=$db->gssp_orderExistInSpreadsheet($order_id);

        if($oldOrder){
            $values=$communicator->gssp_getOrderValues($headings,$order_id);
            $oldOrder2=explode(":",$oldOrder)[0];
            $data=$googleSheet->gssp_clearSpreadSheetData($sheetID,$oldOrder);
            $record=$googleSheet->gssp_updateSpreadSheetData($sheetID,$oldOrder2,$values);
            $db->gssp_updateSheetRow($order_id,$record);

        }
        else{
            $values=$communicator->gssp_getOrderValues($headings,$order_id);
            $record =$googleSheet->gssp_appendSpreadSheetData($sheetID,$values);
            $db->gssp_insertSheetRow($order_id,$record);
        }
        //$range=$communicator->gssp_calculate_last_row();
 
}
