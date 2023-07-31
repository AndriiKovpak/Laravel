$(function () {
    // $('#circuitSearch').usPhoneFormat({
    //     format: '(xxx) xxx-xxxx'
    // });
    let inventoryNoteHistoryVisable = false;
    $('#DID').usPhoneFormat({
        format: 'xxx-xxx-xxxx'
    });


    $('.DID-search').usPhoneFormat({
        format: 'xxx-xxx-xxxx'
    });

    // $('.phone-format-required').usPhoneFormat({
    //     format: '(xxx) xxx-xxxx'
    // });

    $('.toggleInventoryNoteHistory').click(function() {
        inventoryNoteHistoryVisable = !inventoryNoteHistoryVisable
        if(inventoryNoteHistoryVisable) {
            $('.inventoryNoteHistory').show();
        } else {
            $('.inventoryNoteHistory').hide();
        }
    });

    $('#search_btn_users').click(function() {
        $('#formUsersSearch').submit();
    })

});
