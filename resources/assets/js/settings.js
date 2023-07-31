$(document).ready(function () {
    var settingsPage = $(".main-container.settings");
    if (settingsPage.length) {
        var settingsModal = $(".settings-modal");
        if (settingsModal.length) {
            settingsModal.on("show.bs.modal", function (event) {
                var button = $(event.relatedTarget);
                var url = button.data("url");
                var title = button.data("title");
                var modal = $(this);
                modal.find(".modal-title").text(title);

                $.get(url, function (html) {
                    modal.find(".modal-body").html(html);
                });
            });

            var saveBtn = settingsModal.find(".save");

            settingsModal.on("submit", "form", function () {
                var form = $(this);
                removeErrors(form);

                var formData = form.serialize();

                $.post(form.attr("action"), formData, function (response) {
                    location.reload();
                }).fail(function (errors) {
                    // errors.responseJSON.errors repres error object
                    addErrors(errors.responseJSON.errors, form);
                });

                return false;
            });

            saveBtn.click(function () {
                $("form", settingsModal).submit();
            });

            var addErrors = function (errors, form) {
                for (var property in errors) {
                    var input = $('[name="' + property + '"]');
                    if (input) {
                        var formGroup = input.parent();
                        formGroup.addClass("has-danger");
                        formGroup.append(
                            '<div class="form-control-feedback">' +
                                errors[property] +
                                "</div>"
                        );
                    }
                }
            };

            var removeErrors = function (form) {
                $(".form-group", form).removeClass("has-danger");
                $(".form-control-feedback", form).remove();
            };
        }

        $(".service-type-category").change(function () {
            $(this).parent("form").submit();
        });

        $(".feature-category").change(function () {
            $(this).parent("form").submit();
        });

        if ($(".ftp-folders").length) {
            var uploadButton = $(".btn-upload");
            var tableRows = $(".table-clickable tbody tr");
            var selectedRow;

            tableRows.click(function () {
                tableRows.removeClass("table-active");
                selectedRow = $(this);
                selectedRow.addClass("table-active");

                if (selectedRow.data("can-change-status")) {
                    uploadButton.removeClass("disabled");
                } else {
                    uploadButton.addClass("disabled");
                }
            });

            uploadButton.click(function () {
                if (
                    !uploadButton.hasClass("disabled") &&
                    selectedRow.data("can-change-status")
                ) {
                    selectedRow.find("form").submit();
                }
            });
        }

        if ($(".favorite-reports").length && !$(".not-found").length) {
            var table = $("tbody");
            function applyOrder(updateServer) {
                var i = 1;
                var orderList = [];

                $("tr td:first-child", table).each(function () {
                    var cell = $(this);
                    cell.html(i);
                    orderList.push({ id: cell.data("id"), order: i });
                    i++;
                });
                if (updateServer) {
                    var token = $('[name="csrf-token"]').attr("content");
                    $.post(table.data("order-url"), {
                        orderList: orderList,
                        _token: token,
                    });
                }
            }

            table.sortable({
                stop: function () {
                    applyOrder(true);
                },
            });

            applyOrder();
        }
    }
});
