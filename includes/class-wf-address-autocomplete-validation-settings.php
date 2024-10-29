<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

//Class - For settings
class Wf_Address_Autocomplete_Validation_Settings {
	protected $all_settings;
	
	//constructor
	public function __construct() {
		$this->all_settings =get_option('wf_address_autocomplete_validation_settings');
	}
	//to initialize the settings fields
	private function wf_address_autocomplete_validation_get_fields() {
		$setting_fields = array(
			'section_title' => array(
				'name' => '',
				'type' => 'title',
				'desc' => '<br>Enable the required fields to activate Address Autocomplete and Validation. Respective API keys are MANDATORY requirements.',
				'id'   => 'wf_address_autocomplete_validation_settings[wf_address_autocomplete_validation_title]'
			),

		   'enable_autocomplete' => array(
			'title'	    => 'Address Autocomplete',
		   	'type'	    => 'checkbox',
			'default'   => 'no',
		   	'desc'	    => 'Enable<br><span style="font-style: italic;font-size:13px;">Activates Google Address Autocomplete on the checkout page.</span>',
		   	'id'	    => 'wf_address_autocomplete_validation_settings[wf_aac_enable_autocomplete]',
		   	'class'	    =>'wf_aac_enable_autocomplete_class'
		       
		       ),

			'google_api_key'           => array(
				'title'           => __( 'Google API Key', 'wf_address_autocomplete_validation' ),
				'type'            => 'password',
				'desc'     => "<br>Enter the <a href='https://developers.google.com/places/web-service/autocomplete'>Google API</a> Key.<br />By default, google address autocomplete api usage is limited.<br />To increase the usage limit, check this <a href='https://developers.google.com/places/web-service/usage' target='_blank'>article from google.</a>",
				'custom_attributes' => array(
					'autocomplete' => 'off'),
				'id'   => 'wf_address_autocomplete_validation_settings[wf_address_autocomplete_validation_google_api_key]',
				'class'	   => 'wf_address_autocomplete_validation_google_api_key_class', 

			),

			'Enable_disable_autocomplete_fields' => array(
				'title' => __('Editable Address Fields','wf_address_autocomplete_validation'),
				'type' => 'checkbox',
				'default' => 'yes',
				'desc' => 'Enable<br><span style="font-style: italic;font-size:13px;">To make the checkout address fields editable when Address Autocomplete is already enabled.</span>',
				'id'	=> 'wf_address_autocomplete_validation_settings[wf_address_autocomplete_validation_enable_disable_autocomplete_fields]',
				'class'	=> 'wf_address_autocomplete_validation_enable_disable_autocomplete_fields_class',
			),	
            
            'label_name' =>array(
              'title' => __('Label Name','wf_address_autocomplete_validation'),
                'type' => 'text',
                'placeholder' => __('Address Autocomplete','wf_address_autocomplete_validation'),
                 'desc'	    => __('<br>This controls the Label text in the checkout page<span style="vertical-align: super;color:green;font-size:12px">Premium</span>','wf_address_autocomplete_validation'),
               'class'	   => 'wf_address_autocomplete_validation_label_name_class',
                'custom_attributes'=>array('disabled'=>'disabled')
                
            ),

			'section_title1' => array(
				'name' => '',
				'type' => 'title',
				'desc' => '',
				'id'   => 'wf_address_autocomplete_validation_settings[wf_address_autocomplete_validation_title1]'
			),

		    'address_validation'    =>array(
			'type'		    => 'radio_inline',
		    ),

		    'easypost_api_key' => array(
			'title'           => __( 'EasyPost API Key', 'wf_address_autocomplete_validation_get_fields' ),
			'type'            => 'password',
			'desc'     => "<br>Enter the <a href='https://www.easypost.com/pricing.html'>EasyPost API</a> Key.",
			'custom_attributes' => array(
			'autocomplete' => 'off'),
			'id'   => 'wf_address_autocomplete_validation_settings[wf_address_autocomplete_validation_easypost_api_key]',
			'class'	    => 'wf_address_autocomplete_validation_easypost_api_key_class',
			),
		    
		    'validated_address_only'	=> array(
			'title'		    => __('Enforce Address Validation','wf_address_autocomplete_validation_get_fields'),
			'type'		    => 'checkbox',
			'id'		    =>'wf_address_autocomplete_validation_settings[wf_address_autocomplete_validation_validated_address_only]',
			'desc'     => 'Enable<br><span style="font-style: italic;font-size:13px;">By enabling this, the user will not be able to proceed with checkout if address validation fails.<br />This option will be ignored if Easypost/UPS API server is down.</span>',
			'class'		    => 'wf_address_autocomplete_validation_validated_address_only',
		    ),
		    
			'record_log'           => array(
			'title'           => __( 'Debug Log', 'wf_address_autocomplete_validation' ),
			'type'            => 'checkbox',
			'default'         => 'no',
			'desc'     => 'Enable<br><span style="font-style: italic;font-size:13px;">Find request and response logs here (wp-content\uploads\wc-logs)</span>',
			'custom_attributes' => array(
			'autocomplete' => 'off'),
			'id'   => 'wf_address_autocomplete_validation_settings[wf_address_autocomplete_validation_record_log]',
			    'class'	    => 'wf_address_autocomplete_validation_record_log_class',
			),
            
            'confirm_validation'    => array(
            'title'           => __( 'Confirm Before Validation', 'wf_address_autocomplete_validation' ),
            'type'            => 'checkbox',
            'default'         => 'yes',
            'desc'     => 'Enable<br><span style="font-style: italic;font-size:13px;"> Enable it if you want customers to confirm any address before validation.</span>',
            'id'   => 'wf_address_autocomplete_validation_settings[wf_address_autocomplete_validation_confirm_validation]',
            'class'	    => 'wf_address_autocomplete_validation_confirm_validation_class',
                
            ),
		    
            'address_confirm'    =>array(
			'type'		    => 'radio_confirm',
		    ),
		    
		    'enable_address_popup_css_edit' =>array(
				'title' => __('Confirmation Window CSS','wf_address_autocomplete_validation'),
				'type'	=> 'textarea',
				'css'	=> 'width:900px; height:500px',
				'id'	=> 'wf_address_autocomplete_validation_settings[wf_address_autocomplete_validation_enable_address_popup_css_edit]',
				'class'	=> 'wf_address_autocomplete_validation_enable_address_popup_css_edit_class',
				'desc'	=> __('Modify the CSS to change the design of the address confirmation window. Leave it without modification to keep the default design. Clear the field to restore the default.','wf_address_autocomplete_validation'),
				'desc_tip'  => true,
				
		    ),
		    
			'section_end'   => array(
				'type' => 'sectionend',
				'id'   => 'wf_address_autocomplete_validation_settings[wf_address_autocomplete_validation_section1_end]'
			),	

			'section_end'   => array(
				'type' => 'sectionend',
				'id'   => 'wf_address_autocomplete_validation_settings[wf_address_autocomplete_validation_section_end]'
			)



		);
		include_once("market.php");
		return apply_filters( 'wc_settings_tab_wf_address_autocomplete_validation', $setting_fields );
	}
	//Get an option set in our settings tab
	public function wf_address_autocomplete_validation_get_option( $key ) {
		$fields = $this->wf_address_autocomplete_validation_get_fields();
		return apply_filters( 'wc_option_' . $key, wf_address_autocomplete_validation_get_option( 'wc_settings_wf_address_autocomplete_validation' . '_' . $key, ( ( isset( $fields[$key] ) && isset( $fields[$key]['default'] ) ) ? $fields[$key]['default'] : '' ) ) );
	}
	//Setup the WooCommerce settings
	public function wf_address_autocomplete_validation_setup() {
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'wf_address_autocomplete_validation_add_settings_tab' ), 70 );
		add_action( 'woocommerce_settings_tabs_wf_address_autocomplete_validation', array( $this, 'wf_address_autocomplete_validation_tab_content' ) );
		add_action( 'woocommerce_update_options_wf_address_autocomplete_validation', array( $this, 'wf_address_autocomplete_validation_update_settings' ) );
		add_action('woocommerce_admin_field_radio_inline',array( $this, 'generate_radio_inline_html'));
        add_action('woocommerce_admin_field_radio_confirm',array( $this, 'generate_radio_confirm_html'));
	}
	//Add Address Validation settings tab to the settings page
	public function wf_address_autocomplete_validation_add_settings_tab( $settings_tabs ) {
		$settings_tabs['wf_address_autocomplete_validation'] = __( 'Address Validation & Autocomplete', 'wf_address_autocomplete_validation' );
		return $settings_tabs;
	}
	//Output the tab content
		public function wf_address_autocomplete_validation_tab_content() {
		woocommerce_admin_fields( $this->wf_address_autocomplete_validation_get_fields() ); //Display settings data
		wc_enqueue_js("
			jQuery('.wf_aac_enable_autocomplete_class').on('change',function(){
				if(jQuery('.wf_aac_enable_autocomplete_class').is(':checked')){	
                
					jQuery('.wf_address_autocomplete_validation_google_api_key_class').closest('tr').show();
                    jQuery('.wf_address_autocomplete_validation_label_name_class').closest('tr').show();
					jQuery('.wf_address_autocomplete_validation_enable_disable_autocomplete_fields_class').closest('tr').show();
				}else{
					jQuery('.wf_address_autocomplete_validation_google_api_key_class').closest('tr').hide();
                    jQuery('.wf_address_autocomplete_validation_label_name_class').closest('tr').hide();
					jQuery('.wf_address_autocomplete_validation_enable_disable_autocomplete_fields_class').closest('tr').hide();
					}
			}).change();
            
            jQuery('.wf_address_autocomplete_validation_confirm_validation_class').on('change',function() {
                    if(jQuery('.wf_address_autocomplete_validation_confirm_validation_class').is(':checked')) {	
                        jQuery('#confirm_using').closest('tr').show();
                        if(jQuery('#wf_address_autocomplete_validation_settings_popup').prop('checked') && jQuery('#wf_address_autocomplete_validation_settings_popup').val() == 'popup') {
						
							jQuery('.wf_address_autocomplete_validation_enable_address_popup_css_edit_class').closest('tr').show();
                        }
                        }else {
                            jQuery('#confirm_using').closest('tr').hide();
                            jQuery('.wf_address_autocomplete_validation_enable_address_popup_css_edit_class').closest('tr').hide();
                        }
                    }).change();

            

            jQuery(document).ready(function() {
				if(jQuery('#wf_address_autocomplete_validation_settings_popup').prop('checked') || jQuery('#wf_address_autocomplete_validation_settings_msg').prop('checked'))
				{
					jQuery('#wf_address_autocomplete_validation_settings_popup').change(function(){
						if(jQuery('#wf_address_autocomplete_validation_settings_popup').prop('checked') && jQuery('#wf_address_autocomplete_validation_settings_popup').val() == 'popup')
						{
							jQuery('.wf_address_autocomplete_validation_enable_address_popup_css_edit_class').closest('tr').show();
                        }
					}).change();
                    jQuery('#wf_address_autocomplete_validation_settings_msg').change(function(){
						if(jQuery('#wf_address_autocomplete_validation_settings_msg').prop('checked') && jQuery('#wf_address_autocomplete_validation_settings_msg').val() == 'msg')
						{
							jQuery('.wf_address_autocomplete_validation_enable_address_popup_css_edit_class').closest('tr').hide();
                        }
					}).change();
					    
					}
			});
            
			
			jQuery(document).ready(function() {
				if(jQuery('#wf_address_autocomplete_validation_settings_none').prop('checked') || jQuery('#wf_address_autocomplete_validation_settings_easypost').prop('checked') )
				{
					jQuery('#wf_address_autocomplete_validation_settings_none').change(function(){
						if(jQuery('#wf_address_autocomplete_validation_settings_none').prop('checked') && jQuery('#wf_address_autocomplete_validation_settings_none').val() == 'none')
						{
                             jQuery('#confirm_using').closest('tr').hide();
                            jQuery('.wf_address_autocomplete_validation_confirm_validation_class').closest('tr').hide();
							
							jQuery('.wf_address_autocomplete_validation_enable_address_popup_css_edit_class').closest('tr').hide();
							jQuery('.wf_address_autocomplete_validation_easypost_api_key_class').closest('tr').hide();
							jQuery('.wf_address_autocomplete_validation_record_log_class').closest('tr').hide();
							jQuery('.wf_address_autocomplete_validation_validated_address_only').closest('tr').hide();
						}
					}).change();
					    
					jQuery('#wf_address_autocomplete_validation_settings_easypost').change(function(){
						if(jQuery('#wf_address_autocomplete_validation_settings_easypost').prop('checked') && jQuery('#wf_address_autocomplete_validation_settings_easypost').val() == 'easypost')
						{
                        jQuery('.wf_address_autocomplete_validation_confirm_validation_class').closest('tr').show();
                           if(jQuery('.wf_address_autocomplete_validation_confirm_validation_class').is(':checked')) {	
                            jQuery('#confirm_using').closest('tr').show();
                            if(jQuery('#wf_address_autocomplete_validation_settings_popup').prop('checked') && jQuery('#wf_address_autocomplete_validation_settings_popup').val() == 'popup') {
                             jQuery('.wf_address_autocomplete_validation_enable_address_popup_css_edit_class').closest('tr').show();
                            }
                            }else {
                            jQuery('#confirm_using').closest('tr').hide();
                            jQuery('.wf_address_autocomplete_validation_enable_address_popup_css_edit_class').closest('tr').hide();
                            }
							jQuery('.wf_address_autocomplete_validation_easypost_api_key_class').closest('tr').show();
							jQuery('.wf_address_autocomplete_validation_record_log_class').closest('tr').show();
							jQuery('.wf_address_autocomplete_validation_validated_address_only').closest('tr').show();
						}
					}).change();

				
				}
			});
		");
	}
	//Update the settings
	public function wf_address_autocomplete_validation_update_settings() {
		$restore_css=plugin_dir_path( __DIR__ ).'assests/css/address-verification-popup-default.css';
		$path=plugin_dir_path( __DIR__ ).'assests/css/address-verification-popup-current.css';
		$temp ='';
		$temp = $_POST['wf_address_autocomplete_validation_settings'];
		if(! empty($temp['wf_address_autocomplete_validation_enable_address_popup_css_edit']))
			file_put_contents($path, $temp['wf_address_autocomplete_validation_enable_address_popup_css_edit']);
		else {
			$temp['wf_address_autocomplete_validation_enable_address_popup_css_edit']=file_get_contents($restore_css);
			file_put_contents($path, file_get_contents($restore_css));
		}

		$this->all_settings['wf_aac_enable_autocomplete'] = !empty($temp['wf_aac_enable_autocomplete']) ? 'yes':'';
		$this->all_settings['wf_address_autocomplete_validation_google_api_key'] = $temp['wf_address_autocomplete_validation_google_api_key'];
		$this->all_settings['wf_address_autocomplete_validation_enable_disable_autocomplete_fields'] = !empty($temp['wf_address_autocomplete_validation_enable_disable_autocomplete_fields']) ? 'yes':'';
		$this->all_settings['wf_address_autocomplete_validation_easypost_api_key'] = $temp['wf_address_autocomplete_validation_easypost_api_key'];
		$this->all_settings['wf_address_autocomplete_validation_record_log'] = !empty($temp['wf_address_autocomplete_validation_record_log']) ? 'yes':'';
		$this->all_settings['wf_address_autocomplete_validation_validated_address_only'] = !empty($temp['wf_address_autocomplete_validation_validated_address_only']) ? 'yes' : 'no';
        $this->all_settings['wf_address_autocomplete_validation_confirm_validation'] = !empty($temp['wf_address_autocomplete_validation_confirm_validation']) ? 'yes':'';
		//$this->all_settings['wf_address_autocomplete_validation_enable_address_popup'] = !empty($temp['wf_address_autocomplete_validation_enable_address_popup']) ? 'yes':'';
		$this->all_settings['wf_address_autocomplete_validation_enable_address_popup_css_edit'] = $temp['wf_address_autocomplete_validation_enable_address_popup_css_edit'];
		update_option('wf_address_autocomplete_validation_settings', $this->all_settings);
		woocommerce_update_options( $this->wf_address_autocomplete_validation_get_fields() );
	}
	
	public function generate_radio_inline_html(){
	    ?>
		<tr>
            <th>Address Validation <?php echo wc_help_tip("This allows an address validation in checkout page"); ?></th>
		    <td>
			<input id="wf_address_autocomplete_validation_settings_none" type="radio" name="wf_address_autocomplete_validation_settings[xa_address_validation]" value="none"/><?php _e('None','wf_address_autocomplete_validation'); ?>
			<input id="wf_address_autocomplete_validation_settings_easypost" type="radio" name="wf_address_autocomplete_validation_settings[xa_address_validation]" value="easypost" /><?php _e('EasyPost','wf_address_autocomplete_validation'); ?>
            <input type="radio" disabled/><?php _e('UPS','wf_address_autocomplete_validation'); ?>
            <input type="radio" disabled/><?php _e('USPS<br> <span style="color:green;font-size:12px">[Premium includes UPS & USPS]</span>','wf_address_autocomplete_validation'); ?>
            </td>
		</tr>
	    <?php
	    $temp = isset($_POST['wf_address_autocomplete_validation_settings']) ? $_POST['wf_address_autocomplete_validation_settings'] : '';
	    if(!empty($temp['xa_address_validation']) && $temp['xa_address_validation'] !== null)		    //$_POST['xa_address_validation']
	    {
		$this->all_settings[ 'xa_address_validation' ] = $temp['xa_address_validation'];
		update_option('wf_address_autocomplete_validation_settings', $this->all_settings);
	    }
	    $check= isset($this->all_settings[ 'xa_address_validation' ]) ? $this->all_settings[ 'xa_address_validation' ] : '';
	    if(empty($check))
	    {
		
		$this->all_settings[ 'xa_address_validation' ] = 'none';
		update_option('wf_address_autocomplete_validation_settings', $this->all_settings);
		?>
		<script>
		    jQuery('#wf_address_autocomplete_validation_settings_none').prop('checked', true);
		</script>
		<?php
	    }
	    if($check === 'none')
	    {
		echo "<script>jQuery('#wf_address_autocomplete_validation_settings_none').prop('checked', true)</script>";
	    }
	    if($check === 'easypost')
	    {
		echo "<script>jQuery('#wf_address_autocomplete_validation_settings_easypost').prop('checked', true)</script>";
	    }
	}
    
     public function generate_radio_confirm_html(){
	    ?>
		<tr id="confirm_using">
            
		    <th>Confirm Using <?php echo wc_help_tip("Choose either of the options to get a confirmation from customer, if they want the Entered Address or the Suggested Address on the checkout page."); ?></th>
		    <td>
			<input id="wf_address_autocomplete_validation_settings_popup" type="radio" name="wf_address_autocomplete_validation_settings[xa_address_validation_confirm]" value="popup"/><?php _e('A Popup Window','wf_address_autocomplete_validation'); ?>
			<input id="wf_address_autocomplete_validation_settings_msg" type="radio" name="wf_address_autocomplete_validation_settings[xa_address_validation_confirm]" value="msg" /><?php _e('Checkout Page Message' ,'wf_address_autocomplete_validation'); ?>
			
		    </td>
           
		</tr>
        <?php
        
         $temp = isset($_POST['wf_address_autocomplete_validation_settings']) ? $_POST['wf_address_autocomplete_validation_settings'] : '';
	    if(!empty($temp['xa_address_validation_confirm']) && $temp['xa_address_validation_confirm'] !== null)		    //$_POST['xa_address_validation']
	    {
		$this->all_settings[ 'xa_address_validation_confirm' ] = $temp['xa_address_validation_confirm'];
		update_option('wf_address_autocomplete_validation_settings', $this->all_settings);
	    }
        $check= isset($this->all_settings[ 'xa_address_validation_confirm' ]) ? $this->all_settings[ 'xa_address_validation_confirm' ] : '';
	    if(empty($check))
	    {
		
		$this->all_settings[ 'xa_address_validation_confirm' ] = 'msg';
		update_option('wf_address_autocomplete_validation_settings', $this->all_settings);
		?>
		<script>
		    jQuery('#wf_address_autocomplete_validation_settings_msg').prop('checked', true);
		</script>
		<?php
	    }
	    if($check === 'popup')
	    {
		echo "<script>jQuery('#wf_address_autocomplete_validation_settings_popup').prop('checked', true)</script>";
	    }
	    if($check === 'msg')
	    {
		echo "<script>jQuery('#wf_address_autocomplete_validation_settings_msg').prop('checked', true)</script>";
	    }
        
        }
}
