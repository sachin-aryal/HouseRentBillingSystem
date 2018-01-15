<?php
/**
 * Created by PhpStorm.
 * User: sachin
 * Date: 1/15/18
 * Time: 10:21 AM
 */
include_once '_header.php';
$rent_list = array();
?>
<script type="text/javascript">
    $(function () {
        $('#rent-list').DataTable();
    })
</script>
<div class="container">
    <div class="row">
        <div class="col-md-offset-4 col-md-4">
            <table id="rent-list" class="display">
                <thead>
                <tr>
                    <th>Name</th>
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

                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
