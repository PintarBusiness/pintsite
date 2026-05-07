<?php
require_once "config.php";

if(!isset($_SESSION["admin_logged"])){
    die("Ni dostopa.");
}

$company = $_POST["company"];
$email = $_POST["email"];
$package = $_POST["package"];
$price = $_POST["price"];
$invoice = $_POST["invoice"];
$description = nl2br($_POST["description"]);

$date = date("d.m.Y");
$due = date("d.m.Y", strtotime("+7 days"));

ob_start();

include $_SERVER['DOCUMENT_ROOT'] . "/admin/invoice_template.php";

$message = ob_get_clean();

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type:text/html;charset=UTF-8\r\n";
$headers .= "From: PintSite <info@pintsite.si>\r\n";

mail($email, "PintSite račun", $message, $headers);

header("Location: dashboard.php?success=1");
exit;
?>