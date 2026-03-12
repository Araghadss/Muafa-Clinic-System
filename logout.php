<?php
session_start();
session_unset(); // يمسح كل متغيرات السيشن
session_destroy(); // يدمر السيشن نهائيًا
header("Location: index.php"); // يرجع المستخدم للصفحة الرئيسية
exit;
?>
