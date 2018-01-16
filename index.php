<?php
/**
 * Created by PhpStorm.
 * User: sachin
 * Date: 1/15/18
 * Time: 10:21 AM
 */
include_once '_header.php';

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

}
$rent_list=getRentList($conn);
?>
<script type="text/javascript">
    $(function () {

        $('#rent-list').DataTable();
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
    }
    function editRent(id){
        $.ajax({
            type: 'post',
            url: "db_connect.php",
            data: {edit_rent: 'edit_rent', id: id},
            dataType: 'json',
            success: function (data) {
                $('#id').val(data.id);
                $('#usr1').val(data.name);
                $('#year1').val(data.year);
                $('#month1').val(data.month);
                $('#rent1').val(data.rent);
                $('#current_electricity_unit1').val(data.current_electricity_unit);
                $('#previous_electricity_unit1').val(data.previous_electricity_unit);
                $('#water_cost1').val(data.water_cost);
                $('#myModal1').modal('show');
            }
        });
    }
</script>
<div class="container">
    <div class="row">
        <div class="col-md-offset-4 col-md-4">
            <h2>People Rents</h2>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" style="right: 0px">Add People Rent</button>

            <table id="rent-list" class="display">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Rent</th>
                    <th>Electricity</th>
                    <th>Water</th>
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
                            <td><?php echo $rents["name"] ?></td>
                            <td><?php echo $rents["year"] ?></td>
                            <td><?php echo $rents["month"] ?></td>
                            <td><?php echo $rents["rent"] ?></td>

                            <td>
                                <?php
                                $charge=getElectricityRate($conn);
                                $electricity_unit=($rents['current_electricity_unit']- $rents['previous_electricity_unit'])*$charge['rate'];
                                echo $electricity_unit;
                                ?>
                            </td>
                            <td><?php echo $rents["water_cost"] ?></td>
                            <td><?php
                                $total=$rents['rent']+$electricity_unit+$rents["water_cost"];
                                echo $total;
                                ?>
                            </td>
                            <td>
                                <?php
                                if($rents['status']==0){
                                    echo 'Unpaid';
                                }else{
                                    echo 'Paid';
                                }
                                ?>

                            </td>
                            <td>
                                <form>
                                    <button type="button" class="btn btn-primary" onclick="editRent(<?php echo $rents['id'] ?>)" >Edit</button>

                                </form>
                            </td>

                        </tr>
                        <?php
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">New People</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="index.php">
                        <div class="form-group">
                            <label for="usr">Name:</label>
                            <select id="usr" name="usr" onchange="fetch_rent()">
                                <option>Select People</option>
                                <?php
                                $users=getPeopleList($conn,0);
                                foreach ($users as $user) {
                                    ?>
                                    <option value="<?php echo $user['name'] ?>"><?php echo $user['name'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="year">Year:</label>
                            <select name="year" id="year">
                                <?php
                                for($i=2070;$i<=2150;$i++) {
                                    ?>
                                    <option value="<?php echo $i?>"><?php echo $i?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="month">Month:</label>
                            <select name="month" id="month">
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
                            <input type="number" name="rent" class="form-control" id="rent">
                        </div>
                        <div class="form-group">
                            <label for="previous_electricity_unit">Previous Electricity Unit:</label>
                            <input type="text" name="previous_electricity_unit" class="form-control" id="previous_electricity_unit">
                        </div>
                        <div class="form-group">
                            <label for="current_electricity_unit">Current Electricity Unit:</label>
                            <input type="text" name="current_electricity_unit" class="form-control" id="current_electricity_unit">
                        </div>
                        <div class="form-group">
                            <label for="water_cost">Water Cost:</label>
                            <input type="number" name="water_cost" class="form-control" id="water_cost">
                        </div>
                        <button class="btn btn-info" type="submit" name="new_rent">Add</button>
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
                    <h4 class="modal-title">New People</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="index.php">
                        <input  type="hidden" id="id" name="id">
                        <div class="form-group">
                            <label for="usr">Name:</label>
                            <select id="usr1" name="usr" onchange="fetch_rent()">
                                <option>Select People</option>
                                <?php
                                $users=getPeopleList($conn,0);
                                foreach ($users as $user) {
                                    ?>
                                    <option value="<?php echo $user['name'] ?>"><?php echo $user['name'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="year">Year:</label>
                            <select name="year" id="year1">
                                <?php
                                for($i=2070;$i<=2150;$i++) {
                                    ?>
                                    <option value="<?php echo $i?>"><?php echo $i?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="month">Month:</label>
                            <select name="month" id="month1">
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
                            <input type="number" name="rent" class="form-control" id="rent1">
                        </div>
                        <div class="form-group">
                            <label for="previous_electricity_unit">Previous Electricity Unit:</label>
                            <input type="text" name="previous_electricity_unit" class="form-control" id="previous_electricity_unit1">
                        </div>
                        <div class="form-group">
                            <label for="current_electricity_unit">Current Electricity Unit:</label>
                            <input type="text" name="current_electricity_unit" class="form-control" id="current_electricity_unit1">
                        </div>
                        <div class="form-group">
                            <label for="water_cost">Water Cost:</label>
                            <input type="number" name="water_cost" class="form-control" id="water_cost1">
                        </div>
                        <button class="btn btn-info" type="submit" name="update_rent">Update</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>