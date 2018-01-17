<?php
include_once 'db_connect.php';
$id = $_GET['id'];
$rent = get_rent($conn, $id);
$tenant = get_people_info($conn, $rent["people_id"]);
require __DIR__.'/vendor/autoload.php';
$calendar = new Fivedots\NepaliCalendar\Calendar(new \Fivedots\NepaliCalendar\NepaliDataProvider());

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$today = $calendar->englishToNepali(date('Y'), date('m'), date('d'));
$nepali_day = $today["numDay"];
$nepali_month = $today["nmonth"];
$nepali_year = $today["year"];
$date = $nepali_month. " ". $nepali_day. ", ".$nepali_year;
$total = $rent['rent']+$rent['electricity_bill']+$rent["water_cost"]+$rent["previous_rent"];
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
$message.='<h2>दिपा अपार्टमेन्ट Pvt. Ltd</h2>';
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
$message.='<td>'.$rent['water_cost'].'</td>';
$message.='<tr><th colspan="4">कुल जम्मा रु :</th>';
$message.='<td> '.$total.' </td></tr>';
$message.='<tr><td>&nbsp;</td></tr>';
$message.='<tr><td colspan="4">.....................................................</td>';
$message.='<td>.....................................................</td></tr>';
$message.='<tr><th colspan="4"> बुझाउनेको सही </th>';
$message.='<th> बुझिलिनेको सही </th></tr></table>';
$message.='</div></div></div></div>';
$message.='</body></html>';

$from = ''; // todo: set from email.
$to = $tenant["email"];
$subject = 'Rent Alert';
$body = $message;
$mail = new PHPMailer(true);
try {
    //Server settings
//    $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = ''; //todo: sender email
    $mail->Password = ''; //tod: sender email's password
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
    header("Location:print.php?id=$id&m=n&me=$mail->ErrorInfo");
    return;
}

?>
