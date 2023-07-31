<div class="modal fade send-mail" id="reportsDownloadModal" tabindex="-1" role="dialog" aria-labelledby="reportsDownloadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <a href="#" class="close-btn pull-right" data-dismiss="modal" aria-label="Close">&times;</a>
        <div class="clearfix"></div>
        <div class="modal-header text-xs-left">DOWNLOAD REPORT</div>
        <div class="modal-content">
            <div class="row">
                <div class="col-md-12">
                    <form class="col-md-12 download_report_range_form" method="get">

                        <p class="text-muted mt-1">Select the period of time you need for report.</p>

                        <div class="form-group">
                            <label class="control-label" for="from">From</label>
                            <input type="date" class="form-control" name="from" id="from" value="<?php echo date('Y-m-d',strtotime('-1 month')); ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="to">To</label>
                            <input type="date" class="form-control" name="to" id="to" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn-primary pull-right downloadReportDateRange">DOWNLOAD</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- end modal-content -->
    </div>
</div>

<div class="modal fade send-mail" id="reportsEmailModal" tabindex="-1" role="dialog" aria-labelledby="reportsEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" style="max-width: 350px;" role="document">
        <a href="#" class="close-btn pull-right" data-dismiss="modal" aria-label="Close">&times;</a>
        <div class="clearfix"></div>
        <div class="modal-header text-xs-left">SEND BY EMAIL</div>
        <div class="modal-content">
            <div class="row">
                <div class="col-md-12">
                    <form method="get" class="download_report_range_form">

                        <input type="hidden" name="send" value="1">

                        <div class="col-md-12 reportsEmailModalDateRange">

                            <p class="text-muted mt-1">Select the period of time you need for report.</p>

                            <div class="form-group">
                                <label class="control-label" for="from">From</label>
                                <input type="date" class="form-control" name="from" id="from" value="<?php echo date('Y-m-d',strtotime('-1 month')); ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="to">To</label>
                                <input type="date" class="form-control" name="to" id="to" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>

                        <div class="email-form">

                            <div class="col-lg-12">
                                <div class="radio mt-3">
                                    <label><input type="radio" value="default" checked name="destination">Send the report to my default email address</label>
                                </div>

                                <div class="radio mt-4">
                                    <label><input type="radio" value="other" name="destination">Send the report to different(s) email(s) account(s)</label>
                                </div>

                                <div class="form-group mt-2">
                                    <label class="control-label" for="Email[]">Email</label>
                                    <input type="email" class="form-control" name="Email[]" id="Email[]">
                                </div>

                                <a href="javascript:void(0);" class="default addMoreEmail">Add another email account</a>
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="form-group col-lg-12 mt-3">
                            <button type="submit" class="btn-primary pull-right downloadReportDateRange">SEND</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- end modal-content -->
    </div>
</div>