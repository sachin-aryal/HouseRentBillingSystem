<?php
error_reporting(0);
include_once 'db_connect.php';
include_once 'nepali_calendar.php';
$id = $_GET['id'];
$rent = get_rent($conn, $id);
$tenant = get_people_info($conn, $rent["people_id"]);
require __DIR__.'/vendor/autoload.php';
$calendar = new Nepali_Calendar();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$today = $calendar->eng_to_nep(date('Y'), date('m'), date('d'));
$nepali_day = $today["date"];
$nepali_month = $today["nmonth"];
$nepali_year = $today["year"];
$rent_year = $rent["year"];
$rent_month = $rent["month"];

$date = $rent_year. ' '. $rent_month;
$total = $rent['rent']+$rent['electricity_bill']+$rent["water_cost"]+$rent["previous_rent"]+$rent["maintenance_cost"];
$used_unit = $rent['current_electricity_unit'] - $rent['previous_electricity_unit'];
?>
<?php
$message = "Hello ".$tenant['name'].",<br><br> Here is the bill for this month.<br><br>";
$message.= "<html>";
$message.="<head>";
$message.='<meta charset="utf-8"/></head><body>';
$message.= '<div class="container" style="width: 90%;margin: 0 auto">';
$message.='<div class="row">';
$message.='<div class="col-md-offset-3 col-md-9" style="width: 75%">';
$message.='<div id="print-section" class="row" style="width:90%;padding: 30px 30px 50px;border: 2px solid black;margin: 0 auto;background-color: #d7efc5 !important;">';
$message.='<div style="text-align: center">';
$message.='<h2>Deepa Private Home</h2>';
$message.='<h3>घर भाडा</h3>';
$message.='</div>';
$message.='<table style="float: right;width: 25%">';
$message.='<tr><th colspan="2">मिति :</th>';
$message.='<td>'.$date.'</td></tr></table>';
$message.='<table style="width: 100%;text-align: left">';
$message.='<tr><th colspan="4">बाहलावालाको नाम :</th>';
$message.='<td>'.$rent['name'].'</td></tr>';
$message.='<tr><th colspan="4">जम्मा घर भाडा :</th>';
$message.='<td>'.$rent['rent'].'</td></tr>';
$message.='<tr><th colspan="4">पहिलाको बाकि :</th>';
$message.='<td>'.$rent['previous_rent'].'</td></tr>';
$message.='<tr><th colspan="4">बत्तिको रु :</th>';
$message.='<td>'.$rent['electricity_bill'].' </td></tr>';
$message.='<tr><th colspan="4">खपत गरेको बिजुली :</th>';
$message.='<td>'.$used_unit.' युनिट</td></tr>';
$message.='<tr><th colspan="4">बत्तिको दर रु :</th>';
$message.='<td>'.get_electricity_price($conn).' प्रती युनिट</td></tr>';
$message.='<tr><th colspan="4">पानीको रु :</th>';
$message.='<td>'.$rent['water_cost'].'</td></tr>';
$message.='<tr><th colspan="4">मर्मत खर्च रु :</th>';
$message.='<td>'.$rent["maintenance_cost"].'</td></tr>';
$message.='<tr><th colspan="4">कुल जम्मा रु :</th>';
$message.='<td> '.$total.' </td></tr>';
$message.='<tr><td>&nbsp;</td></tr>';
$message.='<tr><td colspan="4">.....................................................</td>';
$message.='<td>.....................................................</td></tr>';
$message.='<tr><th colspan="4"> बुझाउनेको सही </th>';
$message.='<th> बुझिलिनेको सही </th></tr></table>';
$message.='<table style="width: 100%;margin-top: 30px;margin-bottom: 0px;text-align: center">';
$message.='<tr><td style="color: blue;">बैंक खाता नम्बर (NIC ASIA): URLABARI,7287763525524001, ANANDA PHAGU</td></tr>';
$message.='<tr><td style="color: green; text-align: center" >भाडा बुझाउने अन्तिम मिति ';
$message.=$nepali_month. " 5";
$message.='</td></tr>';
$message.='<tr><td style="text-align: center;border-top: 1px solid black;">';
$message.=$rent["remarks"];
$message.='</td></tr>';
$message.='<tr><td style="color: red; text-align: center;border-top: 1px solid black;font-size: large">NO PETS ALLOWED !!!</td></tr>';
$message.="</table>";
$message.='</div></div></div></div>';
$message.='</body></html>';
$from = 'ananphagu@gmail.com'; // todo: set from email.
$to = $tenant["email"];
$subject = 'Rent Alert';
$body = $message;
$mail = new PHPMailer(true);
try {
    //Server settings
//    $mail->SMTPDebug = 4;
//    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $from;
    $mail->Password = 'dallas604342';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    $mail->setFrom($from);
    $mail->addAddress($to);
    $mail->addReplyTo($from);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $message;

    $mail->send();
    header("Location:print.php?id=$id&m=y");
    return;
} catch (Exception $e) {
    echo $e->getMessage();
    header("Location:print.php?id=$id&m=n&me=$mail->ErrorInfo");
    return;
}

?>
