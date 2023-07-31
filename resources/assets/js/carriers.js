/**
 * Created by bcooper on 2/16/2017.
 */
$(document).ready(function() {


    var carrierListDiv = $('#list_carriers');
    var showCarrierList = $('#show_carrier_list');
    var hideCarrierList = $('#hide_carrier_list');
    var formCarrierSearch = $('#formCarrierSearch');
    var searchButton = $('#search_btn_carriers');
    var addContact = $('.Add_New_Carrier_Contact_Button');
    var contactDiv = $('.NewCarrierContactTemplate');
    var newContactDiv = $('.NewCarrierContact');
    var carrierForm = $('#createCarrierForm');
    var addNote = $('.add_Note a');
    var noteInput = $('.noteInput');
    var noteInput2 = $('.noteInput2');
    var noteInputTemplate = $('.noteInputTemplate');
    var numberOfContacts = $('#numberOfContacts');

    //-----Shows/Hides the Alphabet list for searching carriers-----------------
    if(window.name == 'show'){
        carrierListDiv.show();
    }

    showCarrierList.on('click', (function(){
        window.name = 'show';
        carrierListDiv.show();
    }));

    formCarrierSearch.on('submit',(function(ev){
        window.name = '';
    }));

    hideCarrierList.on('click', (function(){
        localStorage.setItem("showList", JSON.stringify('showList'));
        window.name = '';
        carrierListDiv.hide();
    }));
    //------------------------------------------------------------------------

    searchButton.on('click', (function(){
        formCarrierSearch.submit();
        window.name = '';
    }));

    //-----Add Contact for New Carrier-------------------------------------
    addContact.on('click', (function(){
        var cloned = contactDiv.clone();
        cloned.show();
        cloned.find('input').each(function(){
            $(this).val('');
        });
        cloned.appendTo(newContactDiv);
        if(isNaN(numberOfContacts.val()) || numberOfContacts.val() == ''){
            numberOfContacts.val(1);
            console.log(numberOfContacts.val());
        }else{
            numberOfContacts.val(parseInt(numberOfContacts.val()) + 1);
            console.log(numberOfContacts.val());
        }
    }));


    //Create Carrier form submit
    carrierForm.on('submit', (function(){
    }));

    //delete contact
    $('.container').on('click', '.delete_contact', (function(){
        var deleteButton = $(this).parent().parent().parent().parent();
        $(deleteButton).find('input').val('');
        console.log(deleteButton);
        deleteButton.remove();
    }));

    //Add notes section of create Carriers
    addNote.on('click', (function(){
        var cloned = noteInputTemplate.clone().css('display', 'block');
        $(cloned).find('input').val('');
        cloned.appendTo(noteInput2);
    }));

    //Delete Note
    $('body').on('click', '.delete_note', (function(){
        var deleteButton = $(this).parent().parent();
        var input = deleteButton.prev();
        $(input).find('textarea').val('');
        input.hide();
        deleteButton.hide();
    }));

});