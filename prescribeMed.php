<?php
include('db_connection.php');
session_start();

// التحقق من وجود معرف المريض في سلسلة الاستعلام
if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];
    $appointmentID = $_GET['id'] ;

    // استرجاع بيانات المريض من قاعدة البيانات باستخدام معرف المريض
    $query = "SELECT * FROM patient WHERE ID = '$patient_id'";
    $result = mysqli_query($conn, $query);

    // التحقق مما إذا كانت البيانات موجودة
    if ($result) {
        $patient = mysqli_fetch_assoc($result);
        if (!$patient) {
            // إذا لم يتم العثور على المريض
            $message = "Patient not found in the database.";
        } else {
            $message = ""; // إذا تم العثور على المريض
        }
    } else {
        // في حالة حدوث خطأ في الاستعلام
        die("Query failed: " . mysqli_error($conn));
    }
} else {
    $message = "Patient ID is missing in the URL.";
    $patient = null; // تعيين المتغير إلى null بحيث لا تظهر بيانات المريض
}

function calculateAge($dob) {
    if (!$dob) return "Unknown";

    try {
        $dob = new DateTime($dob);
        $today = new DateTime();
        $age = $today->diff($dob);
        return $age->y . " years, " . $age->m . " months";
    } catch (Exception $e) {
        return "Invalid date";
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>معافى</title>
    <link rel="stylesheet" href="css.css">
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
    <a href="prescribeMed.php">Prescribe Medication </a>
  </div>

  <div class="container">
    <h1>Patient's Medications</h1>

    <!-- Personal Information Form -->
    <form class="form-section" id="medications-form" method="POST" action="updatePrescription.php">
    <h2>Patient Information</h2>
    <ul>
        <li>Name: <?php echo htmlspecialchars($patient['firstname'] . ' ' . $patient['lastname']); ?></li>
        <li>Gender: <?php echo htmlspecialchars($patient['Gender']); ?></li>
        <li>Age: <?php echo calculateAge($patient['DOB']); ?></li>
    </ul>

    <!-- Hidden fields to send appointment and patient IDs -->
    <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($patient['ID']); ?>">
    <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointmentID); ?>">

    <h2>Medications</h2>
    <label><input type="checkbox" name="medications[]" value="Aspirin"> Aspirin</label><br>
    <label><input type="checkbox" name="medications[]" value="Ibuprofen"> Ibuprofen</label><br>
    <label><input type="checkbox" name="medications[]" value="Paracetamol"> Paracetamol</label><br>

    <button type="submit">Submit</button>
</form>


    

    



  </div>
</body>

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

</html> 


