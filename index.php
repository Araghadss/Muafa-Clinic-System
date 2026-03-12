<!DOCTYPE html>
<html lang="en">
<head>
    <title>Homepage</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        
        .banner {
            width: 100%;
            height: 100vh;
            position: relative;
        }

        /* Background video */
        .banner video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
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

        .content {
		   
            width: 100%;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            text-align: center;
            color: #fff;
			 background-color: rgba(255, 255, 255, 0.4); /* طبقة شفافة فوق الصورة */
             padding: 20px;
             border-radius: 10px;

        }

        .content h1 {
            font-size: 70px;
            margin-top: 80px;
        }

        .content p {
            margin: 20px auto;
            font-weight: 100;
            line-height: 25px;
        }

        button {
            width: 200px;
            padding: 15px 0;
            margin: 60px 55px;
            text-align: center;
            border-radius: 30px;
            font-weight: bold;
            border: 2px solid rgb(83 44 27 / 90%);
            background: transparent;
            color: #fff;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            font-size: 20px;
        }

        span {
            background: rgb(117 60 35 / 90%); /* Updated background color */
            height: 100%;
            width: 100%;
            border-radius: 25px;
            position: absolute;
            left: 0;
            bottom: 0;
            z-index: -1;
            transition: 0.5s;
        }

        button:hover span {
            width: 0%;
        }

        button:hover {
            border: none;
        }
    </style>
</head>
<body>

    <div class="banner">
        <!-- Background video -->
        <video autoplay muted loop>
            <source src="images\background video - Made with Clipchamp.mp4" type="video/mp4">
        </video>

        <div class="navbar">
            <img src="images\معافى.png" class="logo" alt="Logo">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="#Quicklinks">Quick Links </a></li>
                <li><a href="#contact-us">Contact</a></li>
            </ul>
        </div>

<div class="breadcrumbs">
        <a href="index.php">Home</a>
        
    </div>
	
        <div class="content">
            <h1>مُعافى</h1>
         

            <button type="button" onclick="window.location.href='log_in.php';"><span></span>Sign In</button>

            <button type="button" onclick="window.location.href='signup.php'"><span></span>Sign Up</button>
        </div>
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
