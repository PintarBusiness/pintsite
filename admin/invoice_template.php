<!DOCTYPE html>
<html lang="sl">
<head>
<meta charset="UTF-8">

<style>
body{
font-family: Arial;
background:#f8fbff;
padding:40px;
}

.invoice{
max-width:700px;
margin:auto;
background:white;
border-radius:20px;
padding:40px;
}

.top{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:40px;
}

.logo{
height:60px;
}

.box{
margin-bottom:30px;
}

.price{
font-size:32px;
font-weight:bold;
color:#2563eb;
}

ul{
padding-left:20px;
}
</style>

</head>

<body>

<div class="invoice">

<div class="top">

<img src="https://pintsite.si/Logopravitext.png" class="logo">

<div>
<h2>RAČUN</h2>
<p>Datum: <?= $date ?></p>
<p>Rok plačila: <?= $due ?></p>
</div>

</div>

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

<div class="price">
<?= $price ?> €
</div>

</div>

</body>
</html>
