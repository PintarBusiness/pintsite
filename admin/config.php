<?php
session_start();

define("ADMIN_USER", "info@pintsite.si");
define("ADMIN_PASS", "$2y$10$1jfqtvXqd5k3FmmL6OYXb.7QfLZpGsbSv8AXVMorYTCEyb4PPuzXO");
define("MAIL_FROM",  "info@pintsite.si");

define("COUNTER_FILE", __DIR__ . "/invoice_counter.txt"); // pot je relativna na admin/ mapo

function getNextInvoiceNumber(): string {
    $file = COUNTER_FILE;
    // flock zagotovi da dva hkratna requesta ne dobita iste številke
    $fp = fopen($file, "r+");
    flock($fp, LOCK_EX);
    $current = (int) trim(fread($fp, 20));
    $next = $current + 1;
    rewind($fp);
    fwrite($fp, $next);
    ftruncate($fp, ftell($fp));
    flock($fp, LOCK_UN);
    fclose($fp);
    return "PS-" . date("Y") . "-" . str_pad($next, 4, "0", STR_PAD_LEFT);
}

function peekInvoiceNumber(): string {
    $current = (int) trim(file_get_contents(COUNTER_FILE));
    $next = $current + 1;
    return "PS-" . date("Y") . "-" . str_pad($next, 4, "0", STR_PAD_LEFT);
}
?>
