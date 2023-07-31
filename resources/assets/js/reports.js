$(document).ready(function() {

    $("a[data-report-url]").click(function() {

        $($(this).attr('href') + " form").attr('action', $(this).attr('data-report-url'));

        if ($(this).attr('data-report-has-date-range') == 1) {
            $("#reportsEmailModal div.reportsEmailModalDateRange").show();
            $("#reportsEmailModal div.reportsEmailModalDateRange input").attr('required', true);
        } else {
            $("#reportsEmailModal div.reportsEmailModalDateRange").hide();
            $("#reportsEmailModal div.reportsEmailModalDateRange input").attr('required', false);

        }
    });

    $("a.addMoreEmail").click(function() {
        $html = '<div class="form-group removeDiv"><input name="Email[]" type="email" class="form-control" required><i class="fa fa-close removeInput"></i></div>';
        $(this).before($html);
    });

    $("i.removeInput").click(function() {

        jQuery(this).parents(".removeDiv").remove();
    });

    var setCookie = function(name, value, expiracy) {
        var exdate = new Date();
        exdate.setTime(exdate.getTime() + expiracy * 1000);
        var c_value = escape(value) + ((expiracy == null) ? "" : "; expires=" + exdate.toUTCString());
        document.cookie = name + "=" + c_value + '; path=/';
    };

    var getCookie = function(name) {
        var i, x, y, ARRcookies = document.cookie.split(";");
        for (i = 0; i < ARRcookies.length; i++) {
            x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
            y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
            x = x.replace(/^\s+|\s+$/g, "");
            if (x == name) {
                return y ? decodeURI(unescape(y.replace(/\+/g, ' '))) : y; //;//unescape(decodeURI(y));
            }
        }
    };

    $('.download_report').on('click', function(){
        $('.se-pre-con h2').html('Building your report');
        $('.se-pre-con').show();
        setCookie('downloadStarted', 0, 100);
        setTimeout(checkDownloadCookie, 1000); //Initiate the loop to check the cookie.
    });

    $('.download_report_range_form').on('submit', function(){
        $('.se-pre-con h2').html('Building your report');
        $('.se-pre-con').show();
        setCookie('downloadStarted', 0, 100);
        setTimeout(checkDownloadCookie, 1000); //Initiate the loop to check the cookie.
    });

    $('#scan_pending_invoices').on('click', function(){
        $('.se-pre-con h2').html('Scanning pending Invoices');
        $('.se-pre-con').show();
        setCookie('downloadStarted', 0, 100);
        setTimeout(checkDownloadCookie, 1000); //Initiate the loop to check the cookie.
    });

    $('#submit_file_upload').on('click', function(){
        $('.se-pre-con h2').html('Processing File Upload');
        $('.se-pre-con').show();
        setCookie('downloadStarted', 0, 100);
        setTimeout(checkDownloadCookie, 1000); //Initiate the loop to check the cookie.
    });

    var downloadTimeout;
    var checkCount = 0;
    var checkDownloadCookie = function() {
        checkCount++;
        if (getCookie("downloadStarted") == 1) {
            setCookie("downloadStarted", "false", 100);
            $('.se-pre-con h2').html('Your report has been downloaded!'); //(Seconds:' + checkCount + ')');
            $('.se-pre-con').delay(2200).hide(0);
            location.reload();
        } else {
            downloadTimeout = setTimeout(checkDownloadCookie, 1000); //Re-run this function in 1 second.
        }
    };

});