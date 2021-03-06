<?php
include_once '_header.php';
$id = $_GET['id'];
$rent = get_rent($conn, $id);
$people = get_people_info($conn, $rent["people_id"]);
$today = $calendar->eng_to_nep(date('Y'), date('m'), date('d'));
$nepali_day = $today["date"];
$nepali_month = $today["nmonth"];
$nepali_year = $today["year"];

$rent_year = $rent["year"];
$rent_month = $rent["month"];
?>
<meta charset="utf-8"/>
<script src="js/jquery.PrintArea.js" type="text/javascript"></script>
<style>
    @media print {
        @page { margin: 0; }
    }
</style>
<script type="text/javascript">
    function print_div(){
        var mode = 'iframe'; //popup
        var close = mode == "popup";
        var options = { mode : mode, popClose : close};
        $("#print-section").printArea( options );
    }
    <?php
    $message = "";
    $messageType = "";
    if(isset($_GET["m"])){
        $m = $_GET["m"];
        if($m == 'y'){
            $message = "Email Sent Successfully.";
            $messageType = "info";
        }else{
            $message = $_GET["me"];
            $messageType = "error";
        }
    }
    ?>
    $(function () {
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
        <div class="col-md-offset-3 col-md-9">
            <div id="print-section" class="row" style="width:95%;padding: 30px 30px 10px;border: 2px solid black;margin: 0 auto;background-color: #d7efc5 !important;">
                <div style="text-align: center">
                    <h2>Deepa Private Home</h2>
                    <h3>घर भाडा</h3>
                </div>
                <table style="float: right;width: 20%">
                    <tr>
                        <th colspan="2">मिति :</th>
                        <td><?php echo $rent_year. ' '. $rent_month ?></td>
                    </tr>
                </table>
                <table style="width: 100%">
                    <tr>
                        <th colspan="4">बाहलावालाको नाम :</th>
                        <td><?php echo $rent['name'] ?></td>
                    </tr>
                    <tr>
                        <th colspan="4">जम्मा घर भाडा :</th>
                        <td><?php echo $rent['rent'] ?></td>
                    </tr>
                    <tr>
                        <th colspan="4">पहिलाको बाकि :</th>
                        <td><?php echo $rent['previous_rent'] ?></td>
                    </tr>
                    <tr>
                        <th colspan="4">बत्तिको रु :</th>
                        <td>
                            <?php echo $rent['electricity_bill'] ?>
                        </td>
                    </tr>
<!--                    <tr>-->
<!--                        <th colspan="4">पहिला बत्तिको </th>-->
<!--                        <td>--><?php //echo $rent["previous_electricity_unit"] ?><!-- युनिट</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <th colspan="4">अहिले बत्तिको </th>-->
<!--                        <td>--><?php //echo $rent["current_electricity_unit"] ?><!-- युनिट</td>-->
<!--                    </tr>-->
                    <tr>
                        <th colspan="4">खपत गरेको बिजुली :</th>
                        <td><?php echo $rent["current_electricity_unit"] - $rent["previous_electricity_unit"] ?> युनिट</td>
                    </tr>
                    <tr>
                        <th colspan="4">बत्तिको दर रु :</th>
                        <td><?php echo get_electricity_price($conn) ?> प्रती युनिट</td>
                    </tr>
                    <tr>
                        <th colspan="4">पानीको रु :</th>
                        <td>
                            <?php echo $rent['water_cost'] ?>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="4">मर्मत खर्च रु :</th>
                        <td>
                            <?php echo $rent['maintenance_cost'] ?>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="4">कुल जम्मा रु :</th>
                        <td>
                            <?php
                            $total=$rent['rent']+$rent['electricity_bill']+$rent["water_cost"]+$rent["previous_rent"]+$rent['maintenance_cost'];
                            echo $total;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="4">
                           .....................................................
                        </td>
                        <td>
                            .....................................................
                        </td>
                    </tr>
                    <tr>
                        <th colspan="4">
                            बुझाउनेको सही
                        </th>
                        <th>
                            बुझिलिनेको सही
                        </th>
                    </tr>
                </table>
                <table style="width: 100%;margin-top: 30px;margin-bottom: 0px;text-align: center">
                    <tr>
                        <td style="color: blue;">
                            बैंक खाता नम्बर (NIC ASIA): URLABARI, 7287763525524001, ANANDA PHAGU
                        </td>
                    </tr>
                    <tr>
                        <td style="color: green; text-align: center">
                            भाडा बुझाउने अन्तिम मिति <?php echo $nepali_month ?> 5
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;border-top: 1px solid black;">
                            <?php echo $rent["remarks"] ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="color: red; text-align: center;border-top: 1px solid black;font-size: large">
                            NO PETS ALLOWED !!!
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-offset-8 col-md-4" style="margin-top: 10px;margin-left: 10px">
                <button style="margin-left: 15px;" class="btn btn-primary" onclick="print_div()">Print</button>
                <?php if($people["email"] != "") {?>
                <a href="send_email.php?id=<?php echo $id ?>" class="btn btn-info">Send Email</a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>