<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodersHub</title>
    <link rel="shortcut icon" type="image/x-icon" href="img/CodersHub.png" />
    <script src="https://kit.fontawesome.com/3236b0edfe.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: white;
            border-color: purple;
        }
        * {
            box-sizing: border-box;
        }
        .column {
            float: left;
            width: 250px;
            justify-content: center;
            margin: 20px;
            text-align: center;
            background-color: rgb(129, 53, 136);
            border-radius: 20px;
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
        }
        .column:hover {
            transform: scale(1.1);
        }
        .row::after {
            content: "";
            clear: both;
            display: table;
        }
        .caption {
            text-align: center;
        }
        a {
            text-decoration: none;
            color: #000;
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
            color: red;
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
        #login {
            background-color: rgb(160, 99, 160);
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 25px;
            font-weight: bold;
        }
        #title {
            font-size: medium;
            font-family: cursive;
            font-weight: bold;
            text-align: center;
            text-justify: auto;
            font-size: 40px;
            color: rebeccapurple;
            transition: transform 0.3s ease-in-out;
        }
        #title:hover {
            transform: scale(1.1); 
        }
        .he {
            border-style: solid;
            border-width: 5px;
            border-radius: 20px;
            background-color: rgb(129, 53, 136);
        }
        .cent {
            justify-content: space-between;
            margin: auto;
            text-align: center;
            vertical-align: middle;
        }
        .caption img:hover {
            box-shadow: 0 0 2px 8px rgba(255, 255, 255, 255);
        }
        .item a:hover {
            box-shadow: 0 0 2px 1px rgba(255, 255, 255, 255);
        }
        img {
            margin-top: 20px;
            vertical-align: middle;
        }
        .row {
            justify-content: center;
            top: 50%;
            left: 50%;
            margin-left: 21%;
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
    </style>
</head>
<body>
    <div class="he">
        <header class="site-header">
            <div class="site-identity">
                <a href="#" id="logo"><img src="img\CodersHub.png" alt="Site Logo"></a>
                <h1><a href="#" style="color:white;">CodersHub</a></h1>
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
        </header><br><br>
        <div class="bor">
        <h2 id="title">"Coding: The Journey of Endless Possibilities."</h2><br><br>

        <div class="cent">    
        <div class="row">
            <div class="column">
                <div class="caption">
                    <a href="exp_index.php"><img src="img/exp1.png" alt="Experiences" width="100px" height="100px"></a> 
                    <h3 class="item"><a href="exp_index.php">Experiences</a></h3>
                </div>
            </div>
            <div class="column">
                <div class="caption">
                    <a href="code_index.php"><img src="img/code.png" alt="Code Tackle" width="100px" height="100%px"></a>
                    <h3 class="item"><a href="code_index.php">Code Tackle</a></h3>
                </div>
            </div>
            <div class="column">
                <div class="caption">
                    <a href="project_index.php"><img src="img/pro.png" alt="project showcase" width="100px" height="100px"></a>
                    <h3 class="item"><a href="project_index.php">Project Showcase</a></h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="column">
                <div class="caption">
                    <a href="G:/branches.html"><img src="img/exam.png" alt="Exam" width="100px" height="100px"></a>
                    <h3 class="item"><a href="">Exam</a></h3>
                </div>
            </div>
            <div class="column">
                <div class="caption">
                    <a href="learn_index.php"><img src="img/grow.png" alt="Learn and Grow" width="100px" height="100px"></a>
                    <h3 class="item"><a href="learn_index.php">Learn and Grow!!!</a></h3>
                </div>
            </div>
            <div class="column">
                <div class="caption">
                    <a href="G:/branches.html"><img src="img/lead.png" alt="Leaderboard" width="100px" height="100px"></a>
                    <h3 class="item"><a href="">Leaderboard</a></h3>
                </div>
            </div>
        </div>
        </div>
        </div>
</body>
</html>
