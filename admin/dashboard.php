<?php
require_once "config.php";

if(!isset($_SESSION["admin_logged"])){
    header("Location: admin.php");
    exit;
}

$today = date("d.m.Y");
$due = date("d.m.Y", strtotime("+7 days"));
$invoiceNumber = "PS-" . date("Y") . "-" . rand(100,999);
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

document.getElementById("p-company").innerText =
document.getElementById("company").value;

document.getElementById("p-person").innerText =
document.getElementById("person").value;

document.getElementById("p-address").innerText =
document.getElementById("address").value;

document.getElementById("p-city").innerText =
document.getElementById("city").value;

document.getElementById("p-phone").innerText =
document.getElementById("phone").value;

document.getElementById("p-tax").innerText =
document.getElementById("tax").value;

document.getElementById("p-email").innerText =
document.getElementById("email").value;

document.getElementById("p-package").innerText =
document.getElementById("package").value;

document.getElementById("p-payment").innerText =
document.getElementById("payment").value;

document.getElementById("p-price").innerText =
document.getElementById("price").value + " €";

document.getElementById("p-desc").innerHTML =
document.getElementById("description").value
.split("\n")
.map(x => "<li>" + x + "</li>")
.join("");

}

</script>

</head>

<body class="dashboard-body">

<div class="dashboard-layout">

<div class="sidebar">

<div class="sidebar-top">

<img src="../Logopravitext.png" class="dash-logo">

<a href="logout.php" class="logout-btn">
Odjava
</a>

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

<div class="preview-side">

<div class="invoice-preview">

<div class="invoice-header">

<div class="invoice-top">

<img src="../Logopravitext.png">

<div class="invoice-title">

<h2>RAČUN</h2>

<div class="invoice-meta">
<p><?= $invoiceNumber ?></p>
<p>Datum: <?= $today ?></p>
<p>Rok plačila: <?= $due ?></p>
</div>

</div>

</div>

</div>

<div class="invoice-content">

<div class="invoice-grid">

<div class="invoice-box">

<h4>Stranka</h4>

<p id="p-company">Podjetje d.o.o.</p>

<p id="p-person">Marko Novak</p>

<p id="p-address">Dunajska cesta 1</p>

<p id="p-city">1000 Ljubljana</p>

<p id="p-phone">+386 41 123 456</p>

<p id="p-email">mail@example.com</p>

<p id="p-tax">SI12345678</p>

</div>

<div class="invoice-box">

<h4>Paket</h4>

<p id="p-package">Premium</p>

</div>

</div>

<div class="invoice-box">

<h4>Opis storitev</h4>

<ul id="p-desc">
<li>Moderna premium spletna stran</li>
<li>Responsive optimizacija</li>
<li>SEO osnova</li>
</ul>

</div>

<div class="summary-box">



<div class="summary-row">
<span>Status</span>
<strong>ČAKA NA PLAČILO</strong>
</div>

<div class="summary-row">
<span>Način plačila</span>
<strong id="p-payment">Nakazilo na TRR</strong>
</div>

<div class="summary-total">

<span>SKUPAJ (VKLJUČNO Z DDV)</span>

<h3 id="p-price">0 €</h3>

</div>

</div>

<div class="invoice-footer">

<div>
<h4>PintSite</h4>
<p>info@pintsite.si</p>
</div>

<div class="invoice-badge">
Premium invoice system
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
