<?php
include('db_connection.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'];
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $firstName = mysqli_real_escape_string($conn, $_POST['first-name']);
    $lastName = mysqli_real_escape_string($conn, $_POST['last-name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check_email = "SELECT emailAddress FROM patient WHERE emailAddress='$email'
                    UNION SELECT emailAddress FROM doctor WHERE emailAddress='$email'";
    $email_result = mysqli_query($conn, $check_email);

    if(mysqli_num_rows($email_result) > 0){
        $_SESSION['error'] = "Email already exists!";
        header('Location: signup.php');
        exit();
    } else {
        if ($role == 'patient') {
            $gender = mysqli_real_escape_string($conn, $_POST['gender']);
            $dob = mysqli_real_escape_string($conn, $_POST['dob']);
            $sql = "INSERT INTO patient (ID, firstname, lastname, Gender, DOB, emailAddress, password) 
                    VALUES ('$id','$firstName','$lastName','$gender','$dob','$email','$password')";
            mysqli_query($conn, $sql);
            $_SESSION['user_id'] = $id;
            $_SESSION['user_type'] = 'patient';
            header('Location: Patient_Homepage.php');
            exit();
        } elseif ($role == 'doctor') {
            $speciality = mysqli_real_escape_string($conn, $_POST['speciality']);
            $uniqueFileName = uniqid() . basename($_FILES['photo']['name']);
            move_uploaded_file($_FILES['photo']['tmp_name'], "images/".$uniqueFileName);

            $sql = "INSERT INTO doctor (ID, firstName, lastName, uniqueFileName, SpecialityID, emailAddress, password) 
                    VALUES ('$id','$firstName','$lastName','$uniqueFileName','$speciality','$email','$password')";
            mysqli_query($conn, $sql);
            $_SESSION['user_id'] = $id;
            $_SESSION['user_type'] = 'doctor';
            header('Location: Doctor_Homepage.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up Page</title>
   <style>
        * {
            margin: 0;
            padding: 0;
        }

        body, html {
            height: 100%;
            width: 100%;
        }

        /* Moving background with an image */
        .moving-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('images/b2.png'); /* Replace with your image URL */
            background-size: cover;
            background-position: center center; /* Centers the image */
            z-index: -1;
        }

        /* Navbar Styling */
        .navbar {
            width: 100%;
            margin: auto;
            padding: 10px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: rgba(100, 53, 32, 0.5); /* 50% transparency */
            font-family: 'Poppins', sans-serif;
        }

        .logo {
            width: 100px;
            margin-left: 2em;
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

        /* Breadcrumbs Styling */
        .breadcrumbs {
            font-size: 14px;
            margin-bottom: 2em;
            background-color: #faefe0;
            padding: 3px;
            border-radius: 5px;
            display: inline-block;
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

        /* Footer Styling */
        .footer {
            background-color: rgba(100, 53, 32, 0.9);
            padding: 30px 50px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            border-top: 2px solid #ddd;
            font-family: 'Poppins', sans-serif;
            margin-top: 2em;
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

        /* Sign Up Form Container */
        .container {
            width: 50%;
            margin: 0 auto;
        }

        .form-container {
            width: 400px;
            margin: auto;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding-left: 70px;
            color: #fff;
			font-family: 'Poppins', sans-serif;
        }

        .form-container {
            display: none;
        }

        .form-container.active {
            display: block;
            background: #fffffff5;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            color: black;
			font-family: 'Poppins', sans-serif;
        }

        h1 {
            text-align: center;
            color: #fff;
			font-family: 'Poppins', sans-serif;
        }

        h2 {
            color: black;
        }

        input[type="text"], input[type="email"], input[type="password"], select {
            width: 80%;
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 55px;
            background: rgba(255, 255, 255, 0.2);
        }

        .form-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            margin-top: 20px;
            margin-right: 57px;
        }

        .form-buttons button {
            background-color: #8e6447;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            width: 100%;
        }

        .form-buttons button:hover {
            background-color: #643520;
        }


        /* Radio Button Group */
        .radio-group1, .radio-group2 {
            display: flex;
            justify-content: center;
            gap: 50px;
            margin-bottom: 20px;
        }

        .radio-group1 label {
            display: inline-block;
            font-size: 18px;
            color: #fff;
        }

        .radio-group2 label {
            display: inline-block;
            font-size: 18px;
        }

        .radio-group1 input, .radio-group2 input {
            transform: scale(1.5);
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="moving-background"></div>

    <div class="navbar">
        <img src="images\معافى.png" class="logo" alt="Logo">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="#Quicklinks">Quick Links </a></li>
            <li><a href="#contact-us">Contact</a></li>
        </ul>
    </div>

    <div class="breadcrumbs">
        <a href="index.php">Home</a> &gt; <a href="signup.php">Sign up</a>
    </div>

    <div class="container">
        <h1>Sign Up</h1>

        <?php
        if(isset($_SESSION['error'])){
            echo '<p style="color:red;text-align:center;">'.$_SESSION['error'].'</p>';
            unset($_SESSION['error']);
        }
        ?>

        <div id="role-selection">
            <div class="radio-group1">
                <label><input type="radio" id="patient-role" name="role" value="patient"> Patient</label>
                <label><input type="radio" id="doctor-role" name="role" value="doctor"> Doctor</label>
            </div>
        </div>

        <div id="patient-form" class="form-container">
            <h2>Patient Sign Up</h2>
            <form action="signup.php" method="POST">
                <input type="hidden" name="role" value="patient">
                <label>First Name:</label><input type="text" name="first-name" required>
                <label>Last Name:</label><input type="text" name="last-name" required>
                <label>ID:</label><input type="text" name="id" required>
                <label>Gender:</label>
                <div class="radio-group2">
                    <label><input type="radio" name="gender" value="Male" required> Male</label>
                    <label><input type="radio" name="gender" value="Female" required> Female</label>
                </div>
                <label>Date of Birth:</label><input type="date" name="dob" required>
                <label>Email Address:</label><input type="email" name="email" required>
                <label>Password:</label><input type="password" name="password" required>
                
        <div class="form-buttons">
            <button type="submit">Submit</button>
        </div>
            </form>
        </div>

        <div id="doctor-form" class="form-container">
            <h2>Doctor Sign Up</h2>
            <form action="signup.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="role" value="doctor">
                <label>First Name:</label><input type="text" name="first-name" required>
                <label>Last Name:</label><input type="text" name="last-name" required>
                <label>ID:</label><input type="text" name="id" required>
                <label>Photo:</label><input type="file" name="photo" required>
                <label>Speciality:</label>
                <select name="speciality" required>
                    <option value="1111">Dentist</option>
                    <option value="2222">Dermatologist</option>
                    <option value="3333">Obstetrics & Gynec</option>
                </select>
                <label>Email Address:</label><input type="email" name="email" required>
                <label>Password:</label><input type="password" name="password" required>
                
        <div class="form-buttons">
            <button type="submit">Submit</button>
        </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('role-selection').addEventListener('change', function() {
            document.getElementById('patient-form').classList.toggle('active', document.getElementById('patient-role').checked);
            document.getElementById('doctor-form').classList.toggle('active', document.getElementById('doctor-role').checked);
        });
    </script>

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
        <div class="footer-bottom">© 2025 Muafa Clinic - All Rights Reserved</div>
    </footer>
</body>
</html>