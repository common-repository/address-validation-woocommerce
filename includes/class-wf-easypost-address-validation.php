<?php

if (!defined('ABSPATH')) {
    exit;
}

//Class - For validation of address fields 
class Wf_Address_Validation {

    //constructor
    public $all_settings;

    public function __construct() {
        $this->all_settings = get_option('wf_address_autocomplete_validation_settings');
        add_action('wp_footer', array($this, 'wf_address_validation_scripts'));
        add_action('woocommerce_checkout_update_order_meta', array($this, 'wf_order_note'));
        apply_filters('xa_exclude_validation',false);
    }
    
    
    function wf_order_note($order_id) {
        $validated = get_option('addr_val_easy');
        if($validated) {
            $order = new WC_order($order_id);
            $order->add_order_note("This order address is Validated");
        }
        update_option('addr_val_easy',false);
        delete_option('addr_val_easy');
    }
    
    public function wf_order_note_easypost() {
        update_option('addr_val_easy',true);
    }

    //validation function using EasyPost API
    public function wf_address_validation_easypost() {
        if(function_exists('xa_exclude_addr_validation')) { //code snippet to exclude address validation.
            $exclude_val = xa_exclude_addr_validation();
            if($exclude_val) {
                $address_params = array(
                    "status" => 'failure'
                );
            die(json_encode($address_params));
            }
        }
        // create address
        $address_params = array(
            "verify" => array("delivery"),
            "street1" => $_POST["street1_post"],
            "street2" => $_POST["street2_post"],
            "city" => $_POST["city_post"],
            "state" => $_POST["state_post"],
            "zip" => $_POST["zip_post"],
            "country" => $_POST["country_post"],
        );
        $record = isset($this->all_settings['wf_address_autocomplete_validation_record_log']) ? $this->all_settings['wf_address_autocomplete_validation_record_log'] : '';
        if ($record == 'yes')
            Address_Validation_Log::log_update($address_params, 'Easypost Request');
        //get option values		
        $easypost_api_key = $this->all_settings['wf_address_autocomplete_validation_easypost_api_key'];
        try {

            \EasyPost\EasyPost::setApiKey($easypost_api_key);
            $address = \EasyPost\Address::create_and_verify($address_params, $easypost_api_key);
            //for successful validation cases
            if ($record == 'yes') {
                Address_Validation_Log::log_update(trim($address), 'Easypost Response');
            }
            if ($address->verifications->delivery->success) {
                $vali_address_params = array(
                    "status" => 'success',
                    "street1" => $address->street1,
                    "street2" => $address->street2,
                    "city" => $address->city,
                    "state" => $address->state,
                    "zip" => $address->zip,
                    "country" => $address->country,
                    "rdi" => $address->residential,
                );
                die(json_encode($vali_address_params));
            }
        } catch (Exception $e) {  //for restricted API access
            $add = $e->getTrace();
            $custom['Error'] = $add['0']['args'];
            $custom['Custom_msg'] = 'EasyPost address validation got failed so checkout has been processed on user given address.';
            $error = array("status" => 'failure', 'error' => $e->getMessage(), "map" => "undefined");
            if ($this->all_settings['wf_address_autocomplete_validation_validated_address_only'] == 'yes') {
                $custom['Custom_msg'] = 'Checkout has been stopped as given address could not be verified.';
                if (isset($add['0']['args']['2']['error']['code']) && $add['0']['args']['2']['error']['code'] == 'ADDRESS.VERIFY.FAILURE') {
                    wc_add_notice(__('Unable to verify address :'), 'error');
                    if (isset($add['0']['args']['2']['error']['errors']['0']['code']))
                        foreach ($add['0']['args']['2']['error']['errors'] as $ttemp) {
                            if ($ttemp['code'] == 'E.ADDRESS.NOT_FOUND')
                                wc_add_notice(__('- Address not found.'), 'error');
                            if ($ttemp['code'] == 'E.HOUSE_NUMBER.INVALID')
                                wc_add_notice(__('- House number is invalid.'), 'error');
                            if ($ttemp['code'] == 'E.HOUSE_NUMBER.MISSING')
                                wc_add_notice(__('- House number is missing.'), 'error');
                            if ($ttemp['code'] == 'E.STREET.INVALID')
                                wc_add_notice(__('- Street is invalid.'), 'error');
                            if ($ttemp['code'] == 'E.STATE.INVALID')
                                wc_add_notice(__('- Invalid state.'), 'error');
                            if ($ttemp['code'] == 'E.CITY_STATE.INVALID')
                                wc_add_notice(__('- Unverifiable city/state.'), 'error');
                            if ($ttemp['code'] == 'E.ADDRESS.INSUFFICIENT')
                                wc_add_notice(__('- Insufficient/incorrect address data.'), 'error');
                            if ($ttemp['code'] == 'E.ZIP.INVALID')
                                wc_add_notice(__('- Invalid zip.'), 'error');
                            if ($ttemp['code'] == 'E.ADDRESS.INVALID')
                                wc_add_notice(__('- Invalid city/state/zip.'), 'error');
                            if ($ttemp['code'] == 'E.STREET.MISSING')
                                wc_add_notice(__('- Street is missing.'), 'error');
                            if ($ttemp['code'] == 'E.INPUT.INVALID')
                                wc_add_notice(__('- Street1 is required.'), 'error');
                        }
                }
            }

            Address_Validation_Log::log_update($custom, 'Easypost Response');
            die(json_encode($error));
        }
    }

    public function wf_address_validation_scripts() {
        if (is_checkout() && !is_order_received_page()) {
            wp_enqueue_script('wf-address-validate-script', plugins_url('../assests/js/address_validate-easypost.js', __FILE__), array('jquery'));
            wp_enqueue_style('wf-modal-style-manadatory', plugins_url('../assests/css/address-verification-popup-manadatory.css', __FILE__));
            wp_enqueue_style('wf-modal-style', plugins_url('../assests/css/address-verification-popup-current.css', __FILE__));

            $html = "
	<div id='xa_addr_correction' class='' style='display: none;'>
		
		<div id='xa_orig_addr'>
			<div id='xa_addr_radio' class='xa-addr-radio'></div>	
			<div style='display: none;' id='xa_orig_placeholder'></div>						
		</div>		
	</div>
	";
            echo $html;
            echo '<div id="xa_error_placeholder"></div>';

            //add the ajax url var
            $html = '
			<script type="text/javascript">
			var ajaxurl = "' . admin_url('admin-ajax.php') . '";
			</script>				
		';

            echo $html;

            if (isset($this->all_settings['wf_address_autocomplete_validation_confirm_validation']) && $this->all_settings['wf_address_autocomplete_validation_confirm_validation'] == 'yes')
                wp_localize_script('wf-address-validate-script', 'wf_address_autocomplete_validation_confirm_validation', 'yes');
            else
                wp_localize_script('wf-address-validate-script', 'wf_address_autocomplete_validation_confirm_validation', 'no');

            //for address suggestion popup
            $address_validation_popup = $this->all_settings['xa_address_validation_confirm'];
            if ($address_validation_popup != null && $address_validation_popup == 'popup') {
                if (isset($this->all_settings['wf_address_autocomplete_validation_validated_address_only']) && $this->all_settings['wf_address_autocomplete_validation_validated_address_only'] == 'yes')
                    $xa_address_popup = array(//To avoid warning in console log
                        'enable' => 'yes',
                        'validation_fail_checkout' => 'no'
                    );
                else {
                    $xa_address_popup = array(//To avoid warning in console log
                        'enable' => 'yes',
                        'validation_fail_checkout' => 'yes'
                    );
                }
                wp_localize_script('wf-address-validate-script', 'wf_address_autocomplete_validation_enable_address_popup_obj', $xa_address_popup);
                echo "	
				  <div id='xa_myModal' class='xa-modal' style='z-index:9999999 !important'>
				    <div class='xa-modal-content'>
				      	<div class='xa-container xa-white '>
				      		<div class='xa-container xa-white'>
				      			<span class='xa-closebtn'>&times;</span>
					      	</div>
					      	<table class='xa-popup' border='0'>
								<tr>
								  	<th><center><bold>Original Address</bold></center></th>
								  	<th id='right_title'></th>
								</tr>
								<tr>
								  	<td id='original' class='xa-center'></td>
								  	<td id='validated' class='xa-center'></td>
								</tr>
								<tr>
								  	<td><center><button class='xa-btn xa-white xa-round-large xa-border use_original'>Place Order with Original Address</button></center></td>
								  	<td id='right_button'></td>
								</tr>
							</table>
				    	</div>
				  	</div>
				</div>";
            } else {
                if (isset($this->all_settings['wf_address_autocomplete_validation_validated_address_only']) && $this->all_settings['wf_address_autocomplete_validation_validated_address_only'] == 'yes')
                    $xa_address_popup = array(
                        'enable' => 'no',
                        'validation_fail_checkout' => 'no'
                    );
                else
                    $xa_address_popup = array(
                        'enable' => 'no',
                        'validation_fail_checkout' => 'yes'
                    );
                wp_localize_script('wf-address-validate-script', 'wf_address_autocomplete_validation_enable_address_popup_obj', $xa_address_popup);
            }
        }
    }

}

$obj = new Wf_Address_Validation();
add_action('wp_ajax_wf_address_validation', array($obj, 'wf_address_validation_easypost'));
add_action('wp_ajax_nopriv_wf_address_validation', array($obj, 'wf_address_validation_easypost'));
add_action('wp_ajax_wf_easypost_order_note', array($obj, 'wf_order_note_easypost'));
add_action('wp_ajax_nopriv_wf_easypost_order_note', array($obj, 'wf_order_note_easypost'));
