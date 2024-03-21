<?php 
require_once( SSLW_SMS_PATH . 'lib/sslcare-sms-api.php' );
use Sslcare\Sms\Api\Sslcare_Sms_Api;

/**
 * SMS on Withdraw request 
 * amd 
 * withdraw approval
 **/    

 add_action('dokan_withdraw_created', 'xpdw_withdraw_created_handler', 10, 1 );
 function xpdw_withdraw_created_handler( $withdraw ) {
     
     $store_info  = dokan_get_store_info( $withdraw->get_user_id() ); // Get the store data
     $store_name  = $store_info['store_name'];        
     $store_phone  = $store_info['phone'];        
     $admin_phone  = get_option('admin_mobile_for_sms');
     $smstext = 'A withdraw request has been made by '. $store_name;
     
     $response = Sslcare_Sms_Api::call_to_get_api(Sslcare_Sms_Api::set_get_parameter($admin_phone, $smstext));
 }
 
 
 function custom_dokan_withdraw_approval_email( $withdraw ) {
 
     $withdraw_id = $withdraw->id; // Access the withdrawal ID
     $seller_id = $withdraw->user_id;
     $withdraw_method_detail = $withdraw->get_details();
     $withdraw_method_detail_arr =  unserialize($withdraw_method_detail);
     $withdraw_method_bkash =  $withdraw_method_detail_arr['value'];
     
     $smstext = 'Your withdrawal request of Taka '.$withdraw->amount.' has been approved by BrandWave. <br>brandwave.com';
 
     $response = Sslcare_Sms_Api::call_to_get_api(Sslcare_Sms_Api::set_get_parameter($withdraw_method_bkash, $smstext));
     // wp_mail( $admin_email, $subject, $message );
 }
 add_action( 'dokan_withdraw_request_approved', 'custom_dokan_withdraw_approval_email' );
 