<!DOCTYPE html>
<html lang="sl">
<head>
<meta charset="UTF-8">
<style>

body{
font-family:Arial,sans-serif;
background:#f4f8ff;
padding:30px;
}

.invoice{
max-width:760px;
margin:auto;
background:white;
border-radius:28px;
overflow:hidden;
box-shadow:0 20px 60px rgba(0,0,0,.08);
}

.header{
background:linear-gradient(135deg,#2563eb,#1d4ed8);
padding:40px;
color:white;
}

.top{
display:flex;
justify-content:space-between;
align-items:flex-start;
}

.logo{
height:55px;
filter:brightness(0) invert(1);
}

.header-right h2{
font-size:32px;
margin-bottom:10px;
letter-spacing:2px;
}

.header-right p{
margin-bottom:4px;
font-size:14px;
opacity:.9;
}

.header-left p{
font-size:14px;
opacity:.9;
margin-bottom:3px;
}

.header-left strong{
font-size:16px;
}

.content{
padding:40px;
}

.info-grid{
display:grid;
grid-template-columns:1fr 1fr;
gap:20px;
margin-bottom:20px;
}

.box{
background:#f8fbff;
padding:24px;
border-radius:20px;
}

.box h3{
font-size:11px;
text-transform:uppercase;
letter-spacing:1px;
color:#64748b;
margin-bottom:12px;
}

.box p{
font-size:14px;
line-height:1.8;
color:#0f172a;
}

.box ul{
padding-left:18px;
line-height:1.9;
font-size:14px;
color:#0f172a;
}

.summary{
margin-top:20px;
background:linear-gradient(135deg,#eff6ff,#dbeafe);
padding:28px;
border-radius:24px;
}

.summary-row{
display:flex;
justify-content:space-between;
margin-bottom:12px;
font-size:14px;
}

.summary-total{
display:flex;
justify-content:space-between;
align-items:center;
padding-top:18px;
border-top:1px solid rgba(15,23,42,.1);
margin-top:8px;
}

.summary-total span{
font-size:13px;
color:#64748b;
text-transform:uppercase;
letter-spacing:1px;
}

.price{
font-size:34px;
font-weight:bold;
color:#2563eb;
}

.payment-box{
margin-top:20px;
background:#f0fdf4;
border:1px solid #bbf7d0;
padding:24px;
border-radius:20px;
}

.payment-box h3{
font-size:11px;
text-transform:uppercase;
letter-spacing:1px;
color:#16a34a;
margin-bottom:12px;
}

.payment-box p{
font-size:14px;
line-height:1.9;
color:#0f172a;
}

.footer{
padding:28px 40px;
border-top:1px solid rgba(0,0,0,.08);
display:flex;
justify-content:space-between;
align-items:center;
font-size:13px;
color:#64748b;
}

.footer-left p{
margin-bottom:3px;
}

.badge{
padding:10px 16px;
border-radius:999px;
background:#dbeafe;
font-size:12px;
font-weight:800;
color:#1d4ed8;
}

</style>
</head>
<body>

<div class="invoice">

<div class="header">
<div class="top">

<div class="header-left">
<img src="https://pintsite.si/Logopravitext.png" class="logo"><br><br>
<strong>PintSite</strong><br>
<p>Jure Pintar</p>
<p>info@pintsite.si</p>
<p>+386 XX XXX XXX</p>
</div>

<div class="header-right" style="text-align:right;">
<h2>RAČUN</h2>
<p>Št: <?= htmlspecialchars($invoice) ?></p>
<p>Datum: <?= htmlspecialchars($date) ?></p>
<p>Rok plačila: <?= htmlspecialchars($due) ?></p>
<br>
<p style="opacity:.7;font-size:13px;">Status: <strong>ČAKA NA PLAČILO</strong></p>
</div>

</div>
</div>

<div class="content">

<div class="info-grid">

<div class="box">
<h3>Stranka</h3>
<p>
<strong><?= htmlspecialchars($company) ?></strong><br>
<?= $person ? htmlspecialchars($person).'<br>' : '' ?>
<?= $address ? htmlspecialchars($address).'<br>' : '' ?>
<?= $city ? htmlspecialchars($city).'<br>' : '' ?>
<?= $phone ? htmlspecialchars($phone).'<br>' : '' ?>
<?= htmlspecialchars($email) ?><br>
<?= $tax ? 'DDV: '.htmlspecialchars($tax) : '' ?>
</p>
</div>

<div class="box">
<h3>Paket</h3>
<p><strong><?= htmlspecialchars($package) ?></strong></p>
</div>

</div>

<div class="box">
<h3>Opis storitev</h3>
<?= $description ?>
</div>

<div class="summary">
<div class="summary-row">
<span>Način plačila</span>
<strong><?= htmlspecialchars($payment) ?></strong>
</div>
<div class="summary-row">
<span>DDV</span>
<strong>Ni zavezanec</strong>
</div>
<div class="summary-total">
<span>Skupaj (z DDV)</span>
<div class="price"><?= htmlspecialchars($price) ?> €</div>
</div>
</div>

<div class="payment-box">
<h3>Podatki za plačilo</h3>
<p>
<strong>Prejemnik:</strong> Jure Pintar – PintSite<br>
<strong>TRR:</strong> SI56 XXXX XXXX XXXX XXX<br>
<strong>BIC/SWIFT:</strong> XXXXXXXX<br>
<strong>Namen:</strong> Plačilo računa <?= htmlspecialchars($invoice) ?><br>
<strong>Rok plačila:</strong> <?= htmlspecialchars($due) ?>
</p>
</div>

</div>

<div class="footer">
<div class="footer-left">
<p><strong>PintSite – Jure Pintar</strong></p>
<p>info@pintsite.si | +386 XX XXX XXX</p>
<p>Hvala za vaše zaupanje!</p>
</div>
<div class="badge">PintSite Invoice System</div>
</div>

</div>

</body>
</html>
