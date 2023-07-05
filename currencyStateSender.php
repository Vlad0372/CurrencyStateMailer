<?php 
require "vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

while(true){
    $mail = new PHPMailer(true);

    $data = file_get_contents('http://api.nbp.pl/api/exchangerates/rates/a/CHF/');
    
    $obj = json_decode($data, true);
    
    $currency_name = $obj['currency'];
    $currency_code = $obj['code'];
    $got_at = $obj['rates'][0]['effectiveDate'];
    $value = $obj['rates'][0]['mid'];
    
    $mailContent = "Waluta: ". $currency_name." (".$currency_code.")"."<br><br>Data pobrania: ". $got_at ."<br><br>Kurs: ".$value;
    
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host       = 'smtp.sendgrid.net';
        $mail->SMTPAuth   = true; 
        $mail->Username   = 'apikey'; 
        $mail->Password   = 'your_password';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;                                   
    
        $mail->setFrom('vladyslav.email@gmail.com', 'Vladyslav');
        $mail->addAddress('babcia.email@gmail.com', 'Babcia Angnieszka');        

        //Content
        $mail->isHTML(true);                                 
        $mail->Subject = 'Kurs franka szwajcarskiego';
        $mail->Body = $mailContent;
    
        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    sleep(60*60*24);
}
?>
