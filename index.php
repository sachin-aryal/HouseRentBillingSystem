<?php
/**
 * Created by PhpStorm.
 * User: sachin
 * Date: 1/15/18
 * Time: 10:21 AM
 */
include_once '_header.php';
$today = $calendar->eng_to_nep(date('Y'), date('m'), date('d'));
$nepali_year = $today["year"];
$nepali_month = $today["nmonth"];

$message = "";
$messageType = "";
if(isset($_POST["new_rent"])){
    if(insert_rent($conn)){
        $message = "New Rent Added Successfully.";
        $messageType = "success";
    }else{
        $message = "Error while adding new rent.";
        $messageType = "error";
    }

}elseif(isset($_POST["update_rent"])){
    if(update_rent($conn)){
        $message = " Rent Updated Successfully.";
        $messageType = "success";
    }else{
        $message = "Error while updating rent.";
        $messageType = "error";
    }
}elseif(isset($_POST['paid'])){
    $id = $_POST['id'];
    $total_rent = $_POST["total_rent"];
    $paid_money = $_POST["paid_money"];
    if(update_status($conn, $id)){
        $rent = get_rent($conn, $id);
        if($paid_money != $total_rent){
            $left_to_return = $paid_money - $total_rent;
            insert_return_f($conn, $rent["people_id"], $left_to_return);
        }
        $advance = get_advance($conn, $rent["people_id"]);
        if($advance != 0){
            $advance_remaining = $advance - $total_rent;
            echo $advance_remaining;
            update_advance_by_people($conn, $rent["people_id"], $advance_remaining);
        }
        $message = "Rent Status Updated Successfully.";
        $messageType = "success";
    }else{
        $message = "Error while updating rent status.";
        $messageType = "error";
    }

}elseif(isset($_GET['delete'])){
    $id = $_GET["id"];
    if(delete_rent($conn, $id)){
        $message = "Rent Deleted Successfully.";
        $messageType = "success";
    }else{
        $message = "Error while deleting rent.";
        $messageType = "error";
    }
}
$rent_list=getRentList($conn);
$unit_rate = get_electricity_price($conn);
?>
<script type="text/javascript">
    $(function () {
        $.fn.dataTable.Api.register( 'column().title()', function () {
            var colheader = this.header();
            return $(colheader).text().trim();
        } );
        var todayYear = '<?php echo $nepali_year ?>';
        var todayMonth = '<?php echo $nepali_month ?>';
        console.log(todayMonth);
        $("#year").val(todayYear);
        $("#month").val(todayMonth);

        var rent_list = $('#rent-list').DataTable({
            drawCallback: function () {
                var api = this.api();
                $( api.column( 0 ).footer() ).html('Total');
                $( api.column( 3 ).footer() ).html(
                    api.column( 3, {page:'current'} ).data().sum()
                );
                $( api.column( 4 ).footer() ).html(
                    api.column( 4, {page:'current'} ).data().sum()
                );
                $( api.column( 5 ).footer() ).html(
                    api.column( 5, {page:'current'} ).data().sum()
                );
                $( api.column( 5 ).footer() ).html(
                    api.column( 5, {page:'current'} ).data().sum()
                );
                $( api.column( 6 ).footer() ).html(
                    api.column( 6, {page:'current'} ).data().sum()
                );
                $( api.column( 7 ).footer() ).html(
                    api.column( 7, {page:'current'} ).data().sum()
                );
                $( api.column( 8 ).footer() ).html(
                    api.column( 8, {page:'current'} ).data().sum()
                );
                $( api.column( 9 ).footer() ).html(
                    api.column( 9, {page:'current'} ).data().sum()
                );
            },
            initComplete: function () {
                this.api().columns([1,2]).every( function () {
                    var column = this;
                    var select = $('<select><option value="">'+column.title()+'</option></select>')
                        .appendTo( $(column.header()).empty() )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search( val ? '^'+val+'$' : '', true, false )
                                .draw();
                        } );

                    var column_title = column.title();
                    column.data().unique().sort().each( function ( d, j ) {
                        var selected = false;
                        if(column_title === "Year" && d === todayYear){
                            selected = true;
                        }else if(column_title === "Month" && d === todayMonth){
                            selected = true;
                        }
                        if(selected){
                            select.append( '<option selected="selected" value="'+d+'">'+d+'</option>' )
                        }else{
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        }
                    } );
                    $(rent_list).ready(function() {
                        if(column_title === "Year"){
                            column.search( todayYear ? '^'+todayYear+'$' : '', true, false ).draw();
                        }
                    });
                } );
            }
        });
        notify("<?php echo $message ?>", "<?php echo $messageType ?>");
        var uri = window.location.toString();
        if (uri.indexOf("?") > 0) {
            var clean_uri = uri.substring(0, uri.indexOf("?"));
            window.history.replaceState({}, document.title, clean_uri);
        }
    });
    function fetch_rent(){
        var usr = $("#usr").val();
        $.ajax({
            type: 'post',
            url: "db_connect.php",
            data: {get_rent: 'get_rent', usr: usr},
            dataType: 'json',
            success: function (data) {
                $('#rent').val(data.rent);
            }
        });
        $.ajax({
            type: 'post',
            url: "db_connect.php",
            data: {get_previous_electricity: 'get_previous_electricity', usr: usr},
            dataType: 'json',
            success: function (data) {
                $('#previous_electricity_unit').val(data);
            }
        });
    }
    function fetch_rent_edit(){
        var usr = $("#usr1").val();
        $.ajax({
            type: 'post',
            url: "db_connect.php",
            data: {get_rent: 'get_rent', usr: usr},
            dataType: 'json',
            success: function (data) {
                $('#rent1').val(data.rent);
            }
        });
    }
    function editRent(id){
        $.ajax({
            type: 'post',
            url: "db_connect.php",
            data: {edit_rent: 'edit_rent', id: id},
            dataType: 'json',
            success: function (data) {
                $('#id').val(data.id);
                $('#usr1').val(data.people_id);
                $('#year1').val(data.year);
                $('#month1').val(data.month);
                $('#rent1').val(data.rent);
                $('#current_electricity_unit1').val(data.current_electricity_unit);
                $('#previous_electricity_unit1').val(data.previous_electricity_unit);
                $('#water_cost1').val(data.water_cost);
                $('#previous_rent1').val(data.previous_rent);
                $('#maintenance_cost1').val(data.maintenance_cost);
                $('#remarks1').val(data.remarks);
                $('#myModal1').modal('show');
                change_ebill_edit('<?php echo $unit_rate ?>');
            }
        });
    }
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12" style="margin-left: 130px;width: 90%;">
            <div id="one-row" style="margin-top: 10px">
                <h2 style="display: inline-block">Rent</h2>
                <button style="float: right" type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Add Rent</button>
            </div>
            <table id="rent-list" class="display">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Rent</th>
                    <th>Electricity</th>
                    <th>Water</th>
                    <th>Maintenance Cost</th>
                    <th>Previous Remaining</th>
                    <th>Paid</th>
                    <th>Total</th>
                    <th>Remarks</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($rent_list as $rents) {
                    ?>
                    <tr>
                        <td><a href="print.php?id=<?php echo $rents['id'] ?>"><?php echo $rents["name"] ?></a></td>
                        <td><?php echo $rents["year"] ?></td>
                        <td><?php echo $rents["month"] ?></td>
                        <td><?php echo $rents["rent"] ?></td>

                        <td>
                            <?php
                            echo $rents['electricity_bill'];
                            ?>
                        </td>
                        <td><?php echo $rents["water_cost"] ?></td>
                        <td><?php echo $rents["maintenance_cost"] ?></td>
                        <td><?php echo $rents["previous_rent"] ?></td>
                        <?php
                        $total=$rents['rent']+$rents['electricity_bill']+$rents["water_cost"]+$rents["previous_rent"]+$rents["maintenance_cost"];
                        if($rents['status']==0){
                            echo "<td style='color: red'>0</td>";
                        }else{
                            echo "<td style='color: green'>$total</td>";
                        }
                        ?>
                        <td><?php
                            echo $total;
                            ?>
                        </td>
                        <td><?php echo $rents["remarks"] ?></td>
                        <td>
                            <?php if($rents['status'] == 0){ ?>
                                <a href="#" type="button" class="btn-link" onclick="editRent(<?php echo $rents['id'] ?>)" >Edit</a><br>
                                <button style="margin-left: -8px;" type="button" onclick="show_paid_modal('<?php echo $rents['id'] ?>', '<?php echo $total ?>')" class="btn-link" data-toggle="modal">Paid</button>
                            <?php } ?>
                            <a class="btn-link" href="index.php?delete=true&id=<?php echo $rents['id'] ?>">Delete</a>
                        </td>

                    </tr>
                    <?php
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div id="paid-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Paid Amount</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" action="index.php">
                        <input type="hidden" name="id" id="paid_id" class="form-control"/>
                        <div class="form-group">
                            <label for="rent">Total Rent:</label>
                            <input type="text" readonly="readonly" name="total_rent" id="paid_total_rent" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label for="rent">Paid Amount:</label>
                            <input type="text" name="paid_money" id="paid_money" class="form-control"/>
                        </div>
                        <input type="submit" name="paid" class="btn btn-primary" value="Paid"/>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Rent</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="index.php">
                        <div class="form-group">
                            <label for="usr">Name:</label>
                            <select id="usr" name="usr" onchange="fetch_rent()" class="form-control">
                                <option>Select Tenant</option>
                                <?php
                                $users=getPeopleList($conn,1);
                                foreach ($users as $user) {
                                    ?>
                                    <option value="<?php echo $user['id'] ?>"><?php echo $user['name'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="year">Year:</label>
                            <select name="year" id="year" class="form-control">
                                <?php
                                for($i=2074;$i<=2274;$i++) {
                                    ?>
                                    <option value="<?php echo $i?>"><?php echo $i?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="month">Month:</label>
                            <select name="month" id="month" class="form-control">
                                <option value="Baishak">Baishak</option>
                                <option value="Jestha">Jestha</option>
                                <option value="Ashad">Ashad</option>
                                <option value="Shrawn">Shrawn</option>
                                <option value="Bhadra">Bhadra</option>
                                <option value="Ashwin">Ashwin</option>
                                <option value="Kartik">Kartik</option>
                                <option value="Mangshir">Mangshir</option>
                                <option value="Poush">Poush</option>
                                <option value="Magh">Magh</option>
                                <option value="Falgun">Falgun</option>
                                <option value="Chaitra">Chaitra</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="rent">Rent:</label>
                            <input readonly type="number" name="rent" class="form-control" id="rent">
                        </div>
                        <div class="form-group">
                            <label for="previous_electricity_unit">Previous Electricity Unit:</label>
                            <input type="number" onkeyup="change_ebill('<?php echo $unit_rate ?>')" value="0" name="previous_electricity_unit" class="form-control" id="previous_electricity_unit">
                        </div>
                        <div class="form-group">
                            <label for="current_electricity_unit">Current Electricity Unit:</label>
                            <input type="number" onkeyup="change_ebill('<?php echo $unit_rate ?>')" value="0" name="current_electricity_unit" class="form-control" id="current_electricity_unit">
                        </div>
                        <div class="form-group">
                            <label for="electricity_bill">Electricity Bill:</label>
                            <input type="text" readonly name="electricity_bill" class="form-control" id="electricity_bill">
                        </div>
                        <div class="form-group">
                            <label for="water_cost">Water Cost:</label>
                            <input type="number" value="0" name="water_cost" class="form-control" id="water_cost">
                        </div>
                        <div class="form-group">
                            <label for="maintenance_cost">Maintenance Cost:</label>
                            <input type="number" name="maintenance_cost" value="0" class="form-control" id="maintenance_cost">
                        </div>
                        <div class="form-group">
                            <label for="previous_rent">Previous Remaining:</label>
                            <input value="0" type="number" name="previous_rent" class="form-control" id="previous_rent">
                        </div>
                        <div class="form-group">
                            <label for="remarks">Remarks:</label>
                            <input type="text" name="remarks" class="form-control" id="remarks">
                        </div>
                        <button class="btn btn-info" type="submit" name="new_rent" value="new_rent">Add</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <div id="myModal1" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Update Rent</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="index.php">
                        <input  type="hidden" id="id" name="id">
                        <div class="form-group">
                            <label for="usr">Name:</label>
                            <select id="usr1" name="usr" onchange="fetch_rent_edit()" class="form-control">
                                <option>Select Tenant</option>
                                <?php
                                $users=getPeopleList($conn,1);
                                foreach ($users as $user) {
                                    ?>
                                    <option value="<?php echo $user['id'] ?>"><?php echo $user['name'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="year">Year:</label>
                            <select name="year" id="year1" class="form-control">
                                <?php
                                for($i=2074;$i<=2274;$i++) {
                                    ?>
                                    <option value="<?php echo $i?>"><?php echo $i?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="month">Month:</label>
                            <select name="month" id="month1" class="form-control">
                                <option value="Baishak">Baishak</option>
                                <option value="Jestha">Jestha</option>
                                <option value="Ashad">Ashad</option>
                                <option value="Shrawn">Shrawn</option>
                                <option value="Bhadra">Bhadra</option>
                                <option value="Ashwin">Ashwin</option>
                                <option value="Kartik">Kartik</option>
                                <option value="Mangshir">Mangshir</option>
                                <option value="Poush">Poush</option>
                                <option value="Magh">Magh</option>
                                <option value="Falgun">Falgun</option>
                                <option value="Chaitra">Chaitra</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="rent1">Rent:</label>
                            <input readonly type="number" name="rent" class="form-control" id="rent1">
                        </div>
                        <div class="form-group">
                            <label for="previous_electricity_unit1">Previous Electricity Unit:</label>
                            <input type="number" onkeyup="change_ebill_edit('<?php echo $unit_rate ?>')" name="previous_electricity_unit" class="form-control" id="previous_electricity_unit1">
                        </div>
                        <div class="form-group">
                            <label for="current_electricity_unit1">Current Electricity Unit:</label>
                            <input type="number" onkeyup="change_ebill_edit('<?php echo $unit_rate ?>')" name="current_electricity_unit" class="form-control" id="current_electricity_unit1">
                        </div>
                        <div class="form-group">
                            <label for="electricity_bill1">Electricity Bill:</label>
                            <input type="text" readonly name="electricity_bill" class="form-control" id="electricity_bill1">
                        </div>
                        <div class="form-group">
                            <label for="water_cost1">Water Cost:</label>
                            <input type="number" value="0" name="water_cost" class="form-control" id="water_cost1">
                        </div>
                        <div class="form-group">
                            <label for="maintenance_cost1">Maintenance Cost:</label>
                            <input type="number" name="maintenance_cost" value="0" class="form-control" id="maintenance_cost1">
                        </div>
                        <div class="form-group">
                            <label for="previous_rent1">Previous Remaining:</label>
                            <input type="number" name="previous_rent" class="form-control" id="previous_rent1">
                        </div>
                        <div class="form-group">
                            <label for="remarks1">Remarks:</label>
                            <input type="text" name="remarks" class="form-control" id="remarks1">
                        </div>
                        <button class="btn btn-info" type="submit" name="update_rent">Update</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>