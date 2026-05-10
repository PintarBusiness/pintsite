<?php
require_once "config.php";
require_once "invoices_db.php";

if(!isset($_SESSION["admin_logged"])){
    die("Ni dostopa.");
}

$company     = trim($_POST["company"] ?? '');
$person      = trim($_POST["person"] ?? '');
$email       = trim($_POST["email"] ?? '');
$phone       = trim($_POST["phone"] ?? '');
$address     = trim($_POST["address"] ?? '');
$city        = trim($_POST["city"] ?? '');
$tax         = trim($_POST["tax"] ?? '');
$package     = $_POST["package"] ?? '';
$payment     = $_POST["payment"] ?? '';
$price       = floatval($_POST["price"] ?? 0);
$description = nl2br(htmlspecialchars($_POST["description"] ?? ''));

$invoice = getNextInvoiceNumber();
$date    = date("d.m.Y");
$due     = date("d.m.Y", strtotime("+7 days"));
$due_ts  = date("Y-m-d", strtotime("+7 days"));

// --- Generiraj HTML email ---
ob_start();
include __DIR__ . "/invoice_template.php";
$htmlMessage = ob_get_clean();

// --- Generiraj PDF prilogo ---
$pdfFileName = "Racun_" . preg_replace('/[^A-Za-z0-9_-]/', '_', $invoice) . ".pdf";
$pdfPath = sys_get_temp_dir() . "/" . $pdfFileName;
$pdfGenerated = generateInvoicePDF(
    $invoice, $date, $due,
    $company, $person, $address, $city, $phone, $email, $tax,
    $package, $payment, $price, $description, $pdfPath
);

// --- Sestavi MIME email z PDF prilogo ---
$boundary = "==PintSite_" . md5(uniqid(rand(), true));
$subject  = "Račun " . $invoice . " – PintSite";

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
$headers .= "From: PintSite <info@pintsite.si>\r\n";
$headers .= "Reply-To: info@pintsite.si\r\n";
$headers .= "X-Mailer: PintSite Invoice System 2.0\r\n";

$body  = "--$boundary\r\n";
$body .= "Content-Type: text/html; charset=UTF-8\r\n";
$body .= "Content-Transfer-Encoding: base64\r\n\r\n";
$body .= chunk_split(base64_encode($htmlMessage)) . "\r\n";

if ($pdfGenerated && file_exists($pdfPath)) {
    $pdfData = file_get_contents($pdfPath);
    $body .= "--$boundary\r\n";
    $body .= "Content-Type: application/pdf; name=\"" . $pdfFileName . "\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n";
    $body .= "Content-Disposition: attachment; filename=\"" . $pdfFileName . "\"\r\n\r\n";
    $body .= chunk_split(base64_encode($pdfData)) . "\r\n";
    @unlink($pdfPath);
}
$body .= "--$boundary--\r\n";

mail($email, $subject, $body, $headers);

// --- Shrani v bazo ---
addInvoice([
    'invoice'     => $invoice,
    'date'        => $date,
    'date_ts'     => date("Y-m-d"),
    'due'         => $due,
    'due_ts'      => $due_ts,
    'company'     => $company,
    'person'      => $person,
    'email'       => $email,
    'phone'       => $phone,
    'address'     => $address,
    'city'        => $city,
    'tax'         => $tax,
    'package'     => $package,
    'payment'     => $payment,
    'price'       => $price,
    'price_paid'  => 0,
    'description' => $description,
    'status'      => 'unpaid',
    'paid_at'     => null,
    'notes'       => '',
]);

header("Location: invoices.php?sent=1");
exit;

// -------------------------------------------------------
// PDF generiranje (wkhtmltopdf)
// -------------------------------------------------------
function generateInvoicePDF(
    $invoice, $date, $due,
    $company, $person, $address, $city, $phone, $email, $tax,
    $package, $payment, $price, $description, $outputPath
): bool {

    ob_start(); ?>
<!DOCTYPE html><html lang="sl"><head>
<meta charset="UTF-8">
<style>
  *{box-sizing:border-box;}
  body{font-family:Arial,sans-serif;color:#0f172a;margin:0;padding:24px;background:#f4f8ff;font-size:13px;}
  .wrap{max-width:640px;margin:0 auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08);}
  .hdr{background:linear-gradient(135deg,#2563eb,#1d4ed8);padding:28px 32px;color:#fff;}
  .hdr-title{font-size:24px;font-weight:800;letter-spacing:2px;margin:0 0 6px;}
  .hdr-meta{font-size:12px;line-height:1.8;opacity:.9;}
  .body-pad{padding:28px 32px;}
  .box{background:#f8fbff;border-radius:12px;padding:16px 18px;margin-bottom:12px;}
  .lbl{font-size:10px;text-transform:uppercase;letter-spacing:1px;color:#64748b;font-weight:700;margin-bottom:7px;}
  .two{width:100%;border-collapse:separate;border-spacing:12px 0;margin-bottom:12px;}
  .total-box{background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:12px;padding:20px;margin-bottom:12px;}
  .total-row{display:flex;justify-content:space-between;margin-bottom:7px;}
  .total-big{font-size:26px;font-weight:800;color:#2563eb;}
  .pay-box{background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:16px 18px;margin-bottom:12px;}
  .pay-lbl{font-size:10px;text-transform:uppercase;letter-spacing:1px;color:#16a34a;font-weight:700;margin-bottom:7px;}
  .ftr{padding:16px 32px;border-top:1px solid rgba(15,23,42,.08);font-size:11px;color:#64748b;}
</style>
</head>
<body>
<div class="wrap">
  <div class="hdr">
    <table width="100%"><tr>
      <td valign="top">
        <div style="font-size:18px;font-weight:800;">PintSite</div>
        <div style="font-size:11px;line-height:1.8;opacity:.85;margin-top:6px;">Jure Pintar<br>info@pintsite.si<br>+386 XX XXX XXX</div>
      </td>
      <td valign="top" align="right">
        <div class="hdr-title">RAČUN</div>
        <div class="hdr-meta">
          <strong>Št: <?= htmlspecialchars($invoice) ?></strong><br>
          Datum: <?= $date ?><br>
          Rok plačila: <?= $due ?><br>
          <span style="opacity:.7;font-size:11px;">Status: ČAKA NA PLAČILO</span>
        </div>
      </td>
    </tr></table>
  </div>

  <div class="body-pad">
    <table class="two"><tr>
      <td valign="top" style="width:50%;">
        <div class="box"><div class="lbl">Stranka</div>
          <div style="line-height:1.8;">
            <strong><?= htmlspecialchars($company) ?></strong><br>
            <?= $person  ? htmlspecialchars($person).'<br>'  : '' ?>
            <?= $address ? htmlspecialchars($address).'<br>' : '' ?>
            <?= $city    ? htmlspecialchars($city).'<br>'    : '' ?>
            <?= $phone   ? htmlspecialchars($phone).'<br>'   : '' ?>
            <?= htmlspecialchars($email) ?>
            <?= $tax ? '<br>DDV: '.htmlspecialchars($tax) : '' ?>
          </div>
        </div>
      </td>
      <td valign="top" style="width:50%;">
        <div class="box"><div class="lbl">Paket</div>
          <div><strong><?= htmlspecialchars($package) ?></strong></div>
        </div>
      </td>
    </tr></table>

    <div class="box"><div class="lbl">Opis storitev</div>
      <div style="line-height:1.8;"><?= $description ?></div></div>

    <div class="total-box">
      <div class="total-row"><span>Način plačila</span><strong><?= htmlspecialchars($payment) ?></strong></div>
      <div class="total-row"><span>DDV</span><strong>Ni zavezanec</strong></div>
      <div style="border-top:1px solid rgba(15,23,42,.1);padding-top:12px;display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:#64748b;">Skupaj (z DDV)</span>
        <span class="total-big"><?= number_format($price, 2, ',', '.') ?> €</span>
      </div>
    </div>

    <div class="pay-box"><div class="pay-lbl">Podatki za plačilo</div>
      <div style="line-height:1.9;">
        <strong>Prejemnik:</strong> Jure Pintar – PintSite<br>
        <strong>TRR:</strong> SI56 XXXX XXXX XXXX XXX<br>
        <strong>BIC/SWIFT:</strong> XXXXXXXX<br>
        <strong>Namen:</strong> Plačilo računa <?= htmlspecialchars($invoice) ?><br>
        <strong>Rok plačila:</strong> <?= $due ?>
      </div>
    </div>
  </div>

  <div class="ftr">
    <strong style="color:#0f172a;">PintSite – Jure Pintar</strong> &nbsp;|&nbsp;
    info@pintsite.si &nbsp;|&nbsp; +386 XX XXX XXX &nbsp;|&nbsp; Hvala za vaše zaupanje!
  </div>
</div>
</body></html>
<?php
    $html = ob_get_clean();

    $htmlTmp = tempnam(sys_get_temp_dir(), "psinv_") . ".html";
    file_put_contents($htmlTmp, $html);
    $cmd = "wkhtmltopdf --quiet --encoding utf-8 --page-size A4 --margin-top 8 --margin-bottom 8 --margin-left 8 --margin-right 8 "
         . escapeshellarg($htmlTmp) . " " . escapeshellarg($outputPath) . " 2>/dev/null";
    exec($cmd, $out, $ret);
    @unlink($htmlTmp);
    return ($ret === 0 && file_exists($outputPath));
}
?>
