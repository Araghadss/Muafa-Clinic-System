<?php

if(session_status() === PHP_SESSION_NONE){
session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['user_type'] == 'doctor' && basename($_SERVER['PHP_SELF']) != 'Doctor_Homepage.php') {
    header("Location: Doctor_Homepage.php");
    exit();
}

if ($_SESSION['user_type'] == 'patient' && basename($_SERVER['PHP_SELF']) != 'Patient_Homepage.php') {
    header("Location: Patient_Homepage.php");
    exit();
}
?>
