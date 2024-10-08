/**
* Digit Software Solutions.
*
* NOTICE OF LICENSE
*
* This source file is subject to the EULA
* that is bundled with this package in the file LICENSE.txt.
*
* @category  Dss
* @package   Dss_RefundRequest
* @author    Extension Team
* @copyright Copyright (c) 2024 Digit Software Solutions. ( https://digitsoftsol.com )
*/
define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/mage'
], function($, modal) {
    return function(config) {
        var url = config.dssUrl + 'refundrequest/order/index',
            title = config.dssPopupTitle,
            data = config.dataOrder,
            orderRefund = config.orderRefund
        orders = config.orders;
        var options = {
            wrapperClass: 'dss-modals-wrapper',
            modalClass: 'dss-modal',
            overlayClass: 'dss-modals-overlay',
            responsiveClass: 'dss-modal-slide',
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: title,
            buttons: [{
                text: $.mage.__('Send Request'),
                class: 'dss-popup-button',
                click: function (data) {
                    var form_data = $("#dss-refund-form").serialize();
                    if ($('#dss-refund-form').valid()) {

                        $.ajax({
                            showLoader: true,
                            url: url,
                            type: 'POST',
                            data: form_data
                        })
                            .done(function () {
                                $("#dss-refund-modal").modal('closeModal');
                                $("#dss-refund-form")[0].reset();
                                location.reload(true);

                            })
                            .fail(function () {
                                $("#dss-refund-modal").modal('closeModal');
                            });
                    }
                }
            },
                {
                    text: $.mage.__('Cancel Request'),
                    class: 'dss-popup-button',
                    click: function (data) {
                        $("#dss-refund-form")[0].reset();
                        $("#dss-refund-modal").modal('closeModal');

                    }
                }
            ],
            opened: function() {
                $('.dss-modal').css('background-color', '#0000008a');
            }
        };

        $("#my-orders-table tbody tr").each(function() {
            var pos = $(this).closest("tr");
            var col1 = pos.find("td:eq(0)").text();
            col1 = $.trim(col1);
            var array = orderRefund.split(",");
            var status = "";
            $(this).attr("data-oder-id", col1);

            /* Add refund button to each Order Row */
            $.each(orders, function(key, value) {
                if (value.increment_id == col1) {
                    status = value.status;
                    return;
                }
            });
            if ($.inArray(status, array) !== -1) {
                $(this).find('.col.actions').append("<span class='refund'><a href='#' class='refund-order'>"+$.mage.__('Refund')+"</a></span>");
            }

            var buttonPos = "tr[data-oder-id="+col1+"] td.col.actions";
            $.each(data, function(key, value) {
                var classRefund = buttonPos + ' ' + 'span.refund';
                if (col1 == value.increment_id && value.refund_status == 0) {
                    $(classRefund).html($.mage.__('Pending'));
                }
                if (col1 == value.increment_id && value.refund_status == 1) {
                    $(classRefund).html($.mage.__('Accepted'));
                }
                if (col1 == value.increment_id && value.refund_status == 2) {
                    $(classRefund).html($.mage.__('Rejected'));
                }
            });
        });

        $(document).on('click', '.refund-order', function () {
            var test = $(this).closest("tr");
            var col1=test.find("td:eq(0)").text();
            col1 = col1.replace(/\s/g, '');
            $(this).attr("data-oder-id", col1);
        });
        $(document).on('click', '.refund-order', function () {
            var order_id = $(this).attr('data-oder-id');
            modal(options, $("#dss-refund-modal"));
            $("#dss-refund-modal").modal('openModal');
            $(".dss-refund-oder-id").attr('value', order_id);
        });
    }
});