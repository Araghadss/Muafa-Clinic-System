<?php
    ob_start();
    require_once 'db_connection.php';
    require_once 'checklogin.php';

    header('Content-Type: application/json');

    if($_SERVER['REQUEST_METHOD'] ==='POST'){
        $appointmentID = isset($_POST['id']) ? intval($_POST['id']) : 0;

        if($appointmentID > 0 ){
            $updateQuery = "UPDATE Appointment SET status='Confirmed' WHERE id='$appointmentID'";
            $result = mysqli_query($conn, $updateQuery);
            ob_clean();
            echo json_encode(['success' => (bool)$result]);
            exit();
        }else {
            echo json_encode(['success' => false, 'message' => 'invalid Appointment ID.']);
            exit(); 
            }
        }
    

    ob_clean();
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'method not allowed.']);
    exit(); 
