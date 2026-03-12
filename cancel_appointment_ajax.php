<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'not_logged_in']);
    exit();
}

include 'db_connection.php';

if (!isset($_POST['appointment_id'])) {
    echo json_encode(['success' => false, 'error' => 'missing_appointment_id']);
    exit();
}
if (ob_get_length()) ob_clean();
$appointment_id = $_POST['appointment_id'];
$patient_id = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM Appointment WHERE ID = ? AND PatientID = ?");
$stmt->bind_param("ii", $appointment_id, $patient_id);
$stmt->execute();

echo json_encode(['success' => $stmt->affected_rows > 0]);

$stmt->close();
$conn->close();
exit();
