<?php
/**
 * Created by PhpStorm.
 * User: sachin
 * Date: 1/15/18
 * Time: 10:21 AM
 */
include_once '_header.php';
$today = $calendar->englishToNepali(date('Y'), date('m'), date('d'));
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
}elseif(isset($_GET['paid'])){
    $id = $_GET['id'];
    if(update_status($conn, $id)){
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
        $("#year").val('<?php echo $nepali_year ?>');
        $("#month").val('<?php echo $nepali_month ?>');
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

                    column.data().unique().sort().each( function ( d, j ) {
                        select.append( '<option value="'+d+'">'+d+'</option>' )
                    } );
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
                $('#myModal1').modal('show');
                change_ebill_edit('<?php echo $unit_rate ?>');
            }
        });
    }
</script>
<div class="container">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
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
                    <th>Previous Remaining</th>
                    <th>Total</th>
                    <th>Status</th>
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
                        <td><?php echo $rents["previous_rent"] ?></td>
                        <td><?php
                            $total=$rents['rent']+$rents['electricity_bill']+$rents["water_cost"]+$rents["previous_rent"];
                            echo $total;
                            ?>
                        </td>
                        <td>
                            <?php
                            if($rents['status']==0){
                                echo '<span style="color: red">Unpaid</span>';
                            }else{
                                echo '<span style="color: green">Paid</span>';
                            }
                            ?>

                        </td>
                        <td>
                            <?php if($rents['status'] == 0){ ?>
                            <a href="#" type="button" class="btn-link" onclick="editRent(<?php echo $rents['id'] ?>)" >Edit</a><br>
                            <a class="btn-link" href="index.php?paid=true&id=<?php echo $rents['id'] ?>">Paid</a>
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
                                <option value="Baishakh">Baishakh</option>
                                <option value="Jestha">Jestha</option>
                                <option value="Asar">Asar</option>
                                <option value="Sharwan">Sharwan</option>
                                <option value="Bhadau">Bhadau</option>
                                <option value="Aswin">Aswin</option>
                                <option value="Kartik">Kartik</option>
                                <option value="Mangsir">Mangsir</option>
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
                            <input type="number" name="water_cost" class="form-control" id="water_cost">
                        </div>
                        <div class="form-group">
                            <label for="previous_rent">Previous Remaining:</label>
                            <input value="0" type="number" name="previous_rent" class="form-control" id="previous_rent">
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
                                <option value="Baishakh">Baishakh</option>
                                <option value="Jestha">Jestha</option>
                                <option value="Asar">Asar</option>
                                <option value="Sharwan">Sharwan</option>
                                <option value="Bhadau">Bhadau</option>
                                <option value="Aswin">Aswin</option>
                                <option value="Kartik">Kartik</option>
                                <option value="Mangsir">Mangsir</option>
                                <option value="Poush">Poush</option>
                                <option value="Magh">Magh</option>
                                <option value="Falgun">Falgun</option>
                                <option value="Chaitra">Chaitra</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="rent">Rent:</label>
                            <input readonly type="number" name="rent" class="form-control" id="rent1">
                        </div>
                        <div class="form-group">
                            <label for="previous_electricity_unit">Previous Electricity Unit:</label>
                            <input type="number" onkeyup="change_ebill_edit('<?php echo $unit_rate ?>')" name="previous_electricity_unit" class="form-control" id="previous_electricity_unit1">
                        </div>
                        <div class="form-group">
                            <label for="current_electricity_unit">Current Electricity Unit:</label>
                            <input type="number" onkeyup="change_ebill_edit('<?php echo $unit_rate ?>')" name="current_electricity_unit" class="form-control" id="current_electricity_unit1">
                        </div>
                        <div class="form-group">
                            <label for="electricity_bill">Electricity Bill:</label>
                            <input type="text" readonly name="electricity_bill" class="form-control" id="electricity_bill1">
                        </div>
                        <div class="form-group">
                            <label for="water_cost">Water Cost:</label>
                            <input type="number" name="water_cost" class="form-control" id="water_cost1">
                        </div>
                        <div class="form-group">
                            <label for="previous_rent1">Previous Remaining:</label>
                            <input type="number" name="previous_rent" class="form-control" id="previous_rent1">
                        </div>
                        <button class="btn btn-info" type="submit" name="update_rent">Update</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>