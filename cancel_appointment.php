<?php
session_start();
include 'db_connection.php';

// التأكد من أن المستخدم مسجل دخوله
if (!isset($_SESSION['user_id'])) {
    header("Location: log_in.php");
    exit();
}

// التحقق من وجود معرّف الموعد في الرابط
if (isset($_GET['appointment_id'])) {
    $appointment_id = $_GET['appointment_id'];

    // استعلام لحذف الموعد من قاعدة البيانات
    $delete_sql = "DELETE FROM Appointment WHERE ID = ?";
    $stmt = mysqli_prepare($conn, $delete_sql);
    mysqli_stmt_bind_param($stmt, "i", $appointment_id); // ربط المعرّف مع الاستعلام
    $result = mysqli_stmt_execute($stmt); // تنفيذ الاستعلام
    mysqli_stmt_close($stmt);

    // التحقق من أن الحذف تم بنجاح
    if ($result) {
        // إعادة توجيه المريض إلى الصفحة الرئيسية بعد الحذف
        header("Location: Patient_homepage.php");
        exit();
    } else {
        echo "Error: Unable to cancel the appointment.";
    }
} else {
    echo "Appointment ID is missing.";
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        
    </body>
</html>
