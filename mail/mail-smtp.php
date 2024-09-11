<?php

function authSendEmail($from, $namefrom, $to, $nameto, $subject, $message)
{
//KONFIGURACE SMTP SERVERU
$smtpServer = "mdmail.hlubina.com";
$port = "25";
$timeout = "30";
$username = "swowaco.fans@onlygay.tv";
$password = "heslo";
//$localhost = "email.mydreams.cz";
$newLine = "\r\n";


//PRIPOJENI NA SMTP SERVER PRES URCENY PORT
$smtpConnect = fsockopen($smtpServer, $port, $error_code, $error_message, $timeout);
$smtpResponse = fgets($smtpConnect, 515);

/*
if(empty($smtpConnect))
{
$output = "Failed to connect: $smtpResponse";
return $output;
}
else
{
$logArray['connection'] = "Connected: $smtpResponse";
return $logArray['connection'];
}
*/


fputs($smtpConnect,"EHLO onlygay.tv" . $newLine);

fputs($smtpConnect,"AUTH LOGIN" . $newLine);
$smtpResponse = fgets($smtpConnect, 515);
$logArray['authrequest'] = "$smtpResponse";

fputs($smtpConnect, base64_encode($username) . $newLine);
$smtpResponse = fgets($smtpConnect, 515);
$logArray['authusername'] = "$smtpResponse";

fputs($smtpConnect, base64_encode($password) . $newLine);
$smtpResponse = fgets($smtpConnect, 515);
$logArray['authpassword'] = "$smtpResponse";


fputs($smtpConnect, "MAIL FROM: $from" . $newLine);
$smtpResponse = fgets($smtpConnect, 515);
$logArray['mailfromresponse'] = "$smtpResponse";

fputs($smtpConnect, "RCPT TO: $to" . $newLine);
$smtpResponse = fgets($smtpConnect, 515);
$logArray['mailtoresponse'] = "$smtpResponse";

fputs($smtpConnect, "DATA" . $newLine);
$smtpResponse = fgets($smtpConnect, 515);
$logArray['data1response'] = "$smtpResponse";


$headers = "From: " . $from . "\r\n";
$headers .= "Reply-To: " . $from . "\r\n";
$headers .= "Return-Path: " . $from . "\r\n";
$headers .= "X-Priority: 1" ."\r\n";
$headers .= "x-Mailer: PHP/" . phpversion();
$headers .= "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-Type: text/html; charset=utf-8" . "\r\n";

fputs($smtpConnect, "To: $to\nFrom: $from\nSubject: $subject\n$headers\n\n$message\n.\n");
$smtpResponse = fgets($smtpConnect, 515);
$logArray['data2response'] = "$smtpResponse";


fputs($smtpConnect,"QUIT" . $newLine);
$smtpResponse = fgets($smtpConnect, 515);
$logArray['quitresponse'] = "$smtpResponse";

return $smtpResponse;


}


$to = "js.obfuscator@gmail.com";
$nameto = "js.obfuscator@gmail.com";
$from = "swowaco.fans@onlygay.tv";
$namefrom = "swowaco.fans@onlygay.tv";
$subject = "onlygay.tv - please verify your email address 003 ";
$message = "please verify your email address 003";
echo authSendEmail($from, $namefrom, $to, $nameto, $subject, $message);
echo "<br><br>";
echo $to;



$smtp_data = array(
"server" => "mdmail.hlubina.com",
"port" => 25,
"timeout" => 30
);


/*
$test = fsockopen($smtp_data["server"], $smtp_data["port"], $error_code, $error_message, $smtp_data["timeout"]);

echo $error_code . " " . $error_message;
*/



?>