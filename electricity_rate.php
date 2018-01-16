<?php
include '_header.php';
if(isset($_POST['save_rate'])){
    $id=1;
    $rate=$_POST['electricity_rate'];
    $stmt=$conn->prepare('update electricity_charge set rate=? where id=?');
    $stmt->bind_param('ii',$rate,$id);
    if($stmt->execute()){
        header("Location:electricity_rate.php");
    }
    header("Location:electricity_rate.php");

}

$charge=getElectricityRate($conn);
?>


<div class="container">
    <div class="row">
        <div class="col-md-offset-4 col-md-4">
            <h2>Electricity Rate</h2>
            <?php if(isset($_POST['edit_rate'])){?>
                <form method="post" action="electricity_rate.php">
                    <div class="form-group">
                        <label for="electricity_rate">Electricity Rate:</label>
                        <input type="number" name="electricity_rate" class="form-control" id="electricity_rate" value="<?php echo $charge['rate'] ?>">
                    </div>

                    <button type="submit" class="btn btn-primary" name="save_rate" >Update</button>

                </form>

           <?php  }else{ ?>
                <table id="rent-list" class="display table table-bordered">
                    <thead>
                    <tr>
                        <th>SN</th>
                        <th>Rate</th>
                        <th>Action</th>

                    </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td><?php echo 1 ?></td>
                            <td>NRs <?php
                            echo $charge['rate'];
                            ?></td>

                            <td>
                                <form method="post" action="electricity_rate.php">
                                    <button type="submit" class="btn btn-link" name="edit_rate" >Edit</button>

                                </form>
                            </td>

                        </tr>

                    </tbody>
                </table>
            <?php } ?>
    </div>
</div>
