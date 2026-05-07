<?php
require_once "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    if (
        $username === ADMIN_USER &&
        password_verify($password, ADMIN_PASS)
    ) {

        session_regenerate_id(true);
        $_SESSION["admin_logged"] = true;

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Napačni prijavni podatki.";
    }
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PintSite Admin</title>

<link rel="stylesheet" href="../style.css">
<link rel="stylesheet" href="admin.css">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

</head>

<body class="login-body">

<div class="login-card">

<div class="login-glow"></div>

<img src="../Logopravitext.png" class="admin-logo">

<h1>PintSite Admin</h1>

<p class="login-subtitle">
Premium nadzorna plošča za upravljanje računov in strank.
</p>

<?php if($error): ?>
<div class="admin-error">
<?= $error ?>
</div>
<?php endif; ?>

<form method="POST">

<div class="form-group">
<label>Uporabniško ime</label>
<input type="text" name="username" required>
</div>

<div class="form-group">
<label>Geslo</label>
<input type="password" name="password" required>
</div>

<button class="primary-btn">
Prijava v sistem
</button>

</form>

</div>

</body>
</html>
