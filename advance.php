<?php
/**
 * Created by PhpStorm.
 * User: sachin
 * Date: 1/15/18
 * Time: 10:34 AM
 */
include_once '_header.php';
$message = "";
$messageType = "";
if(isset($_POST["new_advance_payment"])){
    if(insert_advance($conn)){
        $message = "New Advance Payment Added Successfully.";
        $messageType = "success";
    }else{
        $message = "Error while adding new advance payment .";
        $messageType = "error";
    }

}else if (isset($_GET["delete"])){
    $id = $_GET["id"];
    if(delete_advance($conn, $id)){
        $message = "Advance Payment deleted Successfully.";
        $messageType = "success";
    }else{
        $message = "Error while deleting advance payment.";
        $messageType = "error";
    }

}else if (isset($_POST["edit_advance"])){
    if(update_advance($conn)){
        $message = "Advance Payment Updated Successfully.";
        $messageType = "success";
    }else{
        $message = "Error while updating Advance Payment.";
        $messageType = "error";
    }
}
$peoples = getPeople($conn);
$advances = getAdvance($conn);
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
                <h2 style="display: inline-block">Advance Payment</h2>
                <button style="float: right" type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-advance">Add Advance Payment</button>
            </div>
            <table id="people-list" class="display">
                <thead>
                <tr>
                    <th>SN</th>
                    <th>NAME</th>
                    <th>Advance</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i=1;
                foreach ($advances as $advance){
                    ?>
                    <tr>
                        <td><?php echo $i++ ?></td>
                        <td><?php
                            $payer_name=get_people_info($conn,$advance['people_id']) ;
                            echo $payer_name['name'];


                            ?></td>
                        <td><?php echo $advance["advance"] ?></td>
                        <td>

                            <a href="#" onclick="edit_advance('<?php echo $advance["id"] ?>','<?php echo $advance["people_id"] ?>', '<?php echo $advance["advance"] ?>')">Edit
                            </a>
                            /
                            <a href="advance.php?delete=yes&id=<?php echo $advance['id'] ?>">Delete</a>

                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="add-advance" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Advance Payment</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="advance.php">
                        <div class="form-group">
                            <label for="usr">Name:</label>
                            <select class="form-control" name="usr">
                                <?php $peoples=getPeople($conn) ;
                                foreach ($peoples as $people){
                                    ?>
                                    <option value="<?php echo $people['id']?>"><?php echo $people['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="advance">Advance Payment:</label>
                            <input type="number" name="advance" class="form-control" id="advance">
                        </div>

                        <button class="btn btn-info" type="submit" name="new_advance_payment" value="new">Add</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <div id="edit_advance" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Advance Payment</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="advance.php">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="form-group">
                            <label for="usr">Name:</label>
                            <select class="form-control" name="name" id="edit_name">
                                <?php $peoples=getPeople($conn) ;
                                foreach ($peoples as $people){
                                    ?>
                                    <option value="<?php echo $people['id']?>"><?php echo $people['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_advance_payment">Advance Payment:</label>
                            <input type="number" name="advance" class="form-control" id="edit_advance_payment">
                        </div>

                        <button class="btn btn-info" type="submit" name="edit_advance" value="edit">Update</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>