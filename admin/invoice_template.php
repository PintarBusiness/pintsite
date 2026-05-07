<!DOCTYPE html>
<html lang="sl">
<head>
<meta charset="UTF-8">

<style>

body{
font-family:Arial;
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

.content{
padding:40px;
}

.box{
background:#f8fbff;
padding:24px;
border-radius:20px;
margin-bottom:24px;
}

.total{
margin-top:35px;
padding:30px;
background:#eff6ff;
border-radius:24px;
}

.price{
font-size:36px;
font-weight:bold;
color:#2563eb;
}

.footer{
padding:30px 40px;
border-top:1px solid rgba(0,0,0,.08);
font-size:14px;
color:#64748b;
}

</style>

</head>

<body>

<div class="invoice">

<div class="header">

<div class="top">

<img src="https://pintsite.si/Logopravitext.png" class="logo">

<div>

<div style="margin-bottom:15px;">
<strong>PintSite</strong><br>
Jure Pintar<br>
Spletne rešitve
</div>

<h2>RAČUN</h2>

<p>Št: <?= $invoice ?></p>
<p>Datum: <?= $date ?></p>
<p>Rok plačila: <?= $due ?></p>

</div>

</div>

</div>

<div class="content">

<div class="box">
<h3>Stranka</h3>
<p><?= $company ?></p>
<p><?= $email ?></p>
</div>

<div class="box">
<h3>Paket</h3>
<p><?= $package ?></p>
</div>

<div class="box">
<h3>Opis storitev</h3>
<?= $description ?>
</div>

<div class="total">
<p>Skupni znesek</p>
<div class="price">
<?= $price ?> €
</div>
</div>

</div>

<div class="footer">

<strong>PintSite – Jure Pintar</strong><br>
Email: info@pintsite.si<br>
Telefon: +386 XX XXX XXX<br><br>

<strong>Podatki za plačilo:</strong><br>
TRR: SI56 XXXX XXXX XXXX XXX<br>
Način plačila: Nakazilo na TRR ali gotovina<br><br>

Hvala za vaše zaupanje. PintSite — moderna izdelava spletnih strani.

</div>

</div>

</body>
</html>
