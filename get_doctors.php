<?php
require_once 'db_connection.php';

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (ob_get_length()) ob_clean();
if (!isset($_GET['speciality_id'])) {
    echo json_encode(['error' => 'Missing speciality_id']);
    exit;
}

$speciality_id = intval($_GET['speciality_id']);
$query = "SELECT firstName, lastName FROM doctor WHERE specialityid = $speciality_id";
$result = mysqli_query($conn, $query);

$doctors = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $doctors[] = $row['firstName'] . ' ' . $row['lastName'];
    }
}

echo json_encode($doctors);

exit;