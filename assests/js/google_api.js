var placeSearch, billing_autocomplete,shipping_autocomplete;
//billing_autocomplete - search bar for billing address
//shipping_autocomplete - search bar for shipping address

//componentForm - to select the preferred result format
var componentForm = {
  administrative_area_level_1 : 'short_name',   
  administrative_area_level_2 : 'short_name',
  country                     : 'short_name',
  locality                    : 'long_name',
  postal_code                 : 'long_name',
  premise                     : 'long_name',
  route                       : 'long_name',
  street_address              : 'long_name',
  street_number               : 'long_name',
  sublocality                 : 'long_name',
  sublocality_level_1         : 'long_name',
};
//billing_mappingForm - local billing variables with google variables  
var billing_mappingForm = {
  administrative_area_level_1 : 'billing_state',   
  country                     : 'billing_country',
  locality                    : 'billing_city',
  administrative_area_level_2 : 'billing_state',
  postal_code                 : 'billing_postcode',
  premise                     : 'billing_address_1',
  route                       : 'billing_address_1',
  street_address              : 'billing_address_1',
  street_number               : 'billing_address_1',
  sublocality                 : 'billing_address_2',
  sublocality_level_1         : 'billing_address_2'
};
//shipping_mappingForm - local shipping variables with google variables
var shipping_mappingForm = {
  administrative_area_level_1 : 'shipping_state',   
  administrative_area_level_2 : 'shipping_state',
  country                     : 'shipping_country',
  locality                    : 'shipping_city',
  postal_code                 : 'shipping_postcode',
  premise                     : 'shipping_address_1',
  route                       : 'shipping_address_1',
  street_address              : 'shipping_address_1',
  street_number               : 'shipping_address_1',
  sublocality                 : 'shipping_address_2',
  sublocality_level_1         : 'shipping_address_2'
};
//onload function to clear the checkout fields

window.onload = function(){
    
    //jQuery('#address_rdi').prop("hidden","hidden");
    document.getElementById('address_rdi').value = '';
    jQuery("#address_rdi").hide();
    document.getElementById('billing_autocomplete').value = '';
    if(jQuery('#ship-to-different-address-checkbox').is(':checked')){
	document.getElementById('shipping_autocomplete').value = '';
    }
  
  jQuery.ajax({
                 type: 'post',
                 url: wc_checkout_params.ajax_url,
                 data: 
                      {
                        action: 'wf_address_validation_enable_disable',
                      },
                 success: function(results) {                        
                        result = jQuery.parseJSON(results);                        
                       if(result.status_enable_disable == 'yes'){
				enable_disable_address_fields(false);
                        }else{
				jQuery('#billing_country').val('').trigger("change");
				jQuery('#billing_state').val('').trigger("change");
				document.getElementById('billing_address_1').value = '';
				document.getElementById('billing_address_2').value = '';
				document.getElementById('billing_city').value = '';
				document.getElementById('billing_postcode').value = '';
				enable_disable_address_fields(true);
                        }
                  }
              });
}

function enable_disable_address_fields($x){
	
	if(jQuery('#ship-to-different-address-checkbox').is(':checked'))
	{
		jQuery('#shipping_country').val('').trigger("change");
		jQuery('#shipping_state').val('').trigger("change");
		document.getElementById('shipping_address_1').value = '';
		document.getElementById('shipping_address_2').value = '';
		document.getElementById('shipping_city').value = '';
		document.getElementById('shipping_postcode').value = '';
	}
	
	document.getElementById('billing_address_1').disabled = $x;
	document.getElementById('billing_address_2').disabled = $x;
	document.getElementById('billing_city').disabled = $x;
	document.getElementById('billing_state').disabled = $x;
	document.getElementById('billing_country').disabled = $x;
	document.getElementById('billing_postcode').disabled = $x;
	
	if(jQuery('#ship-to-different-address-checkbox').is(':checked'))
	{
		document.getElementById('shipping_address_1').disabled = $x;
		document.getElementById('shipping_address_2').disabled = $x;
		document.getElementById('shipping_city').disabled = $x;
		document.getElementById('shipping_state').disabled = $x;
		document.getElementById('shipping_country').disabled = $x;
		document.getElementById('shipping_postcode').disabled = $x;
	}
};

//For Billing Address
function initAutocomplete() {
  // Create the autocomplete object, restricting the search to geographical
  // location types.
  billing_autocomplete = new google.maps.places.Autocomplete(
      /** @type {!HTMLInputElement} */(document.getElementById('billing_autocomplete')),
      {types: ['geocode']});
  shipping_autocomplete = new google.maps.places.Autocomplete(
      /** @type {!HTMLInputElement} */(document.getElementById('shipping_autocomplete')),
      {types: ['geocode']});

  // When the user selects an address from the dropdown, populate the address
  // fields in the form.
  billing_autocomplete.addListener('place_changed', billing_fillInAddress);
  shipping_autocomplete.addListener('place_changed', shipping_fillInAddress);
}
function billing_fillInAddress() {
  // Get the place details from the autocomplete object.
  var place = billing_autocomplete.getPlace();
  for (var component in componentForm) {
    //to autoclear the fields after every search 
    document.getElementById(billing_mappingForm[component]).value = '';
    //to enable the input fields after search
    document.getElementById(billing_mappingForm[component]).disabled = false;
  }
  if(place.address_components != undefined && place.address_components != null)
  {
  // Get each component of the address from the place details
  // and fill the corresponding field on the form.
  //to update the state select field after updating country select fields
    for (var i = 0; i < place.address_components.length; i++) {
    var addressType = place.address_components[i].types[0];
      if (componentForm[addressType]) {
        var val = place.address_components[i][componentForm[addressType]];
        if(billing_mappingForm[addressType] === 'billing_country')
        {
            jQuery('#billing_country').val(val).trigger("change");
        }
      }
    }
    //to update fields with appropiate values recieved from google api
    for (var i = 0; i < place.address_components.length; i++) {
      var addressType = place.address_components[i].types[0];
      if (componentForm[addressType]) {
        var val = place.address_components[i][componentForm[addressType]];
        if(addressType === 'premise')
        {
          document.getElementById('billing_address_1').value += val + " ";
        }
        else if(addressType === 'street_number')
        {
          document.getElementById('billing_address_1').value += val + " ";
        }
        else if(addressType === 'street_address')
        {
          document.getElementById('billing_address_1').value += val + " ";
        }
        else if(addressType === 'route')
        {
          document.getElementById('billing_address_1').value += val;
        }
        else if(addressType === 'sublocality')
        {
          document.getElementById('billing_address_2').value += val + " ";
          document.getElementById('billing_city').value = val;
        }
        else if(addressType === 'sublocality_level_1')
        {
          document.getElementById('billing_address_2').value += val;
        }
        else if(billing_mappingForm[addressType] === 'billing_state')
        {
            jQuery('#billing_state').val(val).trigger("change");  
        }
        else
        {
            if(val !=null)
            document.getElementById(billing_mappingForm[addressType]).value = val;
        }
      }
    }
  }
}
// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function billing_geolocate() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var geolocation = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      var circle = new google.maps.Circle({
        center: geolocation,
        radius: position.coords.accuracy
      });
      billing_autocomplete.setBounds(circle.getBounds());
    });
  }
}
//For shipping Address
function shipping_fillInAddress() {
  var place = shipping_autocomplete.getPlace();
  for (var component in componentForm) {
    //to autoclear the fields after every search 
    document.getElementById(shipping_mappingForm[component]).value = '';
    //to enable the input fields after search
    document.getElementById(shipping_mappingForm[component]).disabled = false;
  }
  // Get each component of the address from the place details
  // and fill the corresponding field on the form.
  //to update the state select field after updating country select fields
  for (var i = 0; i < place.address_components.length; i++) {
  var addressType = place.address_components[i].types[0];
    if (componentForm[addressType]) {
      var val = place.address_components[i][componentForm[addressType]];
      if(shipping_mappingForm[addressType] === 'shipping_country')
      {
          jQuery('#shipping_country').val(val).trigger("change");
      }
    }
  }
  //to update fields with appropiate values recieved from google api
  for (var i = 0; i < place.address_components.length; i++) {
    var addressType = place.address_components[i].types[0];
    if (componentForm[addressType]) {
      var val = place.address_components[i][componentForm[addressType]];
      if(addressType === 'premise')
      {
        document.getElementById('shipping_address_1').value += val + " ";
      }
      else if(addressType === 'street_number')
      {
        document.getElementById('shipping_address_1').value += val + " ";
      }
      else if(addressType === 'street_address')
      {
        document.getElementById('shipping_address_1').value += val + " ";
      }
      else if(addressType === 'route')
      {
        document.getElementById('shipping_address_1').value += val;
      }
      else if(addressType === 'sublocality')
      {
        document.getElementById('shipping_address_2').value += val + " ";
        document.getElementById('shipping_city').value = val;
      }
      else if(addressType === 'sublocality_level_1')
      {
        document.getElementById('shipping_address_2').value += val;
      }
      else if(shipping_mappingForm[addressType] === 'shipping_state')
      {
          jQuery('#shipping_state').val(val).trigger("change");  
      }
      else
      {
          if(val !=null)
          document.getElementById(shipping_mappingForm[addressType]).value = val;
      }
    }
  }
}
// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function shipping_geolocate() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var geolocation = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      var circle = new google.maps.Circle({
        center: geolocation,
        radius: position.coords.accuracy
      });
      shipping_autocomplete.setBounds(circle.getBounds());
    });
  }
}



