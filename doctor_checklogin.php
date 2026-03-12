<?php

    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    
    if(!isset($_SESSION['user_id'])){
        if(
                (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'],
                        'application/json') !== false) ||
                (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')){
                    http_response_code(401);
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
                    exit(); 

                }else{
                    header("Location: log_in.php");
                    exit();
                }                         
    }