<?php
include 'db.php';
session_start();

$stmt = $conn->prepare("SELECT id, username, company, role, experience, created_at FROM experiences ORDER BY created_at DESC");
$stmt->execute();
$experiences = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/3236b0edfe.js" crossorigin="anonymous"></script>
    <title>Experience Page</title>
    <style>
        :root {
            --primary-color: rgb(129, 53, 136);
            --secondary-color: rgb(129, 53, 136);
            --black: #000000;
            --white: #ffffff;
            --gray: #efefef;
            --gray-2: #757575;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .experience {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            margin-left: 50px;
            margin-right: 50px;
            background-color: #f9f9f9;
        }
        .experience h4, .experience h5 {
            margin: 0 0 10px 0;
        }
        .experience p {
            margin: 0 0 10px 0;
        }
        .experience small {
            display: block;
            margin-top: 10px;
            color: #666;
        }
        button.add-button {
            position: fixed;
            bottom: 20px;
            right: 270px;
            border: none;
            background-color: rgb(160, 99, 160);
            color: #fff;
            font-size: 24px;
            z-index: 999; 
            width: 60px;
            height: 60px;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        button {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            background-color: rgb(160, 99, 160);
            color: #fff;
            cursor: pointer;
        }
        .modal {
            display: none; 
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4); 
        }
        .modal-content {
            background-color: rgba(0,0,0,0.4);
            margin: auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
            max-width: 500px; 
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            position: relative;
            top: 50%; 
            transform: translateY(-50%); 
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .blurred {
            filter: blur(5px);
        }
        .experience-form {
            background-color: var(--white);
            margin: auto; 
            padding: 3rem;
            border: 1px solid #888;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 5px 15px;
            position: relative;
            width: 80%;
            top: 50%; 
            max-width: 500px;
            transform: translateY(-50%);
        }

        .experience-form h1 {
            color: var(--primary-color);
            text-align:center;
        }

        .experience-form .input-group {
            margin-bottom: 1rem;
        }

        .experience-form .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--gray-2);
        }

        .experience-form .input-group input,
        .experience-form .input-group textarea,
        .experience-form .input-group select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid var(--gray-2);
            border-radius: 0.5rem;
            font-size: 1rem;
            outline: none;
        }

        .experience-form .input-group textarea {
            resize: vertical;
            height: 100px;
        }

        .bt {
            display: flex;
            justify-content: center; 
            align-items: center; 
        }

        .experience-form button {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 1rem;
        }
        .back {
            padding-left:20px;
            font-size: 2em; 
            font-weight: bold; 
            display: inline-block;
            margin-bottom: 20px;
        }

        .site-header {
            border-bottom: 1px solid #ccc;
            padding: .5em 1em;
        }
        .site-header::after {
            content: "";
            display: table;
            clear: both;
        }
        .site-identity {
            float: left;
        }
        .site-identity h1 {
            font-size: 30px;
            margin: 0 20 0 0;
            display: inline-block;
            padding-top: 4px;
            color: white;
            font-weight: bolder;
        }
        .site-identity img {
            height:70px;
            width: 70px;
            float: left;
            margin: 0 10px 0 0;
            clip-path: circle(50%);
            object-fit: cover;
        }
        .site-navigation {
            float: right;
        }
        .site-navigation ul, li {
            margin: 0; 
            padding: 0;
        }
        .site-navigation li {
            display: inline-block;
            margin: 1.4em 1em 1em 1em;
        }

        .he {
            border-style: solid;
            border-width: 5px;
            border-radius: 20px;
            background-color: rgb(129, 53, 136);
            margin-bottom:20px;
        }

        .site-navigation a {
            color: white;
        }
        .username {
            font-size: 20px;
            display: inline-block;
            color: white;
            font-style:italic;
        }
        #user {
            color: white;
            display: inline-block;
            font-size: 20px;
        }

        a {
            text-decoration: none;
            color: #000;
        }
       
        
    </style>
</head>
<body>
<div class="he">
        <header class="site-header">
            <div class="site-identity">
                <a href="index.php" id="logo"><img src="img\CodersHub.png" alt="Site Logo"></a>
                <h1>CodersHub</h1>
            </div>  
            <nav class="site-navigation">
                <ul class="nav">
                    <?php if(isset($_SESSION['username'])): ?>
                        <li class="username"><i class="fa-solid fa-circle-user" id="user"></i>&nbsp;<?php echo htmlspecialchars($_SESSION['username']); ?></li>
                        <li><a href="logout.php"><i class='fas fa-sign-out-alt'></i> Logout</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        </header>
        </div>
    <div class="container" id="content">
        <button class="add-button" id="addButton">+</button>
        <h1>Interview Experiences</h1>

        <?php while ($experience = $experiences->fetch_assoc()): ?>
            <div class="experience">
                <h4><?php echo htmlspecialchars($experience['username']); ?></h4>
                <h5><?php echo htmlspecialchars($experience['company']); ?> - <?php echo htmlspecialchars($experience['role']); ?></h5>
                <p><?php echo nl2br(htmlspecialchars($experience['experience'])); ?></p>
                <small>Posted on: <?php echo htmlspecialchars($experience['created_at']); ?></small>
            </div>
        <?php endwhile; ?>
        
    </div>

    <div id="myModal" class="modal">
        
            
            <div class="experience-form">
            <span class="close">&times;</span>
                <h1>New Experience</h1>
                <form id="experience-form" action="add_experience.php" method="post">
                    <div class="input-group">
                        <label for="company">Company</label>
                        <input type="text" id="company" name="company" required>
                    </div>
                    <div class="input-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" required>
                            <option value="Full-Time">Full-Time</option>
                            <option value="Intern">Intern</option>
                            <option value="Freelance">Freelance</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="experience">Experience</label>
                        <textarea id="experience" name="experience" required></textarea>
                    </div>
                    <div class="bt">
                        <button type="submit">Submit</button>
                    </div>
                </form>
            </div>
        
    </div>

    <script>
        var modal = document.getElementById("myModal");

        var btn = document.getElementById("addButton");

        var span = document.getElementsByClassName("close")[0];

        var content = document.getElementById("content");

        btn.onclick = function() {
            modal.style.display = "block";
            content.classList.add("blurred");
        }

        span.onclick = function() {
            modal.style.display = "none";
            content.classList.remove("blurred");
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                content.classList.remove("blurred");
            }
        }
    </script>
</body>
</html>

