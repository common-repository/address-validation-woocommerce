var suggestion = false;
jQuery(function ($) {


    $("#xa_myModal").css("display", "none");

    var result = '';
    //for closing the suggestion popup
    $("span.xa-closebtn").on("click", function () {
        $('#xa_myModal').css("display", "none");
        $("#place_order").removeProp("disabled");
    });
    //for the recheck address button
    $("body").on("click", "button.recheck", function () {
        $('#xa_myModal').css("display", "none");
        $("#place_order").removeProp("disabled");
    });
    //for using the original address from popup
    $("button.use_original").on("click", function () {
        $('#xa_myModal').css("display", "none");
        $("#place_order").removeProp("disabled");
        var form = $("form.checkout, form#order_review, form#add_payment_method");
        form.submit();
    });
    //for using the validated address suggestion from popup
    $("body").on("click", "button.use_validated", function () {
        $('#xa_myModal').css("display", "none");
        $("#place_order").removeProp("disabled");
        if ($('#ship-to-different-address-checkbox').is(':checked')) {
            $("#shipping_address_1").val(result.street1);
            $("#shipping_address_2").val(result.street2);
            $("#shipping_city").val(result.city);
            $("#shipping_postcode").val(result.zip);
            $('#shipping_country').val(result.country).trigger("change");
            $('#shipping_state').val(result.state).trigger("change");
        } else {
            $("#billing_address_1").val(result.street1);
            $("#billing_address_2").val(result.street2);
            $("#billing_city").val(result.city);
            $("#billing_postcode").val(result.zip);
            $('#billing_country').val(result.country).trigger("change");
            $('#billing_state').val(result.state).trigger("change");
        }
        xa_order_note();
        var form = $("form.checkout, form#order_review, form#add_payment_method");
        form.submit();
    });
    //to hook the place order button from checkpout page

    $("body").on("click", "#place_order", function () {

        var street1, street2, city, state, zip, country = '';
        $("#address_rdi").val('');
        if ($('#ship-to-different-address-checkbox').is(':checked'))
        {
            if ($("#shipping_address_1").val() == '' ||
                    $("#shipping_city").val() == '' ||
                    $("#shipping_state").val() == '' ||
                    $("#shipping_postcode").val() == '' ||
                    $("#shipping_country").val() == '') {
                //return true;
            }
            street1 = $("#shipping_address_1").val();
            street2 = $("#shipping_address_2").val();
            city = $("#shipping_city").val();
            state = $("#shipping_state").val();
            zip = $("#shipping_postcode").val();
            country = $("#shipping_country").val();
        } else
        {
            if ($("#billing_address_1").val() == '' ||
                    $("#billing_city").val() == '' ||
                    $("#billing_state").val() == '' ||
                    $("#billing_postcode").val() == '' ||
                    $("#billing_country").val() == '') {
                //return true;
            }
            street1 = $("#billing_address_1").val();
            street2 = $("#billing_address_2").val();
            city = $("#billing_city").val();
            state = $("#billing_state").val();
            zip = $("#billing_postcode").val();
            country = $("#billing_country").val();
        }
      

        //ajax call to send data to php
        $.ajax({
                             type: 'post',
                             url: wc_checkout_params.ajax_url,
                             data:
                    {
                                             action: 'wf_address_validation',
                        street1_post: street1,
                        street2_post: street2,
                        city_post: city,
                        state_post: state,
                        zip_post: zip,
                        country_post: country
                                     },
            //get response back from php
                             success: function (response) {
                result = $.parseJSON(response);
                // if validation fails Proceed with user given data
                if (result.status == 'failure') {
                    $("#place_order").removeProp("disabled");
                    var form = $("form.checkout, form#order_review, form#add_payment_method");
                    form.submit();
                    return true;
                }
                //check if the owner has any map restrictions
                if (result.map == 'undefined')
                {
                    var form = $("form.checkout, form#order_review, form#add_payment_method");
                    form.submit();
                    return true;
                }
                var same_addr = true;

                $('#original').empty();
                $('#validated').empty();
                $('#right_title').empty();
                $('#right_button').empty();
                if (result.street1 != street1) {
                    $('#original').empty();
                    same_addr = false;
                    $('#original').append('<span style="background-color:yellow !important;">' + street1 + '</span>');
                } else {
                    $('#original').empty();
                    $('#original').append(street1);
                }
                $('#original').append(',<br>');
                if (street2 != '')
                {
                    if (result.street2.toLowerCase() != street2.toLowerCase()) {
                        same_addr = false;
                        $('#original').append('<span style="background-color:yellow !important;">' + street2 + '</span>');
                    } else
                        $('#original').append(street2);
                    $('#original').append(',<br>');
                }
                if (result.city && result.city.toLowerCase() != city.toLowerCase()) {
                    same_addr = false;
                    $('#original').append('<span style="background-color:yellow !important;">' + city + '</span>');
                } else
                    $('#original').append(city);
                $('#original').append(', ');
                if (result.state.toLowerCase() != state.toLowerCase()) {
                    same_addr = false;
                    $('#original').append('<span style="background-color:yellow !important;">' + state + '</span>');
                } else
                    $('#original').append(state);
                $('#original').append(', ');
                if (result.country.toLowerCase() != country.toLowerCase()) {
                    same_addr = false;
                    $('#original').append('<span style="background-color:yellow !important;">' + country + '</span>');
                } else
                    $('#original').append(country);
                $('#original').append(' - ');
                if (result.zip != zip) {
                    same_addr = false;
                    $('#original').append('<span style="background-color:yellow !important;">' + zip + '</span>');
                } else
                    $('#original').append(zip);
                if (result.status == 'success')
                {
                    $('#right_title').append("<center><bold>Validation Successful</bold></center>");
                    $('#right_button').append("<center><button class='xa-btn xa-white xa-round-large xa-border use_validated'>Place Order with Suggested Address</button></center>");
                    if (result.rdi == true) {
                        $("#address_rdi").val('Residential');
                    } else {
                        $("#address_rdi").val('Commercial');
                    }

                    if (wf_address_autocomplete_validation_confirm_validation == 'no') {
                        $("#place_order").removeProp("disabled");
                        if ($('#ship-to-different-address-checkbox').is(':checked')) {
                            $("#shipping_address_1").val(result.street1);
                            $("#shipping_address_2").val(result.street2);
                            $("#shipping_city").val(result.city);
                            $("#shipping_postcode").val(result.zip);
                            $('#shipping_state').val(result.state).trigger("change");
                            $('#shipping_country').val(result.country).trigger("change");
                        } else {
                            $("#billing_address_1").val(result.street1);
                            $("#billing_address_2").val(result.street2);
                            $("#billing_city").val(result.city);
                            $("#billing_postcode").val(result.zip);
                            $('#billing_state').val(result.state).trigger("change");
                            $('#billing_country').val(result.country).trigger("change");
                        }
                        xa_order_note();
                        var form = $("form.checkout, form#order_review, form#add_payment_method");
                        form.submit();
                        return true;

                    }

                    $('#validated').append(result.street1);
                    $('#validated').append(',<br>');
                    if (result.street2 != '')
                    {
                        $('#validated').append(result.street2);
                        $('#validated').append(',<br>');
                    }
                    $('#validated').append(result.city);
                    $('#validated').append(', ');
                    $('#validated').append(result.state);
                    $('#validated').append(', ');
                    $('#validated').append(result.country);
                    $('#validated').append(' - ');
                    $('#validated').append(result.zip);
                    if (same_addr == false)
                        $('#xa_myModal').css("display", "block");
                    else{
                        xa_order_note();
                        var form = $("form.checkout, form#order_review, form#add_payment_method");
                        form.submit();
                        return true;
                    }

                    //..Checkout on the validated data received from Easypost server without giving option to select the users
                    if (wf_address_autocomplete_validation_enable_address_popup_obj.enable == 'no') {

                        if (suggestion) {
                            jQuery('.checkout').submit();
                        } else {
                            suggestion = true;
                            jQuery('html, body').animate({scrollTop: 0}, 'fast');
                            jQuery('#xa_addr_radio').empty();


                            addr = ((result.street1 == "") ? "" : result.street1 + ", ");
                            addr += ((result.street2 == "") ? "" : result.street2 + ", ");
                            addr += ((result.city == "") ? "" : result.city + ", ");
                            addr += ((result.state == "") ? "" : result.state + ", ");
                            addr += ((result.zip == "") ? "" : (result.zip));

                            jQuery('#customer_details').prepend('<br>');
                            jQuery('#customer_details').prepend('<div class="xa-addr-radio">');
                            jQuery('#customer_details').prepend('<input type="radio" name="xa_which_to_use" id="xa_radio_easy" value="easy"> <b> Suggestion: </b>' + addr);
                            jQuery('#customer_details').prepend('</div>');



                            //The hidden fields that get posted back to our plugin
                            jQuery('#xa_addr_radio').append("<div style='display: hidden;'>");
                            jQuery('#xa_addr_radio').append("<input type='hidden' name='xa_addr_corrected_easy_addr1' id='xa_addr_corrected_easy_addr1' value='" + result.street1 + "'>");
                            jQuery('#xa_addr_radio').append("<input type='hidden' name='xa_addr_corrected_easy_addr2' id='xa_addr_corrected_easy_addr2' value='" + result.street2 + "'>");
                            jQuery('#xa_addr_radio').append("<input type='hidden' name='xa_addr_corrected_easy_city'' id='xa_addr_corrected_easy_city' value='" + result.city + "'>");
                            jQuery('#xa_addr_radio').append("<input type='hidden' name='xa_addr_corrected_easy_state' id='xa_addr_corrected_easy_state' value='" + result.state + "'>");
                            jQuery('#xa_addr_radio').append("<input type='hidden' name='xa_addr_corrected_easy_zip' id='xa_addr_corrected_easy_zip' value='" + result.zip + "'>");
                            jQuery('#xa_addr_radio').append("</div>");


                            addr = ((street1 == "") ? "" : street1 + ", ");
                            addr += ((street2 == "") ? "" : street2 + ", ");
                            addr += ((city == "") ? "" : city + ", ");
                            addr += ((state == "") ? "" : state + ", ");
                            addr += ((zip == "") ? "" : zip);


                            jQuery('#customer_details').prepend('<div class="xa-addr-radio">');
                            jQuery('#customer_details').prepend('<input type="radio" name="xa_which_to_use" id="xa_radio_orig" value="orig" checked> <b> Use Original:  </b>' + addr);
                            jQuery('#customer_details').prepend('</div>');
                            jQuery('#customer_details').prepend('<b>There appears to be a problem with the address. Please correct or select one below.</b><br><br>');



                            //The hidden fields that get posted back to our plugin
                            jQuery('#xa_addr_radio').append("<div style='display: hidden;'>");
                            jQuery('#xa_addr_radio').append("<input type='hidden' name='xa_addr_orig_addr1' id='xa_addr_orig_addr1' value='" + street1 + "'>");
                            jQuery('#xa_addr_radio').append("<input type='hidden' name='xa_addr_orig_addr2' id='xa_addr_orig_addr2' value='" + street2 + "'>");
                            jQuery('#xa_addr_radio').append("<input type='hidden' name='xa_addr_orig_city'' id='xa_addr_orig_city' value='" + city + "'>");
                            jQuery('#xa_addr_radio').append("<input type='hidden' name='xa_addr_orig_state' id='xa_addr_orig_state' value='" + state + "'>");
                            jQuery('#xa_addr_radio').append("<input type='hidden' name='xa_addr_orig_zip' id='xa_addr_orig_zip' value='" + zip + "'>");
                            jQuery('#xa_addr_radio').append("</div>");


                            jQuery('#xa_addr_correction').show();
                            jQuery("#place_order").removeProp("disabled");
                        }

                        jQuery('input[type=radio][name=xa_which_to_use]').change(function () {
                            xa_radio_changed(this);
                        });
                    }

                } else	//for invalid addresses
                {
                    $('#right_title').append("<center><bold>Address Validation Failed</bold></center>");
                    $('#right_button').append("<center><button class='xa-btn xa-white xa-round-large xa-border recheck'>Recheck Address</button></center>");
                    $('#xa_myModal').css("display", "block");
                }
                             },
                             error: function (jqXHR, textStatus, errorThrown) {
                                     console.log(textStatus, errorThrown);
                             }
                     });
        return false;
    });


    function xa_radio_changed(item) {
        //TODO we need to work out how to select the correct state here....
        //lets copy the data into the appropriate fields
        if (item.value == 'orig') {
            //go with orig values
            street1 = jQuery('#xa_addr_orig_addr1').val();
            street2 = jQuery('#xa_addr_orig_addr2').val();
            city = jQuery('#xa_addr_orig_city').val();
            state = jQuery('#xa_addr_orig_state').val();
            zip = jQuery('#xa_addr_orig_zip').val();

        } else {
            //it is one of the corrected fields
            key = item.value;
            street1 = jQuery('#xa_addr_corrected_easy_addr1').val();
            street2 = jQuery('#xa_addr_corrected_easy_addr2').val();
            city = jQuery('#xa_addr_corrected_easy_city').val();
            state = jQuery('#xa_addr_corrected_easy_state').val();
            zip = jQuery('#xa_addr_corrected_easy_zip').val();

        }

        //OK are we shipping to different addr?
        if (jQuery('input[name=ship_to_different_address]').is(':checked')) {
            //shipping to different addr
            jQuery('#shipping_address_1').val(street1);
            jQuery('#shipping_address_2').val(street2);
            jQuery('#shipping_city').val(city);
            jQuery('#shipping_state').val(state);
            jQuery('#shipping_postcode').val(zip);
        } else {
            //shipping to billing
            jQuery('#billing_address_1').val(street1);
            jQuery('#billing_address_2').val(street2);
            jQuery('#billing_city').val(city);
            jQuery('#billing_state').val(state);
            jQuery('#billing_postcode').val(zip);

            //always update the ship to in case they select it!
            jQuery('#shipping_address_1').val(street1);
            jQuery('#shipping_address_2').val(street2);
            jQuery('#shipping_city').val(city);
            jQuery('#shipping_state').val(state);
            jQuery('#shipping_postcode').val(zip);

        }
    }
    function xa_order_note() {
     $.ajax({
                 url: wc_checkout_params.ajax_url,
                 data:
                {
                     action: 'wf_easypost_order_note'

                 }});
    }
});