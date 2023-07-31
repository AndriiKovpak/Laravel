/**
 * Created by bcooper on 3/10/2017.
 */


$(document).ready(function() {

    var errorSpan = $('.before_file');

    $('#attach_file').on('click', function(){
        errorSpan.before('<input type="file" class="form-control mt-2" name="File[]" />');
    });

    $("input[name=BTN]").on('keyup', function() {
        getBTNAccount($(this));
    });
    $("input[name=AccountNum]").on('keyup', function() {
        getBTNAccount($(this));
    });

    if($("#BTNAccountExists").length) {
        if ($("#BTNAccountExists").val() == '0') {
            $("[name='CategoryID']").val(null);
            $('#ShowCircuitForm').hide();
            $('#ShowBTNForm').show();
        } else if ($("#BTNAccountExists").val().length) {
            getAddress(window.name);
            $('#account-found').show();
            $('#ShowCircuitForm').show();
            $('#ShowBTNForm').hide();
        } else {
            $('#ShowCircuitForm').hide();
            $('#ShowBTNForm').show();
        }
    }

    function getBTNAccount(value){
        if (value.val().length > 3 && value.closest("form").attr('class') != 'order-edit-form') {
            var searchType = value.attr('id');
            $.ajax({
                url: '/api/collections/accounts',
                type: 'post',
                data: { 'accountID' : value.val(), 'type' : searchType},
                success: function (data) {
                    if(data.length){
                        window.name = data[0]['SiteAddressID'];
                        getAddress(data[0]['SiteAddressID']);
                        $('#account-found').show();
                        if(searchType == 'AccountNum'){
                            $('#BTN').val(data[0]['BTN']);
                        }else if (searchType == 'BTN'){
                            $('#AccountNum').val(data[0]['AccountNum'])
                        }
                        localStorage['OrderBTN'] = JSON.stringify(data[0]);
                        $('#BTNAccountExists').val(data[0]['BTNAccountID']);
                        $('#ShowCircuitForm').show();
                        $('#ShowBTNForm').hide();
                    }else{
                        $('#account-found').hide();
                        $('#BTNAccountExists').val('0');
                        $('#ShowCircuitForm').hide();
                        $("[name='CategoryID']").val(null);
                        $('#ShowBTNForm').show();
                        localStorage['OrderBTN'] = JSON.stringify(false);
                    }
                },
            });
        }
    }

    function getAddress(SiteAddressID){
        if(SiteAddressID) {
            $.ajax({
                url: '/api/collections/address',
                type: 'post',
                data: {'SiteAddressID': SiteAddressID},
                success: function (data) {
                    $('#ServiceAddressID').val(data['AddressID']);
                    $('#ServiceAddressIDSearch').val(data['Search']);
                    $('#ServiceAddressSiteName').val(data['SiteName']);

                    $('#SiteNameS').val(data['SiteName']);
                    $('#Address1S').val(data['Address1']);
                    $('#Address2S').val(data['Address2']);
                    $('#CityS').val(data['City']);
                    $('#StateS').val(data['State']);
                    $('#ZipS').val(data['Zip']);
                }
            });
        }
    }
});
