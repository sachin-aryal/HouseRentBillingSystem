<?php
/**
 * Created by PhpStorm.
 * User: sachin
 * Date: 1/15/18
 * Time: 10:34 AM
 */
include_once '_header.php';
$today = $calendar->eng_to_nep(date('Y'), date('m'), date('d'));
$nepali_year = $today["year"];
$nepali_month = $today["month"];
$nepali_day = $today["date"];

$message = "";
$messageType = "";
if(isset($_POST["new_people"])){
    if(insert_people($conn)){
        $message = "New People Added Successfully.";
        $messageType = "success";
    }else{
        $message = "Error while adding new people.";
        $messageType = "error";
    }

}else if (isset($_GET["disable"])){
    $id = $_GET["id"];
    if(disable_people($conn, $id)){
        $message = "People Disabled Successfully.";
        $messageType = "success";
    }else{
        $message = "Error while disabling people.";
        $messageType = "error";
    }

}else if (isset($_POST["edit_people"])){
    if(update_people($conn)){
        $message = "People Updated Successfully.";
        $messageType = "success";
    }else{
        $message = "Error while updating people.";
        $messageType = "error";
    }
}
$peoples = getPeople($conn);
?>
<script type="text/javascript">
    $(function () {
        $('#people-list').DataTable({
            "order": [[ 3, "desc" ]]
        });
        notify("<?php echo $message ?>", "<?php echo $messageType ?>");
        var uri = window.location.toString();
        if (uri.indexOf("?") > 0) {
            var clean_uri = uri.substring(0, uri.indexOf("?"));
            window.history.replaceState({}, document.title, clean_uri);
        }
    })
</script>
<div class="container">
    <div class="row">
        <div class="col-md-offset-2 col-md-10">
            <div id="one-row" style="margin-top: 10px">
                <h2 style="display: inline-block">Tenant</h2>
                <button style="float: right" type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-people">Add New Tenant</button>
            </div>
            <table id="people-list" class="display">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Rent</th>
                    <th>Contract Start Date</th>
                    <th>Contract End Date</th>
                    <th>Contract Remaining</th>
                    <th>Live Here</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($peoples as $people){
                    ?>
                <tr>
                    <td><?php echo $people["name"] ?></td>
                    <td><?php echo $people["email"] ?></td>
                    <td><?php echo $people["rent"] ?></td>
                    <td><?php echo $people["rent_date"] ?></td>
                    <td>
                        <?php
                        $rent_date = strtotime($people["rent_date"]);
                        $release_date = strtotime(date("Y/m/d", $rent_date) . " +1 year");
                        $formatted_release_date = date("Y/m/d", $release_date);
                        echo $formatted_release_date;
                        ?>
                    </td>
                    <td>
                        <?php
                        $today_str = $nepali_year.'/'.$nepali_month.'/'.$nepali_day;
                        $ts1 = strtotime($today_str);
                        $ts2 = $release_date;
                        $month1 = date('m', $ts1);
                        $month2 = date('m', $ts2);
                        $year1 = date('Y', $ts1);
                        $year2 = date('Y', $ts2);
                        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
                        if($diff > 1){
                            echo '<span style="color: green">'.$diff.' Month</span>';
                        }else{
                            echo '<span style="color: red">'.$diff.' Month</span>';
                        }
                        ?>
                    </td>
                    <td><?php echo ($people["enabled"]==1?'Yes': 'No') ?></td>
                    <td>
                        <?php
                        if($people["enabled"]==1) {
                            ?>
                            <a href="people.php?disable=yes&id=<?php echo $people['id'] ?>">Disable</a>
                            <a href="#" onclick="edit_people('<?php echo $people["name"] ?>', '<?php echo $people["rent"] ?>',
                                '<?php echo $people["rent_date"] ?>', '<?php echo $people["id"] ?>', '<?php echo $people["email"] ?>')">/ Edit
                            </a>
                            <?php
                        }else{
                            echo "";
                        }
                        ?>
                    </td>
                </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="add-people" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">New Tenant</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="people.php">
                        <div class="form-group">
                            <label for="usr">Name:</label>
                            <input type="text" name="people_name" class="form-control" id="usr">
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" class="form-control" id="email">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Rent:</label>
                            <input type="number" name="rent" class="form-control" id="pwd">
                        </div>
                        <div class="form-group">
                            <label for="rent-date">Rent Start Date:</label>
                            <input type="text" placeholder="2074/10/16" name="rent_date" class="form-control" id="rent-date">
                        </div>
                        <button class="btn btn-info" type="submit" name="new_people" value="new">Add</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <div id="edit_people" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Tenant</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="people.php">
                        <div class="form-group">
                            <label for="usr">Name:</label>
                            <input type="text" name="people_name" class="form-control" id="edit_name">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Rent:</label>
                            <input type="number" name="rent" class="form-control" id="edit_rent">
                        </div>
                        <div class="form-group">
                            <label for="edit_email">Email:</label>
                            <input type="email" name="email" class="form-control" id="edit_email">
                        </div>
                        <div class="form-group">
                            <label for="rent-date">Rent Start Date:</label>
                            <input type="text" placeholder="2074/10/16" name="rent_date" class="form-control" id="edit_rent_date">
                        </div>
                        <input type="hidden" name="id" id="edit_id">
                        <button class="btn btn-info" type="submit" name="edit_people" value="edit">Update</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>