$(document).ready(function() {
    $('#test').DataTable();
} );
$.fn.displayErrorModal = function(err) {
    $('#footer_action_button').text("OK");
    $('#footer_action_button').removeClass('glyphicon-check');
    $('#footer_action_button').removeClass('glyphicon-trash');
    $('.actionBtn').removeClass('btn-danger');
    $('.actionBtn').addClass('btn-success');
    $('.actionBtn').removeClass('edit');
    $('.actionBtn').removeClass('create');
    $('.actionBtn').removeClass('delete');
    $('.actionBtn').addClass('error');
    $('.modal-title').text('Error');
    $('.deleteContent').hide();
    $('.errorContent').show();
    $('.form-horizontal').hide();

    var data='';
    var response = JSON.parse(err.responseText);

    if(response.message){
        data = response.message;
    }else{
        $.each( response.errors, function( key, value ) {
            if($.isArray( value)){
                data += '*' +  value[0] + '<br/>';
            }else{
                data += '*' +  value + '<br/>';
            }
        });
    }
    $('.recorddata').html(data);
    $('#myModal').modal('show');
}
$(document).on('click', '.create-modal', function() {

    $('#footer_action_button').addClass('glyphicon-check');
    $('#footer_action_button').removeClass('glyphicon-trash');
    $('.actionBtn').addClass('btn-success');
    $('.actionBtn').removeClass('btn-danger');
    $('.actionBtn').removeClass('delete');
    $('.actionBtn').addClass('create');

    $('#footer_action_button').text("Create");
    $('.modal-title').text('Create');
    $('.deleteContent').hide();
    $('.errorContent').hide();
    $('.form-horizontal').show();


    $('#myModal').modal('show');
});
$(document).on('click', '.create', function() {
    var data={};
    $.each(defaultColumnData, function( key, value ) {
        data[value.dbName] = $("#"+value.dbName).val();
    })

    $.ajax({
        headers: {
            "Authorization": `Bearer ${defaultToken}`,
        },
        method: 'post',
        contentType: "text/json",
        data: JSON.stringify(data),
        url: `/api/${currentTable}`,
        success: function() {
            location.reload();
        },
        error: function (err) {
            $(document).displayErrorModal(err);
        }
    })
})
$(document).on('click', '.edit-modal', function() {

    $('#footer_action_button').addClass('glyphicon-check');
    $('#footer_action_button').removeClass('glyphicon-trash');
    $('.actionBtn').addClass('btn-success');
    $('.actionBtn').removeClass('btn-danger');
    $('.actionBtn').removeClass('delete');
    $('.actionBtn').removeClass('create');
    $('.actionBtn').addClass('edit');

    $('#footer_action_button').text("Edit");
    $('.modal-title').text('Edit');
    $('.deleteContent').hide();
    $('.errorContent').hide();
    $('.form-horizontal').show();

    var record = $(this).data('info');
    $('.actionBtn').data('info',record);
    $.each( record, function( key, value ) {
        if($('#'+key).attr("type")==='date'){
            $('#'+key).val(value.substr(0, 10));
        }else{
            $('#'+key).val(value);
        }
        if($('#'+key).attr("type")==='checkbox'){
            if(value===1){
                $('#'+key).prop('checked','checked');
            }else{
                $(this).removeAttr('checked');
            }

        }
    })
    $('#myModal').modal('show');
});

$(document).on('click', '.edit', function() {
    var id = $(this).data('info')['id']
    var uuid = $(this).data('info')['uuid']
    var data = {};

    $.each( $(this).data('info'), function( key, value ) {
        data[key] = $("#"+key).val();
    })
    data['id'] = id;
    data['uuid'] = uuid;

    $.ajax({
        headers: {
            "Authorization": `Bearer ${defaultToken}`,
        },
        method: 'put',
        contentType: "text/json",
        data: JSON.stringify(data),
        url: `/api/${currentTable}/${id}`,
        success: function() {
            location.reload();
        },
        error: function (err) {
            $(document).displayErrorModal(err);
        }
    })
})
$(document).on('click', '.delete-modal', function() {
    $('#footer_action_button').text(" Delete");
    $('#footer_action_button').removeClass('glyphicon-check');
    $('#footer_action_button').addClass('glyphicon-trash');
    $('.actionBtn').removeClass('btn-success');
    $('.actionBtn').addClass('btn-danger');
    $('.actionBtn').removeClass('edit');
    $('.actionBtn').addClass('delete');
    $('.modal-title').text('Delete');
    $('.deleteContent').show();
    $('.form-horizontal').hide();
    var record = $(this).data('info');
    $('.actionBtn').data('info',record);
    var data='';
    $.each( record, function( key, value ) {
        data += key + ': ' + value + '<br/>';
    });
    $('.recorddata').html(data);
    $('#myModal').modal('show');
});

$(document).on('click', '.delete', function() {
    var id = $(this).data('info').id;
    $.ajax({
        headers: {
            "Authorization": `Bearer ${defaultToken}`,
        },
        method: 'delete',
        contentType: "text/json",
        url: `/api/${currentTable}/${id}`,
        success: function() {
            location.reload();
        },
        error: function (err) {
            $(document).displayErrorModal(err);
        }
    });
});

