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
if(isset($_POST["new_return"])){
    if(insert_return($conn)){
        $message = "New Return Money Added Successfully.";
        $messageType = "success";
    }else{
        $message = "Error while adding new Return Money.";
        $messageType = "error";
    }

}else if (isset($_GET["delete"])){
    $id = $_GET["id"];
    if(delete_return($conn, $id)){
        $message = "Return Money deleted Successfully.";
        $messageType = "success";
    }else{
        $message = "Error while deleting Return Money.";
        $messageType = "error";
    }

}else if (isset($_POST["edit_return"])){
    if(update_return($conn)){
        $message = "Return Money Updated Successfully.";
        $messageType = "success";
    }else{
        $message = "Error while updating Return Money.";
        $messageType = "error";
    }
}
$peoples = getPeople($conn);
$left_to_returns=getLeftTOReturn($conn);
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
                <h2 style="display: inline-block">Change</h2>
                <button style="float: right" type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-people">Add Change</button>
            </div>
            <table id="people-list" class="display">
                <thead>
                <tr>
                    <th>SN</th>
                    <th>NAME</th>
                    <th>Change(Rs.)</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i=1;
                foreach ($left_to_returns as $left_to_return){
                    ?>
                    <tr>
                        <td><?php echo $i++ ?></td>
                        <td><?php
                            $left_name=get_people_info($conn,$left_to_return['people_id']) ;
                            echo $left_name['name'];


                            ?></td>
                        <td><?php echo $left_to_return["remain_to_give"] ?></td>


                        <td>

                            <a href="#" onclick="edit_return('<?php echo $left_to_return["id"] ?>','<?php echo $left_to_return["people_id"] ?>', '<?php echo $left_to_return["remain_to_give"] ?>')">Edit /
                            </a>
                            <a href="left_to_return.php?delete=yes&id=<?php echo $left_to_return['id'] ?>">Delete</a>

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
                    <h4 class="modal-title">Change</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="left_to_return.php">
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
                            <label for="remain_to_return">Remain to Return:</label>
                            <input type="number" name="remain_to_return" class="form-control" id="remain_to_return">
                        </div>

                        <button class="btn btn-info" type="submit" name="new_return" value="new">Add</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <div id="edit_return" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Tenant</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="left_to_return.php">
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
                            <label for="remain_to_return">Remain to Return:</label>
                            <input type="number" name="remain_to_return" class="form-control" id="edit_remain_to_return">
                        </div>

                        <button class="btn btn-info" type="submit" name="edit_return" value="new">Update</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>