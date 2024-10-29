<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Class - For autocompletion of address fields
class Wf_Address_Autocomplete {
	protected $all_settings;
	//constructor
	public function __construct() {
		$this->all_settings = get_option( 'wf_address_autocomplete_validation_settings' );
		if(defined('WC_VERSION') && WC_VERSION < '3.0')
		{
			add_filter( 'woocommerce_before_checkout_billing_form', array($this,'wf_address_autocomplete_validation_fields'));
		}
		
		else 
		{
			add_filter( 'woocommerce_checkout_fields', array($this,'wf_address_autocomplete_validation_fields_create'));
			add_filter( 'woocommerce_checkout_fields', array($this,'wf_address_autocomplete_validation_fields_arrange'));
		}
		add_action( 'wp_footer', array( $this,'wf_address_autocomplete_validation_scripts' ));
		add_action( 'woocommerce_after_order_notes', array( $this,'wf_address_autocomplete_validation_rdi_field') );
		add_action( 'woocommerce_checkout_update_order_meta',array( $this, 'wf_address_autocomplete_validation_rdi_update_order_meta') );
		add_action( 'woocommerce_admin_order_data_after_billing_address', array( $this,'wf_address_autocomplete_validation_rdi_display_admin_order_meta'), 10, 1 );
	}
	//Add RDI field to the checkout
	function wf_address_autocomplete_validation_rdi_field( $checkout ) {
	    woocommerce_form_field( 'address_rdi', array(
	        'type'          => 'text',
	        'class'         => array('my-field-class form-row-wide'),
	        'required'		=> false,
	        ), $checkout->get_value( 'address_rdi' ));
	}

	//Update the order meta with RDI field value
	function wf_address_autocomplete_validation_rdi_update_order_meta( $order_id ) {
	    if ( ! empty( $_POST['address_rdi'] ) ) {
	        update_post_meta( $order_id, 'RDI', sanitize_text_field( $_POST['address_rdi'] ) );
	    }
	}
	//display RDI field value on the order edit page
	function wf_address_autocomplete_validation_rdi_display_admin_order_meta($order){
		$wf_order_id = ( defined('WC_VERSION') && WC_VERSION < '3.0' ) ? $order->ID : $order->get_id();
	    echo '<p><strong>'.__('Residential Delivery Indicator').':</strong> ' . get_post_meta( $wf_order_id, 'RDI', true ) . '</p>';
	}

	//to create two new address autocomplete fields in woocommerce version less than 3.0
	public function wf_address_autocomplete_validation_fields( $checkout_fields ) {
	    foreach($checkout_fields->checkout_fields['billing'] as $key=>$value)
	    {
		$temp_billing_fields[$key] = $value;
		
		if($key == 'billing_phone')
		{
			$temp_billing_fields['billing_autocomplete'] = array(
				'label'	    => __('Address Autocomplete', 'woocommerce'),
				'placeholder'   => _x('Search for address', 'placeholder', 'woocommerce'),
				'required'	    => false,
				'class'	    => array('form-row-wide'),
				'clear'	    => true
			);
		}
	    }
	    $checkout_fields->checkout_fields['billing'] = $temp_billing_fields;
	    
	    foreach($checkout_fields->checkout_fields['shipping'] as $key=>$value)
	    {
		$temp_shipping_fields[$key] = $value;
		
		if($key == 'shipping_company')
		{
			$temp_shipping_fields['shipping_autocomplete'] = array(
				'label'	    => __('Address Autocomplete', 'woocommerce'),
				'placeholder'   => _x('Search for address', 'placeholder', 'woocommerce'),
				'required'	    => false,
				'class'	    => array('form-row-wide'),
				'clear'	    => true
			);
		}
	    }
	    $checkout_fields->checkout_fields['shipping'] = $temp_shipping_fields;
	     return $checkout_fields;
	}
	
	//to create two new address autocomplete fields 
	public function wf_address_autocomplete_validation_fields_create( $fields ) {
		 $fields['billing']['billing_autocomplete'] = array(
	        'label'     => __('Address Autocomplete', 'woocommerce'),
		    'placeholder'   => _x('Search for address', 'placeholder', 'woocommerce'),
		    'required'  => false,
		    'class'     => array('form-row-wide'),
		    'clear'     => true
	     );
	     $fields['shipping']['shipping_autocomplete'] = array(
	        'label'     => __('Address Autocomplete', 'woocommerce'),
		    'placeholder'   => _x('Search for address', 'placeholder', 'woocommerce'),
		    'required'  => false,
		    'class'     => array('form-row-wide'),
		    'clear'     => true
	     );
	     return $fields;
	}
	//to rearrange the input fields
	//bug - reshift country manually using array
	public function wf_address_autocomplete_validation_fields_arrange( $fields ) {
		$billing_order = array(
			"billing_first_name", 
			"billing_last_name",
			"billing_company",
			"billing_autocomplete",
			"billing_email", 
			"billing_phone",
			"billing_address_1", 
			"billing_address_2", 
			"billing_city",
			"billing_state",
			"billing_postcode", 
			"billing_country"
		);
		// This sets the billing fields in the order above
		foreach($billing_order as $billing_field) {

		    $billing_fields[$billing_field] = $fields["billing"][$billing_field];
		    unset($fields['billing'][$billing_field]);
		}
		$remaining_fields_billing = array();
		if(!empty($fields["billing"]))
		{
			$remaining_fields_billing[]=$fields["billing"];
			$fields["billing"] = array_merge($billing_fields, $remaining_fields_billing);
		}
		else
		{
			$fields["billing"] = $billing_fields;
		}

		$shipping_order = array(
			"shipping_first_name", 
			"shipping_last_name", 
			"shipping_company", 
			"shipping_autocomplete",
			"shipping_address_1", 
			"shipping_address_2",
			"shipping_city",
			"shipping_state", 
			"shipping_postcode", 
			"shipping_country"
		);
		// This sets the shipping fields in the order above
		foreach($shipping_order as $shipping_field) {
		    $shipping_fields[$shipping_field] = $fields["shipping"][$shipping_field];
		    unset($fields['shipping'][$shipping_field]);
		}
		$remaining_fields_shipping = array();
		if(!empty($fields["shipping"]))
		{
			$remaining_fields_shipping[]=$fields["shipping"];
			$fields["shipping"] = array_merge($shipping_fields, $remaining_fields_shipping);
		}
		else
		{
			$fields["shipping"] = $shipping_fields;
		}
		return $fields;
	}
	//to add the necessary js scripts and css styles
	public function wf_address_autocomplete_validation_scripts() {
		if(is_checkout()&&!is_order_received_page())
		{
			wp_enqueue_script( 'wf-address-common-script', plugins_url( '../assests/js/google_api.js', __FILE__ ), array( 'jquery' ) );
			wp_enqueue_script( 'wf-address-google-script', "https://maps.googleapis.com/maps/api/js?key=".$this->all_settings['wf_address_autocomplete_validation_google_api_key']."&libraries=places&callback=initAutocomplete" );
		}		
	}
}