<?php
session_start();
if ($_POST['loggaut']) {
    session_start();
    session_destroy();
}
if (isset($_SESSION["username"]) && !empty($_SESSION["username"])) {
    echo "Du är inloggad som " . $_SESSION["username"];
} else {
    echo "Du är inte inloggad.";
}
