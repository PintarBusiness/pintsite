<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/phpmailer/src/Exception.php';
require __DIR__ . '/phpmailer/src/PHPMailer.php';
require __DIR__ . '/phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: kontakt.html");
    exit;
}

function clean_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

$ime = clean_input($_POST["ime"] ?? "");
$email = clean_input($_POST["email"] ?? "");
$telefon = clean_input($_POST["telefon"] ?? "");
$podjetje = clean_input($_POST["podjetje"] ?? "");
$paket = clean_input($_POST["paket"] ?? "");
$sporocilo = clean_input($_POST["sporocilo"] ?? "");

if (empty($ime) || empty($email) || empty($sporocilo)) {
    echo "
    <!DOCTYPE html>
    <html lang='sl'>
    <head>
      <meta charset='UTF-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <title>Napaka | PintSite</title>
      <style>
        body {
          margin: 0;
          font-family: Arial, sans-serif;
          background: #f8fbff;
          color: #0f172a;
          display: flex;
          align-items: center;
          justify-content: center;
          min-height: 100vh;
          padding: 20px;
        }
        .box {
          max-width: 600px;
          width: 100%;
          background: #ffffff;
          border-radius: 24px;
          padding: 40px 30px;
          box-shadow: 0 20px 60px rgba(15, 23, 42, 0.08);
          text-align: center;
        }
        h1 {
          margin-bottom: 15px;
          font-size: 32px;
        }
        p {
          color: #64748b;
          line-height: 1.7;
          margin-bottom: 25px;
        }
        a {
          display: inline-block;
          text-decoration: none;
          background: linear-gradient(135deg, #2563eb, #06b6d4);
          color: white;
          padding: 14px 24px;
          border-radius: 999px;
          font-weight: bold;
        }
      </style>
    </head>
    <body>
      <div class='box'>
        <h1>Manjkajo obvezna polja</h1>
        <p>Prosim, izpolni ime, email in sporočilo.</p>
        <a href='kontakt.html'>Nazaj na kontakt</a>
      </div>
    </body>
    </html>
    ";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "
    <!DOCTYPE html>
    <html lang='sl'>
    <head>
      <meta charset='UTF-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <title>Napaka | PintSite</title>
      <style>
        body {
          margin: 0;
          font-family: Arial, sans-serif;
          background: #f8fbff;
          color: #0f172a;
          display: flex;
          align-items: center;
          justify-content: center;
          min-height: 100vh;
          padding: 20px;
        }
        .box {
          max-width: 600px;
          width: 100%;
          background: #ffffff;
          border-radius: 24px;
          padding: 40px 30px;
          box-shadow: 0 20px 60px rgba(15, 23, 42, 0.08);
          text-align: center;
        }
        h1 {
          margin-bottom: 15px;
          font-size: 32px;
        }
        p {
          color: #64748b;
          line-height: 1.7;
          margin-bottom: 25px;
        }
        a {
          display: inline-block;
          text-decoration: none;
          background: linear-gradient(135deg, #2563eb, #06b6d4);
          color: white;
          padding: 14px 24px;
          border-radius: 999px;
          font-weight: bold;
        }
      </style>
    </head>
    <body>
      <div class='box'>
        <h1>Email ni veljaven</h1>
        <p>Preveri vneseni email naslov in poskusi ponovno.</p>
        <a href='kontakt.html'>Nazaj na kontakt</a>
      </div>
    </body>
    </html>
    ";
    exit;
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'sh29.neoserv.si';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'info@pintsite.si';
    $mail->Password   = 'HE HE HE skrivnost';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->CharSet = 'UTF-8';

    $mail->setFrom('info@pintsite.si', 'PintSite');
    $mail->addAddress('info@pintsite.si');
    $mail->addReplyTo($email, $ime);

    $mail->Subject = 'Novo povpraševanje preko spletne strani';

    $mail->Body = "Novo povpraševanje s strani PintSite\n\n";
    $mail->Body .= "Ime in priimek: $ime\n";
    $mail->Body .= "Email: $email\n";
    $mail->Body .= "Telefon: $telefon\n";
    $mail->Body .= "Podjetje: $podjetje\n";
    $mail->Body .= "Paket: $paket\n\n";
    $mail->Body .= "Sporočilo:\n$sporocilo\n";

    $mail->send();

    echo "
    <!DOCTYPE html>
    <html lang='sl'>
    <head>
      <meta charset='UTF-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <title>Sporočilo poslano | PintSite</title>
      <style>
        body {
          margin: 0;
          font-family: Arial, sans-serif;
          background: #f8fbff;
          color: #0f172a;
          display: flex;
          align-items: center;
          justify-content: center;
          min-height: 100vh;
          padding: 20px;
        }
        .box {
          max-width: 600px;
          width: 100%;
          background: #ffffff;
          border-radius: 24px;
          padding: 40px 30px;
          box-shadow: 0 20px 60px rgba(15, 23, 42, 0.08);
          text-align: center;
        }
        h1 {
          margin-bottom: 15px;
          font-size: 32px;
        }
        p {
          color: #64748b;
          line-height: 1.7;
          margin-bottom: 25px;
        }
        a {
          display: inline-block;
          text-decoration: none;
          background: linear-gradient(135deg, #2563eb, #06b6d4);
          color: white;
          padding: 14px 24px;
          border-radius: 999px;
          font-weight: bold;
        }
      </style>
    </head>
    <body>
      <div class='box'>
        <h1>Sporočilo je bilo uspešno poslano</h1>
        <p>Hvala za tvoje povpraševanje. Odgovoril ti bom v najkrajšem možnem času.</p>
        <a href='kontakt.html'>Nazaj na kontakt</a>
      </div>
    </body>
    </html>
    ";
    exit;

} catch (Exception $e) {
    echo "
    <!DOCTYPE html>
    <html lang='sl'>
    <head>
      <meta charset='UTF-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <title>Napaka | PintSite</title>
      <style>
        body {
          margin: 0;
          font-family: Arial, sans-serif;
          background: #f8fbff;
          color: #0f172a;
          display: flex;
          align-items: center;
          justify-content: center;
          min-height: 100vh;
          padding: 20px;
        }
        .box {
          max-width: 700px;
          width: 100%;
          background: #ffffff;
          border-radius: 24px;
          padding: 40px 30px;
          box-shadow: 0 20px 60px rgba(15, 23, 42, 0.08);
          text-align: center;
        }
        h1 {
          margin-bottom: 15px;
          font-size: 32px;
        }
        p {
          color: #64748b;
          line-height: 1.7;
          margin-bottom: 25px;
          word-break: break-word;
        }
        a {
          display: inline-block;
          text-decoration: none;
          background: linear-gradient(135deg, #2563eb, #06b6d4);
          color: white;
          padding: 14px 24px;
          border-radius: 999px;
          font-weight: bold;
        }
      </style>
    </head>
    <body>
      <div class='box'>
        <h1>Pri pošiljanju je prišlo do napake</h1>
        <p>" . htmlspecialchars($mail->ErrorInfo, ENT_QUOTES, 'UTF-8') . "</p>
        <a href='kontakt.html'>Nazaj na kontakt</a>
      </div>
    </body>
    </html>
    ";
    exit;
}
?>
