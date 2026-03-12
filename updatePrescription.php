<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointmentID = $_POST['appointment_id'];
    $medications = $_POST['medications'] ?? [];

    if (empty($medications)) {
        die("No medications selected.");
    }

    foreach ($medications as $medName) {
        // احصل على MedicationID من جدول Medication
        $stmt = $conn->prepare("SELECT id FROM Medication WHERE MedicationName = ?");
        $stmt->bind_param("s", $medName);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $medID = $row['id'];

            // تحقق إذا كان الوصفة موجودة مسبقاً لتجنب التكرار (اختياري)
            $checkStmt = $conn->prepare("SELECT id FROM Prescription WHERE AppointmentID = ? AND MedicationID = ?");
            $checkStmt->bind_param("ii", $appointmentID, $medID);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows === 0) {
                // أضف إلى جدول Prescription
                $insertStmt = $conn->prepare("INSERT INTO Prescription (AppointmentID, MedicationID) VALUES (?, ?)");
                $insertStmt->bind_param("ii", $appointmentID, $medID);
                $insertStmt->execute();
            }
        }
    }
    
    $updateStatus = "UPDATE Appointment SET status = 'DONE' WHERE id = ?";
        $statusStmt = mysqli_prepare($conn, $updateStatus);
        mysqli_stmt_bind_param($statusStmt, "i", $appointmentID);
        mysqli_stmt_execute($statusStmt);

   
    header("Location: Doctor_homepage.php");
     exit;
}
?>
