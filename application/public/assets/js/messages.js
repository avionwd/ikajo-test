$(function() {
    var $tbl = $('#messages');

    function getSelectedRow(table)
    {
        var data = table.rows({selected: true}).data();
        var row = ('0' in data) ? data[0] : null;
        if (!row) {
            alert('Select one row to continue');
        }
        return row;
    }

    function initAjaxForm(element, table) {
        element.ajaxForm({
            target: '#message_form_container',
            success: function (response, statusText, xhr, $form) {
                $('#message_modal').modal('hide');
                table.ajax.reload();
            },
            error: function(xhr, statusText, thrownError, $form) {
                $('#message_form_container').html(xhr.responseText);
                initAjaxForm($('#message_form_container form'), table);
            }
        });
    }

    function initModal(table)
    {
        $('#message_modal').modal('show');
        initAjaxForm($('#message_modal').find('form#message_form'), table);
    }


    var columns = [];
    $tbl.find('thead tr:first th').each(function(el) {
        columns.push($(this).data());
    });

    $tbl.DataTable( {
        dom: "Bfrtip",
        lengthChange: false,
        processing: true,
        serverSide: true,
        ajax: $tbl.data('source'),
        select: true,
        columns: columns,
        order: [[0, 'desc']],
        buttons: [
            {
                text: "New",
                action: function (e, dt, node, config) {
                    $('#message_form_container').load('/message/create', function () {
                        initModal(dt);
                    });
                }
            },
            {
                text: "Edit",
                action: function (e, dt, node, config) {
                    var row = getSelectedRow(dt);
                    if (!row) {
                        return;
                    }
                    $('#message_form_container').load('/message/update/' + row.id, function () {
                        initModal(dt);
                    });
                }
            },
            {
                text: "Delete",
                action: function (e, dt, node, config) {
                    var row = getSelectedRow(dt);
                    if (!row || !confirm('Delete selected row?')) {
                        return;
                    }
                    $.ajax({
                        type: "POST",
                        url: "/message/delete/" + row.id,
                        success: function (resp) {
                            // TODO Show message response
                            dt.ajax.reload();
                        },
                        error: function (xhr, status, errorThrown) {
                            if (status == 'error') {
                                var data = xhr.responseJSON;
                                if ('error' in data) {
                                    alert('Error: ' + data.error.message);
                                }
                            }
                        }
                    });
                }
            }
        ]
    } );
} );