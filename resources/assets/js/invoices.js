$(document).ready(function() {

    var invoices = $('.main-container.invoices');
    if (invoices.length) {
        var $filterInput = $('form [name="filter"]');
        var filterVisible = $filterInput.val() == 'show';

        if (filterVisible) {
            $filterInput.parent().show();
        } else {
            $filterInput.parent().hide();
        }

        $('.toggle-filter').click(function () {
            if (filterVisible) {
                $filterInput.parent().hide();
                $filterInput.val('hide');
                filterVisible = false;
            }  else {
                $filterInput.parent().show();
                $filterInput.val('show');
                filterVisible = true;
            }

            return false;
        });

        var $searchInput = $('form [name="search"]');
        var searchVisible = $searchInput.val() == 'show';
        var $searchArea = $('form .search-input');

        if (searchVisible) {
            $searchArea.show();
        } else {
            $searchArea.hide();
        }

        $('.toggle-search').click(function () {
            if (searchVisible) {
                $searchArea.hide();
                $searchInput.val('hide');
                searchVisible = false;
            }  else {
                $searchArea.show();
                $searchInput.val('show');
                searchVisible = true;
            }

            return false;
        });

        $('.invoice-type').change(function () {
            var invoiceTypes = $(this);
            window.location.href = invoiceTypes.data('url-' + invoiceTypes.val());
        });

        $('.toggle-date-range').click(function () {
            if ($(this).val() == 1) {
                $(this).val(0);
                $('#date_range').show();
            } else {
                $(this).val(1);
                $('#date_range').hide();
            }
        });

        $('.toggle-batch-date').click(function () {
            if ($(this).val() == 1) {
                $(this).val(0);
                $('#batch_date').show();
            } else {
                $(this).val(1);
                $('#batch_date').hide();
            }
        });
    }

    var renamePendingInvoiceBTN = $('.editPendingInvoice');
    renamePendingInvoiceBTN.each(renamePendingInvoice);

    function renamePendingInvoice(){
        $(this).on('click',function(){
            $("input[name='FileName']").val($(this).parent().parent().children().html());
            $("input[name='OldFileName']").val($(this).parent().parent().children().html());
        });
    }

    $('#save-file-name').on('click', function(){

        $("form[name='edit-pending-form']").submit();
    });

    $('#invoice-no').on('click', function(){
        event.preventDefault();
        $('#amount-alert-message').hide();
    })

});


function saveInvoice(formName){
    var Charge  = parseInt($('#CurrentChargeAmount').val());
    var PastDue = parseInt($('#PastDueAmount').val());
    var Credit  = parseInt($('#CreditAmount').val());
    if(validNumber(Charge) || validNumber(PastDue) || validNumber(Credit)){
        document.getElementById(formName).submit();
    }else{
        $('#amount-alert-message').show();
    }
}

function validNumber(value){
    return !(value === 0 || isNaN(value));
}
