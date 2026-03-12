<?php
session_start();  

if (!isset($_SESSION['user_id'])) {
    header("Location: log_in.php");
    exit();
}

include 'db_connection.php';
include 'check_login.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['user_type'];

if ($role == 'patient') {
    $sql = "SELECT * FROM patient WHERE ID = ?";
} else {
    $sql = "SELECT * FROM doctor WHERE ID = ?";
}

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

$appointments_sql = "SELECT a.ID, d.firstName AS doctor_name, a.date AS appointment_date, a.time AS appointment_time, a.status, d.uniqueFileName 
                     FROM Appointment a 
                     JOIN Doctor d ON a.DoctorID = d.ID
                     WHERE a.PatientID = ?
                     ORDER BY a.date ASC, a.time ASC";
                     
$appointments_stmt = mysqli_prepare($conn, $appointments_sql);
mysqli_stmt_bind_param($appointments_stmt, "i", $user_id);
mysqli_stmt_execute($appointments_stmt);
$appointments_result = mysqli_stmt_get_result($appointments_stmt);
mysqli_stmt_close($appointments_stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Homepage</title>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- AJAX script for cancellation -->
<script>
$(document).ready(function(){
    $(".cancel-btn").click(function(e){
        e.preventDefault();

        let button = $(this);
        let appointmentId = button.data("id");

        if(confirm("Are you sure you want to cancel this appointment?")){
            $.ajax({
                url: 'cancel_appointment_ajax.php',
                type: 'POST',
                dataType: 'json',
                data: { appointment_id: appointmentId },
                success: function(response){
                    console.log("Success response:", response);
                    if(response.success){
                       $("#appointment-" + appointmentId).fadeOut(300, function(){ $(this).remove(); });

                    } else {
                        alert("Failed to cancel appointment.");
                    }
                }
               
            });
        }
    });
});

</script>
    <style>
      body{
            font-family: 'Poppins', sans-serif;
            margin:0;
            padding:0;
            background: url('images/clinc.png') no-repeat center center fixed;
            background-size: cover;
           
        }
        

        .welcome-message {
            background-color: rgba(100, 53, 32, 0.6);
            color: white;
            text-align: center;
            padding: 10px 20px;
            font-size: 15px;
            border-top: 2px solid rgba(255, 255, 255, 0.5);
            width: 60%;
            border-radius: 10px;
            margin: 20px auto; 
            display: flex;
            justify-content: center; 
            align-items: center; 

        }


        
        .navbar {
            width: 100%;
            margin: auto;
            padding: 10px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
			background-color: rgba(100, 53, 32, 0.5); /* 0.5 تعني شفافية 50% */
			font-family: 'Poppins', sans-serif;

			
        }

        .logo {
            width: 100px;
            margin-left:2em;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .navbar ul li {
            list-style: none;
            margin: 0 20px;
            position: relative;
        }

        .navbar ul li a {
            text-decoration: none;
            color: #fff;
            text-transform: uppercase;
			font-family: Arial, sans-serif;
        }

        .navbar ul li::after {
            content: '';
            height: 2px;
            width: 0%;
            background: #643520;
            position: absolute;
            left: 0;
            bottom: 0;
            transition: 0.5s;
        }

        .navbar ul li:hover::after {
            width: 100%;
        }
       .breadcrumbs {
    
            font-size: 14px;
        	margin-bottom:2em;
	        background-color: #faefe0;
            padding: 3px;
            border-radius: 5px;
	        display: inline-block; /* لجعلها في حجم مناسب */
        }

       .breadcrumbs a {
            text-decoration: none;
            color: #643520;
        }

        .breadcrumbs a:hover {
            text-decoration: underline;
        }

        .breadcrumbs::after {
            content: ' >';
        }

        .footer {
            background-color: rgba(100, 53, 32, 0.9); /* 50% شفافية */
            padding: 30px 50px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            border-top: 2px solid #ddd;
			font-family: 'Poppins', sans-serif;
			margin-top:2em;
        }

        .footer-column {
            flex: 1;
            min-width: 200px;
            padding: 2px;
        }

        .footer-column h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #FFFFFF;
			
			
        }

        .footer-column ul {
            list-style: none;
            padding: 0;
        }

        .footer-column ul li {
            margin-bottom: 8px;
			color: #FFFFFF;
        }

        .footer-column ul li a {
            text-decoration: none;
            color: #FFFFFF;
            font-size: 20px;
            transition: 0.3s;
        }

        .footer-column ul li a:hover {
            color: #FFD700; 
        }

        .footer-bottom {
            text-align: center;
            padding: 15px 0;
            font-size: 14px;
            color: #777;
            background-color: #faefe0;
        }

        

        main{
            padding:20px;
        }

      .paitent-info {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 30px;
    border-radius: 16px;
    max-width: 1000px;
    margin: 0 auto 30px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.paitent-info h2 {
    font-size: 26px;
    margin-bottom: 25px;
    color: #643520;
    font-weight: 700;
}

.paitent-info-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

.patient-card {
    background-color: #f7f4f0;
    padding: 18px 25px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    min-width: 220px;
    font-size: 16px;
    font-weight: 500;
    color: #333;
    transition: transform 0.3s ease;
}

.patient-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.1);
}

.patient-card strong {
    color: #643520;
}



        .appointments-card {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            justify-content: center; 
            align-items: stretch; 
        }

        @media (max-width: 768px) {
           .appointments-card {
                grid-template-columns: repeat(2, minmax(200px, 1fr)); /* صفين بدلًا من أن تصبح فوق بعضها */
             }
        }

        @media (max-width: 500px) {
           .appointments-card {
                grid-template-columns: 1fr; 
             }
        }
        
        .appointments{
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            padding: 25px;
            
        }

        .appointments a{
            color: #643520;
        }

        .appointments a:hover{
            color: #FFD700;
            text-decoration: underline;
        }


        .doctor-card{
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            padding: 15px;
            width: 280px;
            height: 280px;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            text-align: center;
        }

        .doctor-card img{
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: block;
            margin: 0 auto 10px;
        }

        .doctor-card h3{
            color: black;
            text-align: center;
            margin: 10px 0;
        }

        .doctor-card p{
            margin: 5px 0;
            text-align: center;
        }

        .cancel-link{
            color: #643520;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 5px;
        }

        .cancel-link:hover{
            text-decoration: underline;
            color: #FFD700;
        }

        .book-link{
            display: inline-block;
            margin-bottom: 20px;
            color: black;
            text-decoration: none;
            font-weight: bold;
        }

        .book-link:hover{
            text-decoration: underlines;
        }

    </style>
</head>

<body>
<div class="navbar">
    <img src="images/معافى.png" class="logo" alt="Logo">
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="#Quicklinks">Quick Links </a></li>
        <li><a href="#contact-us">Contact</a></li>
        <li><a href="logout.php">Sign out</a></li>
    </ul>
</div>

<div class="breadcrumbs">
    <a href="index.php">Home</a> &gt; 
    <a href="Patient_Homepage.php">Patient home page</a>
</div>

<div class="welcome-message">
    <h1>Welcome <?= htmlspecialchars($user['firstname']." ".$user['lastname']); ?></h1>
</div>

<main>
    <section class="paitent-info">
        <h2>Patient Information</h2>
        <div class="paitent-info-content">
            <div class="patient-card"><strong>Name:</strong> <?= htmlspecialchars($user['firstname']." ".$user['lastname']); ?></div>
            <div class="patient-card"><strong>ID:</strong> <?= htmlspecialchars($user['ID']); ?></div>
            <div class="patient-card"><strong>Gender:</strong> <?= htmlspecialchars($user['Gender']); ?></div>
            <div class="patient-card"><strong>DOB:</strong> <?= htmlspecialchars($user['DOB']); ?></div>
            <div class="patient-card"><strong>Email:</strong> <?= htmlspecialchars($user['emailAddress']); ?></div>
        </div>
    </section>

    <section class="appointments">
        <h2>Appointments</h2>
        <a href="Appointment.php" class="book-link">Book an appointment</a>
        <section class="appointments-card">
            <?php if (mysqli_num_rows($appointments_result) > 0): ?>
                <?php while ($appointment = mysqli_fetch_assoc($appointments_result)): ?>
                   <div class="doctor-card" id="appointment-<?= htmlspecialchars($appointment['ID']); ?>" data-id="<?= htmlspecialchars($appointment['ID']); ?>">

                        <img src="images/<?= htmlspecialchars($appointment['uniqueFileName']); ?>" alt="Doctor Photo">
                        <h3><?= htmlspecialchars($appointment['doctor_name']); ?></h3>
                        <p><strong>Time:</strong> <?= htmlspecialchars($appointment['appointment_time']); ?></p>
                        <p><strong>Date:</strong> <?= htmlspecialchars($appointment['appointment_date']); ?></p>
                        <?php
                        $status = htmlspecialchars($appointment['status']);
                        $status_color = 'gray';
                        if ($status == 'Confirmed') $status_color = 'green';
                        elseif ($status == 'Pending') $status_color = 'orange';
                        ?>
                        <p><strong>Status:</strong> <span style="color:<?= $status_color ?>"><?= $status ?></span></p>
                        <button class="cancel-btn" data-id="<?= htmlspecialchars($appointment['ID']); ?>">Cancel</button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color:red;">No appointments found.</p>
            <?php endif; ?>
        </section>
    </section>
</main>

<footer>
    <div class="footer">
        <div class="footer-column" id="Quicklinks">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </div>
        <div class="footer-column" id="contact-us">
            <h3>Contact us</h3>
            <ul>
                <li>📍 456 King Fahd St., Riyadh</li>
                <li>📞  (011) 123-4567</li>
                <li>✉  contact@mufaclinic.com</li>
                <li>🌐 www.mufaclinic.com</li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        © 2025 Muafa Clinic - All Rights Reserved
    </div>
</footer>


</body>
</html>