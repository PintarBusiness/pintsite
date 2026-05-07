<?php
require_once "config.php";

if(!isset($_SESSION["admin_logged"])) {
    header("Location: admin.php");
    exit;
}

$today = date("d.m.Y");
$due = date("d.m.Y", strtotime("+7 days"));
?>

<!DOCTYPE html>
<html lang="sl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>PintSite Admin Panel</title>

<link rel="stylesheet" href="../style.css">
<link rel="stylesheet" href="admin.css">

<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<script>
function updatePreview() {

    document.getElementById("p-company").innerText =
        document.getElementById("company").value;

    document.getElementById("p-email").innerText =
        document.getElementById("email").value;

    document.getElementById("p-package").innerText =
        document.getElementById("package").value;

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

<div class="dashboard-wrap">

<div class="dashboard-left">

<div class="dashboard-top">
    <img src="../Logopravitext.png" class="dash-logo">

    <a href="logout.php" class="logout-btn">
        Odjava
    </a>
</div>

<div class="dashboard-card">

<h2>Ustvari račun</h2>

<form action="send_invoice.php" method="POST">

<div class="form-group">
<label>Ime podjetja / stranke</label>
<input type="text" name="company" id="company" oninput="updatePreview()" required>
</div>

<div class="form-group">
<label>Email stranke</label>
<input type="email" name="email" id="email" oninput="updatePreview()" required>
</div>

<div class="form-group">
<label>Paket</label>

<select name="package" id="package" oninput="updatePreview()">
    <option>Start</option>
    <option>Growth</option>
    <option>Premium</option>
    <option>Prilagojen</option>
</select>

</div>

<div class="form-group">
<label>Cena (€)</label>
<input type="number" name="price" id="price" oninput="updatePreview()" required>
</div>

<div class="form-group">
<label>Opis storitev (vsaka vrstica = alineja)</label>

<textarea
name="description"
id="description"
rows="8"
oninput="updatePreview()"></textarea>

</div>

<button class="admin-btn">
Pošlji račun
</button>

</form>

</div>
</div>

<div class="dashboard-right">

<div class="invoice-preview">
<p id="p-company">Podjetje d.o.o.</p>
<p id="p-email">mail@example.com</p>
</div>

<div class="invoice-box">
<h4>Storitev</h4>
<p id="p-package">Start paket</p>
<p>Izdelava moderne spletne strani</p>
</div>

</div>

<div class="invoice-box">
<h4>Kaj vključuje paket</h4>
<ul id="p-desc">
<li>Moderen premium dizajn</li>
<li>Responsive optimizacija</li>
<li>SEO osnova</li>
</ul>
</div>

<div class="invoice-summary">

<div class="summary-row">
<span>Status računa</span>
<span>Neplačano</span>
</div>

<div class="summary-row">
<span>Način plačila</span>
<span>Nakazilo na TRR</span>
</div>

<div class="summary-row">
<span>Paket</span>
<span id="p-package">Start</span>
</div>

<div class="summary-total">
<span>SKUPAJ Z DDV</span>
<strong id="p-price">0 €</strong>
</div>

</div>

<div class="invoice-footer">

<div class="footer-note">
Hvala za vaše zaupanje. Za dodatna vprašanja ali podporo smo vam vedno na voljo na info@pintsite.si.
</div>

<div class="payment-status">
ČAKA NA PLAČILO
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
