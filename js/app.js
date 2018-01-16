function notify(message, messageType){
    $.notify(message, messageType);
}

function edit_people(name, rent, rent_date, id){
    $("#edit_name").val(name);
    $("#edit_rent").val(rent);
    $("#edit_rent_date").val(rent_date);
    $("#edit_id").val(id);
    $("#edit_people").modal('show');
}