<?php
session_start();

define("ADMIN_USER", "info@pintsite.si");

/*
GENERIRAJ HASH:
<?php echo password_hash("TVOJE_GESLO", PASSWORD_DEFAULT); ?>
*/

define("ADMIN_PASS", password_hash("1234", PASSWORD_DEFAULT));

define("MAIL_FROM", "info@pintsite.si");
?>
