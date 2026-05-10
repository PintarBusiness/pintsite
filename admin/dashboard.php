<?php
require_once "config.php";

if(!isset($_SESSION["admin_logged"])){
    header("Location: admin.php");
    exit;
}

$today = date("d.m.Y");
$due = date("d.m.Y", strtotime("+7 days"));
$invoiceNumber = peekInvoiceNumber();
?>

<!DOCTYPE html>
<html lang="sl">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>PintSite Dashboard</title>

<link rel="stylesheet" href="../style.css">
<link rel="stylesheet" href="admin.css">

<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<script>

function updatePreview(){

/* Stranka */
document.getElementById("p-company").innerText =
document.getElementById("company").value || "Podjetje d.o.o.";

document.getElementById("p-person").innerText =
document.getElementById("person").value;

document.getElementById("p-address").innerText =
document.getElementById("address").value;

document.getElementById("p-city").innerText =
document.getElementById("city").value;

document.getElementById("p-phone").innerText =
document.getElementById("phone").value;

document.getElementById("p-email").innerText =
document.getElementById("email").value || "mail@example.com";

document.getElementById("p-tax").innerText =
document.getElementById("tax").value ? "DDV: " + document.getElementById("tax").value : "";

/* Paket */
document.getElementById("p-package").innerText =
document.getElementById("package").value;

/* Plačilo */
document.getElementById("p-payment").innerText =
document.getElementById("payment").value;

/* Cena */
document.getElementById("p-price").innerText =
(document.getElementById("price").value || "0") + " €";

/* Opis */
var desc = document.getElementById("description").value;
if(desc.trim()){
    document.getElementById("p-desc").innerHTML =
        "<ul style='padding-left:18px;line-height:1.9;font-size:14px;'>" +
        desc.split("\n").filter(x => x.trim()).map(x => "<li>" + x + "</li>").join("") +
        "</ul>";
} else {
    document.getElementById("p-desc").innerHTML =
        "<ul style='padding-left:18px;line-height:1.9;font-size:14px;'><li>—</li></ul>";
}

/* Namen plačila */
document.getElementById("p-invoice-ref").innerText =
document.getElementById("invoice").value;

}

</script>

</head>

<body class="dashboard-body">

<div class="dashboard-layout">

<div class="sidebar">

<div class="sidebar-top">

<img src="../Logopravitext.png" class="dash-logo">

<div style="display:flex;flex-direction:column;gap:8px;">
<a href="invoices.php" style="display:flex;align-items:center;gap:8px;padding:10px 16px;border-radius:14px;background:rgba(37,99,235,.1);color:#2563eb;text-decoration:none;font-weight:700;font-size:13px;">
📄 Poslani računi
</a>
<a href="logout.php" class="logout-btn">
Odjava
</a>
</div>

</div>

<div class="stats-grid">

<div class="stat-card">
<span>Aktivni status</span>
<strong>ONLINE</strong>
</div>

<div class="stat-card">
<span>Invoice sistem</span>
<strong>READY</strong>
</div>

</div>

<div class="panel-card">

<h2>Ustvari račun</h2>

<p class="panel-subtitle">
Pošlji profesionalen račun direktno na email stranke.
</p>

<?php if(isset($_GET['success'])): ?>
<div style="background:#dcfce7;border:1px solid #86efac;border-radius:14px;padding:14px 18px;margin-bottom:16px;color:#166534;font-weight:600;font-size:14px;">
✅ Račun poslan s PDF prilogo! <a href="invoices.php" style="color:#15803d;font-weight:800;">Oglej si →</a>
</div>
<?php endif; ?>

<form action="send_invoice.php" method="POST">

<div class="form-row">

<div class="form-group">
<label>Ime podjetja</label>
<input
type="text"
name="company"
id="company"
oninput="updatePreview()"
placeholder="Pint Company d.o.o."
required>
</div>

<div class="form-group">
<label>Kontaktna oseba</label>
<input
type="text"
name="person"
id="person"
oninput="updatePreview()"
placeholder="Marko Novak">
</div>

</div>

<div class="form-row">

<div class="form-group">
<label>Email stranke</label>
<input
type="email"
name="email"
id="email"
oninput="updatePreview()"
placeholder="mail@podjetje.si"
required>
</div>

<div class="form-group">
<label>Telefonska številka</label>
<input
type="text"
name="phone"
id="phone"
oninput="updatePreview()"
placeholder="+386 41 123 456">
</div>

</div>

<div class="form-row">

<div class="form-group">
<label>Naslov podjetja</label>
<input
type="text"
name="address"
id="address"
oninput="updatePreview()"
placeholder="Dunajska cesta 1">
</div>

<div class="form-group">
<label>Poštna številka in kraj</label>
<input
type="text"
name="city"
id="city"
oninput="updatePreview()"
placeholder="1000 Ljubljana">
</div>

</div>

<div class="form-row">

<div class="form-group">
<label>Davčna številka</label>
<input
type="text"
name="tax"
id="tax"
oninput="updatePreview()"
placeholder="SI12345678">
</div>

<div class="form-group">
<label>Paket</label>

<select
name="package"
id="package"
oninput="updatePreview()">

<option>Start</option>
<option>Growth</option>
<option>Premium</option>
<option>Prilagojen</option>

</select>

</div>

</div>

<div class="form-row">

<div class="form-group">
<label>Način plačila</label>

<select name="payment" id="payment" oninput="updatePreview()">
<option>Nakazilo na TRR</option>
<option>Gotovina</option>
</select>

</div>

<div class="form-group">
<label>Cena (€)</label>
<input
type="number"
name="price"
id="price"
oninput="updatePreview()"
placeholder="1200"
required>
</div>

</div>

<div class="form-group">
<label>Številka računa</label>
<input
type="text"
name="invoice"
id="invoice"
oninput="updatePreview()"
value="<?= $invoiceNumber ?>">
</div>

<div class="form-group">
<label>Opis storitev</label>

<textarea
name="description"
id="description"
oninput="updatePreview()"
placeholder="Vsaka nova vrstica = nova alineja"></textarea>

</div>

<button class="primary-btn">
Pošlji račun
</button>

</form>

</div>

</div>

<!-- PREVIEW -->
<div class="preview-side">

<div class="invoice-preview">

<!-- HEADER -->
<div class="invoice-header">
<div class="invoice-top">

<div>
<img src="../Logopravitext.png">
<div style="margin-top:14px;font-size:13px;line-height:1.8;color:rgba(255,255,255,.85);">
<strong style="color:white;font-size:15px;">PintSite</strong><br>
Jure Pintar<br>
info@pintsite.si<br>
+386 XX XXX XXX
</div>
</div>

<div class="invoice-title" style="text-align:right;">
<h2>RAČUN</h2>
<div class="invoice-meta">
<p><?= $invoiceNumber ?></p>
<p>Datum: <?= $today ?></p>
<p>Rok plačila: <?= $due ?></p>
<p style="margin-top:10px;opacity:.7;font-size:12px;">Status: <strong>ČAKA NA PLAČILO</strong></p>
</div>
</div>

</div>
</div>

<!-- VSEBINA -->
<div class="invoice-content">

<div class="invoice-grid">

<div class="invoice-box">
<h4>Stranka</h4>
<p>
<strong id="p-company">Podjetje d.o.o.</strong><br>
<span id="p-person"></span><br>
<span id="p-address"></span><br>
<span id="p-city"></span><br>
<span id="p-phone"></span><br>
<span id="p-email">mail@example.com</span><br>
<span id="p-tax"></span>
</p>
</div>

<div class="invoice-box">
<h4>Paket</h4>
<p><strong id="p-package">Start</strong></p>
</div>

</div>

<div class="invoice-box" style="margin-bottom:0;">
<h4>Opis storitev</h4>
<div id="p-desc">
<ul style="padding-left:18px;line-height:1.9;font-size:14px;"><li>—</li></ul>
</div>
</div>

<!-- SKUPAJ -->
<div class="summary-box">

<div class="summary-row">
<span>Način plačila</span>
<strong id="p-payment">Nakazilo na TRR</strong>
</div>

<div class="summary-row">
<span>DDV</span>
<strong>Ni zavezanec</strong>
</div>

<div class="summary-total">
<span>SKUPAJ (VKLJUČNO Z DDV)</span>
<h3 id="p-price">0 €</h3>
</div>

</div>

<!-- PLAČILNI PODATKI -->
<div style="margin-top:20px;background:#f0fdf4;border:1px solid #bbf7d0;padding:24px;border-radius:20px;">
<h4 style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:#16a34a;margin-bottom:12px;">Podatki za plačilo</h4>
<p style="font-size:13px;line-height:1.9;color:#0f172a;">
<strong>Prejemnik:</strong> Jure Pintar – PintSite<br>
<strong>TRR:</strong> SI56 XXXX XXXX XXXX XXX<br>
<strong>BIC/SWIFT:</strong> XXXXXXXX<br>
<strong>Namen:</strong> Plačilo računa <span id="p-invoice-ref"><?= $invoiceNumber ?></span><br>
<strong>Rok plačila:</strong> <?= $due ?>
</p>
</div>

<!-- FOOTER -->
<div class="invoice-footer">
<div>
<h4>PintSite – Jure Pintar</h4>
<p>info@pintsite.si | +386 XX XXX XXX</p>
<p style="margin-top:4px;font-size:12px;color:#64748b;">Hvala za vaše zaupanje!</p>
</div>
<div class="invoice-badge">
PintSite Invoice System
</div>
</div>

</div>

</div>

</div>

</div>

<script>
updatePreview();
</script>

</body>
</html>
