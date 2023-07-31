$(document).ready(function() {

    if ($('.main-container.inventory-accounts-payable').length) {

        $('input[type=checkbox][data-enable-switch]').change(function() {

            var input = $("#" + $(this).attr('data-enable-switch'));
            var disabled = $(input).attr('disabled') == 'disabled';

            $(input).prop('disabled', !disabled);

            // Note the disabled attribute has been changed
            if (!disabled) $(input).val('');
        });

    } else if ($('.inventory-circuits-dids').length) {

        $('input[type=checkbox][data-delete-toggle]').change(function () {

            $('input[type=checkbox][data-delete-did]')
                .prop('checked', $(this).is(':checked') ? 'checked' : null);

            $('button#DeleteDIDsButton').prop('disabled', $(this).is(':checked') ? '' : 'disabled');
        });

        $('input[type=checkbox][data-delete-did]').change(function () {

            $('button#DeleteDIDsButton').prop('disabled', $('input[type=checkbox][data-delete-did]:checked').length ? '' : 'disabled');
        });

    } else if ($('.inventory-circuits-form').length) {

        $('#CategoryID').change(function () {
            var url = '';
            $('.Order_Inputs').find(':input').each(function(){
                if($(this).val().length){
                    url += '&' + $(this).attr('name') + '=' + encodeURIComponent($(this).val());
                }

            });
            window.location.href = $(this).attr('data-href') + '&category=' + $(this).val() + url;
        });

        // Delegate event because of dynamically added rows.
        // This could have been manually attached to each row when it is added.
        $('#FeatureRows').on('click', '.deleteFeature', function (event) {
            event.preventDefault();
            $(this).parents('.FeatureRow').remove();
        });

        $('.addFeature').click(function (event) {
            event.preventDefault();
            var regex = new RegExp('CircuitFeatures\\[([0-9]+)\\]');
            var added = 0;
            // Find last because these could be created by either Blade or JavaScript.
            $('.FeatureType, .FeatureCost').each(function () { // Looping through both FeatureType and FeatureCost should be overkill.
                var name = $(this).prop('name');
                if (name) {
                    var index = name.match(regex)[1];
                    if (index > added) {
                        added = index;
                    }
                }
            });
            added++;
            $('#FeatureTypeTemplate')
                .clone()
                .removeClass('d-none')
                .removeProp('id')
                .find('.FeatureTypeLabel')
                    .prop('for', 'FeatureType' + added)
                .end()
                .find('.FeatureType')
                    .prop('id', 'FeatureType' + added)
                    .prop('name', 'CircuitFeatures[' + added + '][FeatureType]')
                .end()
                .find('.FeatureCostLabel')
                    .prop('for', 'FeatureCost' + added)
                .end()
                .find('.FeatureCost')
                    .prop('id', 'FeatureCost' + added)
                    .prop('name', 'CircuitFeatures[' + added + '][FeatureCost]')
                .end()
                .appendTo('#FeatureRows');
            added++;
        });

    } else if ($('.inventory-mac').length) {

        $("select#MACType").change(function () {

            window.location.href = $(this).attr('data-href') + '?type=' + $(this).val();
        });

    } else if ($('.inventory-circuits-mac-edit').length) {

        $("select#MACType").change(function () {

            window.location.href = $(this).attr('data-href') + '?type=' + $(this).val();
        });

    } else if ($('.inventory-circuits-dids-create').length) {

        var select = $('select#Type');

        var switchDID = function() {

            var type = $(select).val();

            if ('range' === type) {
                $('.range-input').show();
                $('.single-input').hide();
            } else {
                $('.range-input').hide();
                $('.single-input').show();
            }

        };

        $(select).change(switchDID);

        switchDID();
    }

    //Details edit and view pages
    var carrier_details_edit = $('.carrier_details_edit');
    var carrier_details_view = $('.carrier_details_view');
    //buttons for selecting carrier details and for editing them
    var edit_carrier_details_btn = $('.edit_carrier_details_btn');
    var carrier_details_btn = $('.carrier_details_btn');
    var submit = $('.submit_update_carrier_detials_btn');

    //displays the edit form and hides the view form (EDIT)
    edit_carrier_details_btn.on('click', function(){
        carrier_details_view.hide();
        carrier_details_edit.show();
        submit.show();
        edit_carrier_details_btn.hide();
    });

    //Displays the carrier detail view and hides the form in case it was opened previously (CARRIER DETAILS)
    carrier_details_btn.on('click',function(){
        carrier_details_view.show();
        carrier_details_edit.hide();
        submit.hide();
        edit_carrier_details_btn.show();
    });

    // Delegate event because of dynamically added rows.
    // This could have been manually attached to each row when it is added.
    $('#CarrierDetailsNotesRows').on('click', '.deleteNote', function (event) {
        event.preventDefault();
        $(this).parents('.CarrierDetailsNotesRow').remove();
    });

    $('.addNote').click(function (event) {
        event.preventDefault();
        var regex = new RegExp('Notes\\[([0-9]+)\\]');
        var added = 0;
        // Find last because these could be created by either Blade or JavaScript.
        $('.CarrierDetailsNoteID, .CarrierDetailsNote').each(function () { // Looping through both BTNAccountCarrierDetailNoteID and DetailNotes should be overkill.
            var name = $(this).prop('name');
            if (name) {
                var index = name.match(regex)[1];
                if (index > added) {
                    added = index;
                }
            }
        });
        added++;
        $('#CarrierDetailsNoteTemplate')
            .clone()
            .removeClass('d-none')
            .removeProp('id')
            .find('.CarrierDetailsNoteLabel')
            .prop('for', 'DetailNotes' + added)
            .end()
            .find('.CarrierDetailsNoteID')
            .prop('id', 'BTNAccountCarrierDetailNoteID' + added)
            .prop('name', 'Notes[' + added + '][BTNAccountCarrierDetailNoteID]')
            .end()
            .find('.CarrierDetailsNote')
            .prop('id', 'DetailNotes' + added)
            .prop('name', 'Notes[' + added + '][DetailNotes]')
            .end()
            .appendTo('#CarrierDetailsNotesRows');
        added++;
    });

    var phoneRegex = /^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})?$/;
    submit.on('click', function(){
        valid = true;
        $('#carrier_detail_form *').filter(':input').each(function(){
            var name = $(this).attr('name');
            var inputValue = $(this).val();
            var input = $(this);
            switch(name){
                case 'CarrierName':
                    errorMessage(inputValue,input);
                    break;
                case 'VendorCode':
                    errorMessage(inputValue,input);
                    break;
                case 'Address1':
                    errorMessage(inputValue,input);
                    break;
                case 'City':
                    errorMessage(inputValue,input);
                    break;
                case 'State':
                    errorMessage(inputValue,input);
                    break;
                case 'Zip':
                    errorMessage(inputValue,input);
                    break;
                case 'CarrierPhoneNum':
                    errorMessage(inputValue,input);
                    validatePhone(inputValue,input);
                    break;
                case 'CarrierSupportPhoneNum':
                    errorMessage(inputValue,input);
                    validatePhone(inputValue,input);
                    break;
            }
            console.log('valid: ' + valid);
        });
        if(valid){
            $('#carrier_detail_form').submit();
        }
    });

    function validatePhone(inputValue,input){
        if(phoneRegex.test(inputValue)){
            input.parent().removeClass('has-danger');
            input.next().text('');
        }else{
            input.parent().addClass('has-danger');
            input.next().text('Phone # must be 10 digits.');
            valid = false;
        }
    }
    function errorMessage(inputValue,input){
        if(!inputValue){
            input.parent().addClass('has-danger');
            input.next().text('This field is required');
            valid = false;
        }else{
            input.parent().removeClass('has-danger');
            input.next().text('');
        }
    }
    var CarrierCircuitID = $('#CarrierCircuitID');
    var CarrierCircuitIDVal = CarrierCircuitID.val();
    var DuplicateAlertMessage = $('#duplicate-alert-message');

    CarrierCircuitID.on('keyup change', function(){
        var circuitID = null;
        if(window.location.href.split('/').length === 9) {
            circuitID = window.location.href.split('/')[7];
        }
        //Don't validate if it is the original value
        if(CarrierCircuitID.val() !== CarrierCircuitIDVal){
            // I don't like the split part
            isDuplicateCircuit($(this).val(),window.location.href.split('/')[5],circuitID);
        }
    });

    $('#close-duplicate-alert-message').click(function(){
        DuplicateAlertMessage.hide();
    });

    function isDuplicateCircuit(carrierCircuitID, btnAccountID, circuitID){
        $.ajax({
            url: '/api/collections/isduplicatecircuit',
            type: 'post',
            data: {
                'CarrierCircuitID': carrierCircuitID,
                'BTNAccountID': btnAccountID,
                'CircuitID': circuitID
            },
            success: function (data) {
                if(data.length) {
                    DuplicateAlertMessage.show();
                } else {
                    DuplicateAlertMessage.hide();
                }
            }
        });
    }


     /** Note text box on Mac form  */
     $("textarea.form-control").each(function () {
        this.setAttribute("style", "height:" + (this.scrollHeight) + "px;overflow-y:hidden;");
      }).on("input", function () {
        this.style.height = 0;
        this.style.height = (this.scrollHeight) + "px";
      })


});
