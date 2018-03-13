function notify(message, messageType){
    $.notify(message, messageType);
}

function edit_people(name, rent, rent_date, id, email){
    $("#edit_name").val(name);
    $("#edit_rent").val(rent);
    $("#edit_rent_date").val(rent_date);
    $("#edit_id").val(id);
    $("#edit_email").val(email);
    $("#edit_people").modal('show');
}
function edit_return(id, people_id, remain_to_give){
    $("#edit_id").val(id);
    $("#edit_name").val(people_id);
    $("#edit_remain_to_return").val(remain_to_give);

    $("#edit_return").modal('show');
}

function change_ebill(rate) {
    var punit = $("#previous_electricity_unit").val();
    var cunit = $("#current_electricity_unit").val();
    var price = (cunit-punit)*rate;
    $("#electricity_bill").val(price);
}

function change_ebill_edit(rate) {
    var punit = $("#previous_electricity_unit1").val();
    var cunit = $("#current_electricity_unit1").val();
    var price = (cunit-punit)*rate;
    $("#electricity_bill1").val(price);
}

function show_paid_modal(id, total_rent) {
    $("#paid_id").val(id);
    $("#paid_total_rent").val(total_rent);
    $("#paid-modal").modal('show');
}