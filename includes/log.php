<?php
if (!defined('ABSPATH')) {
    exit;
}

class Address_Validation_Log 
{
    public static function init_log()
    {
        $content="<------------------- Address Validation Log File  ------------------->\n";
        return $content;
    }
    
    //Function to write EasyPost and UPS response and request header for address validation in /wp-content/uploads/wc-logs/address_validation_log-****.php
    public static function log_update($msg,$title)
    {
	$all_settings = get_option('wf_address_autocomplete_validation_settings');
        $check =  $all_settings[ 'wf_address_autocomplete_validation_record_log' ];
        if('yes' === $check)
        {
            $log=new WC_Logger();
            $head="<------------------- ( ".$title." ) ------------------->\n";
            $log_text=$head.print_r((object)$msg,true);
            $log->add("address_validation_log",$log_text);
        }
    }
}
