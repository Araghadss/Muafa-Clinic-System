<?php
session_start();  

error_reporting(E_ALL);
ini_set('log_errors', '1');
ini_set('display_errors', '1');

require_once 'db_connection.php';
require_once 'checklogin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['bookingSubmit'])) {
        $speciality = $_POST['Speciality'] ?? '';
        $doctor = $_POST['Doctor'] ?? '';
        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '';
        $reason = $_POST['reason'] ?? '';
        $patient_id = $_SESSION['user_id'];

        if (empty($speciality) || empty($doctor) || empty($date) || empty($time) || empty($reason)) {
            die("All the fields are required.");
        }

        $doctor_name = mysqli_real_escape_string($conn, $doctor);
        $sql = "SELECT id FROM Doctor WHERE CONCAT(firstName, ' ', lastName) = '$doctor_name'";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Error finding doctor ID: " . mysqli_error($conn));
        }

        $doctor_row = mysqli_fetch_assoc($result);
        if (!$doctor_row) {
            die("Invalid Doctor.");
        }

        $doctor_id = $doctor_row['id'];
        $date = mysqli_real_escape_string($conn, $date);
        $time = mysqli_real_escape_string($conn, $time);
        $reason = mysqli_real_escape_string($conn, $reason);

        $sql = "INSERT INTO Appointment(PatientID, DoctorID, date, time, reason, status)
                VALUES ('$patient_id', '$doctor_id' , '$date', '$time', '$reason','Pending')";

        $result = mysqli_query($conn, $sql);
        if ($result) {
            header("Location: Patient_Homepage.php?success=1");
            exit();
        } else {
            die("Error in booking an appointment: " . mysqli_error($conn));
        }
    }
}

$specialtiesQuery = "SELECT id, speciality FROM Speciality";
$specialtiesResult = mysqli_query($conn, $specialtiesQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width , initial-scale=1">
    <title>Appointment Booking</title>
    <link rel="stylesheet" href="css.css">
    <style>
        input, select {
            width: 90%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 55px;
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>
<header>
    <div class="navbar">
        <img src="images/معافى.png" class="logo" alt="Logo">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="#Quicklinks">Quick Links</a></li>
            <li><a href="#contact-us">Contact</a></li>
            <li><a href="logout.php">Sign out</a></li>
        </ul>
    </div>

    <div class="breadcrumbs">
        <a href="index.php">Home</a> &gt;
        <a href="Patient_Homepage.php">Patient home page &gt;</a>
        <a href="Appointment.php">Appointment Booking</a>
    </div>
</header>

<main>
    <div class="container">
        <h1>Book an Appointment</h1><br>

        <form id="bookingform" method="post">
            <div class="form-column1">
                <label for="Speciality">Select Speciality:</label>
                <select id="Speciality" name="Speciality" required>
                    <option value="">--Select Speciality--</option>
                    <?php while ($row = mysqli_fetch_assoc($specialtiesResult)): ?>
                        <option value="<?= htmlspecialchars($row['id']) ?>">
                            <?= htmlspecialchars($row['speciality']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <br><br>
            </div>

            <input type="hidden" id="HiddenSpeciality" name="Speciality">

            <div class="form-column2">
                <label for="Doctor">Select Doctor:</label>
                <select id="Doctor" name="Doctor" required>
                    <option value="">--Select Doctor--</option>
                </select>
                <br><br>

                <label for="date">Select Date:</label>
                <input type="date" id="date" name="date">
                <br><br>

                <label for="time">Select a time:</label>
                <input type="time" id="time" name="time">
                <br><br>

                <div id="form-container">
                    <div class="form-column">
                        <label for="reason">Reason for Visit:</label>
                        <textarea id="reason" name="reason" rows="6" cols="50"></textarea>
                    </div>
                </div>
                <br><br>
                <button type="submit" name="bookingSubmit">Submit</button>
            </div>
        </form>
    </div>
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
                <li>📞 (011) 123-4567</li>
                <li>✉ contact@mufaclinic.com</li>
                <li>🌐 www.mufaclinic.com</li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        © 2025 Muafa Clinic - All Rights Reserved
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const speciality = document.getElementById('Speciality');
    const doctor = document.getElementById('Doctor');
    const hiddenInput = document.getElementById('HiddenSpeciality');

    speciality.addEventListener('change', function () {
        const id = this.value;
        hiddenInput.value = id;
        doctor.innerHTML = '<option value="">Loading...</option>';

        // جلب البيانات عبر Fetch
        fetch('get_doctors.php?speciality_id=' + id)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json(); // التحويل إلى JSON
            })
            .then(data => {
                console.log("Parsed data:", data);
                doctor.innerHTML = '<option value="">--Select Doctor--</option>';
                // إضافة الأطباء إلى القائمة
                data.forEach(name => {
                    const opt = document.createElement('option');
                    opt.value = name;
                    opt.textContent = name;
                    doctor.appendChild(opt);
                });
            })
            .catch((error) => {
                console.error("Fetch Error:", error);
                doctor.innerHTML = '<option value="">Error loading</option>';
            });
    });
});


</script>


</body>
</html>