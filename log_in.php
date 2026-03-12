<?php 
  session_start();
  
?>

<?php 
    
   include 'db_connection.php';
    
   $error_message="";
   
   if($_SERVER["REQUEST_METHOD"]=='POST'){
       $em=$_POST['email'];
       $pass=$_POST['password'];
       $rol=$_POST['role'];
       
       // Sanitize email to prevent any potential issues
       $em = mysqli_real_escape_string($conn, $em);
    
       if($rol=='doctor'){
           $sql="SELECT * FROM doctor WHERE emailAddress=?";
       }else{
           $sql="SELECT * FROM patient WHERE emailAddress=? ";
       }
       
       
       if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "s", $em);

        // Execute the statement
        mysqli_stmt_execute($stmt);

        // Get the result
        $result = mysqli_stmt_get_result($stmt);
       
         if(mysqli_num_rows($result)==1){
           $user= mysqli_fetch_assoc($result);
           
           if(password_verify($pass, $user['password'])){
               $_SESSION['user_id']=$user['ID'];
               $_SESSION['user_type']=$rol;
               
               if($rol=='patient'){
                   header("Location: Patient_homepage.php ");
               }else{
                   header("Location: Doctor_homepage.php ");
               }
               
               exit();
           }
           else{
            $error_message="Incorrect password.";
           }
       }else{
           $error_message="No user found with this email address.";
       }
       
         // Close the statement
        mysqli_stmt_close($stmt);
   }}

 // Close the connection
mysqli_close($conn);
?>







<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <title>login</title>
       <link href="https://fonts.googleapis.com/css2">
       
       <style>
            body{
                font-family: 'Poppins', sans-serif;
                margin: 0;
                padding: 0;
                background-size: cover;
				background-image: url('images/b2.png'); /* Replace with your image URL */
            background-size: cover;
            background-position: center center; /* Centers the image */
            z-index: -1;
            }

            main {
               display: flex;
               justify-content: center;
               align-items: center;
               min-height: 80vh; /* يضمن أن الفورم في منتصف الصفحة */
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


            @keyframes moveGradient{
			
            0%{background-position: 0% 50%;}
            50%{background-position: 100% 50%;}
            100%{background-position: 0% 50%;}
            }


            

           .login-panel{
               border: 1px solid #ccc;
               box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
               padding: 40px;
               border-radius: 8px;
               width: 300px;
			   color: black;
               background-color: #fff;
            }

            h2{
               text-align: center;
               margin-bottom: 20px;
               font-weight: lighter;
               
            }

            form{
               display: flex;
               flex-direction: column;
               direction: ltr;
            }

            label{
               margin-bottom: 5px;
               
            }

            

            input, select {
            width: 90%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 55px;
			background: rgba(255, 255, 255, 0.2);
            
        }
           
           
        
            button{
               width: 100%;
               margin-bottom: 15px;
               align-items: center;
               padding: 10px 20px;
               border: none;
               border-radius: 4px;
               cursor: pointer;
               background-color:#8e6447;
               color: white;

            }
            button:hover {
                background-color:#643520;
        }

        .sign_up a{
            color:#8e6447 ;
        }

        .sign_up a:hover{
            color:#FFD700 ;
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
            </ul>
        </div>
        <div class="breadcrumbs">
            <a href="index.php">Home</a> &gt; 
            <a href="log_in.php">Log in </a>
        </div>
        <br>
        <div class="welcome-message">
            <h1>Welcome To Muafa Clinic</h1>
        </div>
        

        <main>
            <div class="login-panel">
                <h2>Login</h2>
                <form id="loginForm" action="" method="post">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>

                    <label for="role">Role:</label>
                    <select id="role" name="role" required>
                        <option value="patient">Patient</option>
                        <option value="doctor">Doctor</option>
                    </select>
                    
                    <button type="submit" >Login</button>
                    <div class="sign_up"><p>Don't have an account?<a href="signup.php">Sign up</a></p></div>
                </form>
                <?php
                   if ($error_message != "") {
                   echo "<div style='color: red; text-align: center;'>$error_message</div>";
                }
                ?>
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
