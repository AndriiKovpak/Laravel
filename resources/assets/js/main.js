$(document).ready(function () {
    if (false) {
        $('input[type="date"]').each(function () {
            var $this = $(this);
            try {
                $this.val($.datepicker.formatDate('mm/dd/yy', $.datepicker.parseDate('yy-mm-dd', $this.val())));
            } catch (exception) {
                // Do nothing.
            }
            $this.datepicker({ dateFormat: 'mm/dd/yy' });
        });

        $(window).on('resize orientationchange', function () {
            var $elActive = $(document.activeElement);
            if ($elActive.is('.hasDatepicker')) {
                $elActive.datepicker('widget').position({
                    my: 'left top',
                    at: 'left bottom',
                    of: $elActive
                });
            }
        });
    }

    $('a[data-delete-form]').click(function () {

        $(this).parents('td').find('form')[0].submit();
        return false;
    });

    $(document).on('click', 'a[data-confirmation-btn]', function () {
        var button = $(this);
        var confirm = ($(button).attr('data-confirmation-btn') == 'true');

        if (confirm) {

            $(button).parents('tr').find('td').hide();
            $(button).parents('tr').find('td[data-confirmation-body]').css('display', 'table-cell');

        } else {

            $(button).parents('tr').find('td').show();
            $(button).parents('tr').find('td[data-confirmation-body]').css('display', 'none');
        }

        return false;
    });

    $('input[data-address-search]').each(function (index, input) {

        var $input = $(input);
        var limit = 10;
        var $json = $('#' + $input.attr('data-address-json'));
        var $string = $('#' + $input.attr('data-address-string'));
        var $remittanceId = $('#' + $input.attr('data-address-remittance-id'));
        var source = $input.attr('data-address-source');
        var type = $input.attr('data-address-type');
        var addressType = $input.attr('data-address-addresstype');
        var $NewOrUpdate = $('#' + type + 'AddressNewOrUpdate');

        $(input).easyAutocomplete({

            url: function (phrase) {
                return '/api/collections/addresses?phrase=' + encodeURIComponent(phrase);
            },

            getValue: 'string',

            ajaxSettings: {
                dataType: 'json',
                method: 'GET',
                data: {
                    addressType: addressType,
                    limit: limit,
                    source: source ? source : 'main'
                }
            },

            list: {
                maxNumberOfElements: limit,
                match: {
                    enabled: true
                },
                onChooseEvent: function () {
                    var item = $input.getSelectedItemData();
                    $json.val(JSON.stringify(item.address));
                    $string.val(item.string);
                    $.each(item.address, function (key, value) {
                        $NewOrUpdate.find('[name="' + key + type + '"]').val(value);
                    });
                    $remittanceId.val(item.address.RemittanceAddressID);
                    /* Uncomment to re-enable update button
                    $('#' + type + 'AddressButtonsUpdate').show();
                    */
                }
            },

            requestDelay: 200,

            listLocation: 'items',

            matchResponseProperty: 'inputPhrase'
        });

    }).on('change', function () {
        var $this = $(this);
        if ($this.val() === '') {
            var type = $this.attr('data-address-type');
            var $NewOrUpdate = $('#' + type + 'AddressNewOrUpdate');
            var $AddressJSON = $('#' + type + 'AddressJSON');
            var Address = $AddressJSON.val() ? JSON.parse($AddressJSON.val()) : {};
            $.each(Address, function (key, value) {
                $NewOrUpdate.find('[name="' + key + type + '"]').val('');
            });
        }
    });

    function initializeAddress(type) {
        var $Existing = $('#' + type + 'AddressExisting');
        var $NewOrUpdate = $('#' + type + 'AddressNewOrUpdate');
        var $Buttons = $('#' + type + 'AddressButtons');
        var $AddressJSON = $('#' + type + 'AddressJSON');

        $('[name="' + type + 'AddressType"]').on('change', function () {
            var value = $('[name="' + type + 'AddressType"]:checked').val();
            var Address = $AddressJSON.val() ? JSON.parse($AddressJSON.val()) : {};
            switch (value) {
                case 'new':
                    $.each(Address, function (key, value) {
                        $NewOrUpdate.find('[name="' + key + type + '"]').val('');
                    });
                    $Existing.hide();
                    $NewOrUpdate.show();
                    $Buttons.hide();
                    break;
                /* Uncomment to re-enable update button
                case 'update':
                    $.each(Address, function(key, value) {
                        $NewOrUpdate.find('[name="' + key + type + '"]').val(value);
                    });
                    $Existing.hide();
                    $NewOrUpdate.show();
                    $Buttons.hide();
                    break;
                */
                case 'existing':
                default:
                    $.each(Address, function (key, value) {
                        $NewOrUpdate.find('[name="' + key + type + '"]').val(value);
                    });
                    $Existing.show();
                    $NewOrUpdate.hide();
                    $Buttons.show();
            }
        });
    }

    initializeAddress('Service');
    initializeAddress('LocationA');
    initializeAddress('LocationZ');
    initializeAddress('Remittance');
    initializeAddress('Site');

    //the width keeps being set and I am not sure where it is being set. This fixes it but I don't like it.
    $('.easy-autocomplete').css('width', '100%');

    function isNumber(obj) {
        return !isNaN(parseFloat(obj));
    }

    //Shows svg loading icon if compatible, if not shows gif.
    if (false) {
        document.getElementById("loading").src = loadingSVG;
    } else {
        document.getElementById("loading").src = loadingGIF;
    }

    $('.carousel').carousel({
        interval: false
    });

    //Makes the alert messages full width on the screen
    $('.alert').parent().parent().parent().css('padding-left', '0px');
    $('.alert').parent().parent().parent().css('padding-right', '0px');

    $('.menu-mob').on('click', function () {
        $(this).toggleClass('expanded');
        $('.mobile').slideToggle();
    });
});
