
<?php

    session_start();
    require_once 'db_connection.php';
    require_once 'doctor_checklogin.php';
    
    
    $doctorID = $_SESSION['user_id'];
      $sql ="SELECT Doctor.id, Doctor.firstName, Doctor.lastName, Doctor.emailAddress, Speciality.speciality, Doctor.uniqueFileName "
            . "FROM Doctor JOIN Speciality ON Doctor.SpecialityID = Speciality.id "
            . "WHERE Doctor.id = $doctorID";
    
    $result = mysqli_query($conn, $sql);
    $doctor = mysqli_fetch_assoc($result);
    
    
    $upcomingQuery = "
    SELECT 
        Appointment.id AS appointment_id,
        Appointment.date, Appointment.time, Appointment.status, Appointment.reason,
        Patient.id as patient_id ,Patient.firstName, Patient.lastName, Patient.gender, 
        TIMESTAMPDIFF(YEAR, Patient.DoB, CURDATE()) AS age
    FROM Appointment
    JOIN Patient ON Appointment.PatientID = Patient.id
    WHERE Appointment.DoctorID = '$doctorID' AND (Appointment.status = 'Pending' OR Appointment.status = 'Confirmed')
    ORDER BY Appointment.date ASC, Appointment.time ASC
    ";
    $upcomingResult = mysqli_query($conn, $upcomingQuery);
    if (!$upcomingResult) {
    die("Query failed: " . mysqli_error($conn));
}
    $patientsQuery = "
    SELECT DISTINCT 
        Patient.id as patient_id, Patient.firstName, Patient.lastName, Patient.gender, Patient.DoB,
        TIMESTAMPDIFF(YEAR, Patient.DoB, CURDATE()) AS age
    FROM Appointment
    JOIN Patient ON Appointment.PatientID = Patient.id
    WHERE Appointment.DoctorID = '$doctorID' AND Appointment.status = 'Done'
    ORDER BY Patient.firstName ASC
";
$patientsResult = mysqli_query($conn, $patientsQuery);
if (!$patientsResult) {
    die("Query failed: " . mysqli_error($conn));
}
$confirmedPatientsQuery = "
    SELECT DISTINCT 
        Patient.id as patient_id, Patient.firstName, Patient.lastName, Patient.gender, Patient.DoB
    FROM Appointment
    JOIN Patient ON Appointment.PatientID = Patient.id
    WHERE Appointment.DoctorID = '$doctorID' AND Appointment.status = 'Done'
    ORDER BY Patient.firstName ASC
";

$confirmedPatientsResult = mysqli_query($conn, $confirmedPatientsQuery);
if (!$confirmedPatientsResult) {
    die("Query failed: " . mysqli_error($conn));
}
  



?>
<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width , initial-scale=1"  >

			<title>Doctor homepage</title>
			<link rel="stylesheet" href="css.css" >
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

			<style>
				body{
					background: url('images/wallpaper2.png') no-repeat center center fixed;
					background-size: cover;
					font-family:'Times New Roman', Times, serif;	
				}
				p{
					text-align: center;

				}
				table{
					margin: 1.5em auto;
					border-collapse: collapse;
					box-shadow: 0 0.125em 0.3125em rgba(0, 0, 0, 0.1);
					width: 80%;
					max-width: 1200px;
					border-radius: 8px;
					overflow: hidden;
					background-color: rgba(255, 255, 255, 0.8);
				}
				
				th, td{
					padding: 20px;
					text-align: center;
					font-size: 18px;
				}
				th{
					background-color:#8e6447;
					color: white
				}
				tr:hover{
				
					background-color:rgba(100, 53, 32, 0.5);
					
				}
				#Docname{
				
					gap: 1.25em;
					width: 60%;
					max-width: 600px;
					box-shadow: 0 0.125em 0.3125em rgba(0, 0, 0, 0.1);
					border-radius: 4%;
					background-color: rgba(100, 53, 32, 0.5);
					padding:10px;
					margin: 1.25em auto 0  auto;

				}
				#Docname h1{

					text-decoration: none;
					color: #fff;

				}
								
				#DocContainer{
					display: flex;
					align-items:center;
					justify-content:left;
					gap: 1.25em;
					width: 60%;
					max-width: 600px;
					box-shadow: 0 0.125em 0.3125em rgba(0, 0, 0, 0.1);
					margin: 0 auto ;
					background-color: rgba(255, 255, 255, 0.8);
					padding:10px;			
				}
			
				
				#doc_pic{
					margin-right: 2.5em;
					margin-left: 2.5em;
					margin-bottom: 1.5em;
					border-radius: 50%;
					height: 9.375em; 
					width: 9.375em;
					border: 1px solid #643520;
					box-shadow: 0 0.125em 0.3125em rgba(0, 0, 0, 0.1);
					
				
				}
				ul{
					list-style:none;
					padding: 0;
					margin: 0;
				}

				#Docinfo{
					padding-left: 0;
					list-style:none;
				}
				#Docinfo li{
					margin-bottom: 10px;
					font-size: 18px;
					font-weight: lighter;
					
				}
				#Table-title{
				
					display: flex;
					align-items: center;
					justify-content: center;
					gap: 1.25em;
					width: 50%;
					max-width: 400px;
					box-shadow: 0 0.125em 0.3125em rgba(0, 0, 0, 0.1);
					border-radius: 4%;
					margin: 1.25em auto 3.125em auto;
					background-color: rgba(255, 255, 255, 0.8);
					padding:10px;
					
				}
				#Table-title p {
					font-size: 24px;
					font-weight: bold;
					text-align: center;
				}
				
				td a {
					text-decoration: none;
					color: #643520;
					text-transform: uppercase;
					font-family: Arial, sans-serif;
				}
				td a:hover {
					text-decoration: underline;
				}
                                
                              

			 </style>
                           
			
		</head>
		
		<body>
                    
			<header>
				<div class="navbar">
					<img src="images\معافى.png" class="logo" alt="Logo">
					<ul>
						<li><a href="index.php">Home</a></li>
						<li><a href="#Quicklinks">Quick Links </a></li>
						<li><a href="#contact-us">Contact</a></li>
						<li><a href="logout.php">Sign out</a></li>

					</ul>
				</div>
		
				<div class="breadcrumbs">
					<a href="index.php">Home</a> &gt; 
					<a href="Doctor_homepage.php">Doctor Homepage </a>
				</div>
			
                        </header>
			<div id='Docname'>
                            <h1>Welcome Dr.<?php echo htmlspecialchars($doctor['firstName'].
                                    " " . $doctor['lastName']);?></h1>
			</div>
				   
			<div id='DocContainer'>
				
                            <img id='doc_pic' src= "<?php echo !empty($doctor['uniqueFileName']) ? 'images/' . htmlspecialchars($doctor['uniqueFileName']) : 'images/default-doctor.png'; ?> "  alt="doctor photo">
				<div id='Docinfo'>
                                    <ul>
					<li><h3>Doctor Information:</h3></li>
					<li>Name:<?php echo htmlspecialchars($doctor['firstName']. " " . $doctor['lastName']);?></li>
					<li>ID: <?php echo htmlspecialchars($doctor['id']);?></li>
					<li>Speciality:<?php echo htmlspecialchars($doctor['speciality']);?></li>							
					<li>Email: <?php echo htmlspecialchars($doctor['emailAddress']);?></li>
                                    </ul>
                                </div>
			</div>  

		<main>
				
                    <hr style="margin-top: 1.25em;
			margin-bottom: 1.25em;
			height: 0.3125em;
			background-color: rgba(100, 53, 32, 0.9);
			width:100%;">
			   
                    <br>
			
                    <div id='Table-title'>
                        
			<p >Upcoming Appointments</p>
                    </div>
			<table style="width:100%">
                            <tr>
				<th>Date</th>
				<th>Time</th>
				<th>Patient's Name</th>
				<th>Age</th>
				<th>Gender</th>
				<th>Reason for visit</th>
				<th>Status</th>			
                            </tr>
                            <?php while($row =mysqli_fetch_assoc($upcomingResult)): ?>
                            <tr>
				<td><?php echo htmlspecialchars($row['date']);?></td>
				<td><?php echo htmlspecialchars($row['time']);?></td>
				<td><?php echo htmlspecialchars($row['firstName']. " " . $row['lastName']);?></td>
				<td><?php echo htmlspecialchars($row['age']);?></td>
				<td><?php echo htmlspecialchars($row['gender']);?></td>
				<td><?php echo htmlspecialchars($row['reason']);?></td>
                                <td class="appointment-status">
                                <?php echo htmlspecialchars($row['status']);?></td>

				<td>
                                    <?php if ($row['status'] ==='Pending'): ?>
                                    <button class="confirm-btn"
                                        data-id="<?= $row['appointment_id'] ?>"
                                        data-patient="<?= $row['patient_id'] ?>" >
                                        Confirm
                                    </button>
                                        <?php elseif ($row['status'] === 'Confirmed'): ?>
                                            <a href="prescribeMed.php?id=<?= $row['appointment_id'] ?>&patient_id=<?= $row['patient_id'] ?>" >Prescribe</a>
                                
                                    <?php endif; ?>
                                </td>
                            </tr>							
                            <?php endwhile; ?>
			</table>
                    <br>
                    <div id='Table-title'>
			<p>Your Patients</p>
                    </div>
                    
                    <table style="width:100%">
			<tr>
                            <th>Name </th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Medications</th>
                            
			</tr>
                        
                         <?php while($patient =mysqli_fetch_assoc($confirmedPatientsResult)): ?>
                            <tr>
				<td><?php echo htmlspecialchars($patient['firstName']. " " . $patient['lastName']);?></td>
				<td><?php echo AgeCalculation($patient['DoB']);?></td>
				<td><?php echo htmlspecialchars($patient['gender']);?></td>
				<td>
                                    <?php
                                    $currentPatientID = $patient['patient_id'];
                                    $getMedicationsQuery = "
                                        SELECT Medication.MedicationName
                                        FROM Medication
                                        JOIN Prescription ON Medication.ID = Prescription.MedicationID
                                        JOIN Appointment ON Prescription.AppointmentID = Appointment.ID
                                        WHERE Appointment.PatientID = '$currentPatientID' AND Appointment.status = 'Done'
                                    ";
                                    $medResult = mysqli_query($conn, $getMedicationsQuery);
                                    if ($medResult && mysqli_num_rows($medResult) > 0) {
                                        while ($med = mysqli_fetch_assoc($medResult)) {
                                            echo htmlspecialchars($med['MedicationName']) . "<br>";
                                        }
                                    } else {
                                        echo "No medications";
                                    }
                                    ?>
                                </td>

                                
                            </tr>							
                            <?php endwhile; ?>
			</table>
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
                <script>
                    document.addEventListener('DOMContentLoaded', function(){
                        document.querySelectorAll('.confirm-btn').forEach(button => {
                            button.addEventListener('click', function(){
                                const appointmentId = this.dataset.id;
                                const patientId = this.dataset.patient;
                                const row = this.closest('tr');
                                fetch('confirm_appointment.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded'
                                    },
                                    body: `id=${encodeURIComponent(appointmentId)}`
                                })
                                .then(response => {
                                    if(!response.ok) throw new Error('Network response error');
                                    return response.json();
                                })
                                .then(data => {
                                    if(data.success){
                                        const statusCell = row.querySelector('.appointment-status');
                                        if (statusCell){
                                            statusCell.textContent = 'Confirmed';
                                        }
                                        this.outerHTML = `<a href="prescribeMed.php?id=${appointmentId}&patient_id=${patientId}">Prescribe</a>`;
                                    }else {
                                        alert('Failed to confirm appointment: ' + data.message);
                                    }
                                })
                                .catch (err => {
                                    concole.error('AJAX ferching error: ' , err);
                                    alert('AJAX error !');
                                });
                            });
                        });
                    });
                    </script>

		</body>
	</html>
        
        <?php function AgeCalculation($dob){
            if (!$dob) return "Unknown";
                try {
                    $dob = new DateTime($dob);
                    $now = new DateTime();
                    $age = $now->diff($dob);
                    return $age->y;
                } catch (Exception $e) {
                    return "Invalid";
                }
            }
        ?>