<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: kontakt.html");
    exit;
}

function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

$ime = clean_input($_POST["ime"] ?? "");
$email = clean_input($_POST["email"] ?? "");
$telefon = clean_input($_POST["telefon"] ?? "");
$podjetje = clean_input($_POST["podjetje"] ?? "");
$paket = clean_input($_POST["paket"] ?? "");
$sporocilo = clean_input($_POST["sporocilo"] ?? "");

if (empty($ime) || empty($email) || empty($sporocilo)) {
    echo "Prosim, izpolni vsa obvezna polja.";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Email naslov ni veljaven.";
    exit;
}

/* KAMOR ŽELIŠ PREJETI EMAIL */
$to = "info@pintsite.si";

/* ZADEVA */
$subject = "Novo povpraševanje preko spletne strani";

/* VSEBINA EMAILA */
$message = "
Novo povpraševanje s strani PintSite

Ime in priimek: $ime
Email: $email
Telefon: $telefon
Podjetje: $podjetje
Paket: $paket

Sporočilo:
$sporocilo
";

/* HEADERS */
$headers = "From: PintSite <info@pintsite.si>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

if (mail($to, $subject, $message, $headers)) {
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
} else {
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
        <h1>Pri pošiljanju je prišlo do napake</h1>
        <p>Preveri nastavitve strežnika ali poskusi ponovno kasneje.</p>
        <a href='kontakt.html'>Nazaj na kontakt</a>
      </div>
    </body>
    </html>
    ";
}
?>