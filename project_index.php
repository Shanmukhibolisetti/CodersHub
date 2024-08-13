<?php
session_start();
include 'db.php';

$department = isset($_GET['department']) ? $_GET['department'] : '';

$sql = "SELECT id, department, domain, title, description, created_at, username FROM projects";
if ($department) {
    $sql .= " WHERE department = ?";
}
$sql .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
if ($department) {
    $stmt->bind_param("s", $department);
}
$stmt->execute();
$projects = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/3236b0edfe.js" crossorigin="anonymous"></script>
    <title>Project Showcase</title>
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
            max-width: 900px;
            margin: 0 auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .project {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }
        .project h4 {
            margin: 0 0 10px 0;
        }
        .project p {
            margin: 0 0 10px 0;
        }
        .project small {
            display: block;
            margin-top: 10px;
            color: #666;
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
        .button-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
        }
        .add-button {
            font-size: 24px;
            width: 60px;
            height: 60px;
            border: none;
            background-color: rgb(160, 99, 160);
            color: #fff;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .add-button:hover {
            background-color: rgb(160, 99, 160);
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
        .blur {
            filter: blur(5px);
        }
        .project-form {
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

        .project-form h1 {
            color: var(--primary-color);
            text-align:center;
        }

        .project-form .input-group {
            margin-bottom: 1rem;
        }

        .project-form .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--gray-2);
        }

        .project-form .input-group input,
        .project-form .input-group select,
        .project-form .input-group textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid var(--gray-2);
            border-radius: 0.5rem;
            font-size: 1rem;
            outline: none;
        }

        .project-form .input-group textarea {
            resize: vertical;
            height: 100px;
        }

        .bt {
            display: flex;
            justify-content: center; 
            align-items: center; 
        }

        .project-form button {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 1rem;
        }

        .filter-buttons {
            text-align: center;
            margin-bottom: 20px;
        }
        .filter-buttons form {
            display: inline-block;
        }
        .filter-buttons button {
            background-color: rgb(129, 53, 136);
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            margin: 0 5px;
            cursor: pointer;
            outline: none;
        }
        .filter-buttons button.active {
            background-color: #555; 
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
        <h1>Project Showcase</h1>
        <div class="filter-buttons">
            <form method="get" action="project_index.php">
                <button type="submit" name="department" value="" class="<?php echo $department == '' ? 'active' : ''; ?>">All Departments</button>
            </form>
            <form method="get" action="project_index.php">
                <button type="submit" name="department" value="CSE" class="<?php echo $department == 'cse' ? 'active' : ''; ?>">CSE</button>
            </form>
            <form method="get" action="project_index.php">
                <button type="submit" name="department" value="ECE" class="<?php echo $department == 'ece' ? 'active' : ''; ?>">ECE</button>
            </form>
            <form method="get" action="project_index.php">
                <button type="submit" name="department" value="IT" class="<?php echo $department == 'it' ? 'active' : ''; ?>">IT</button>
            </form>
            <form method="get" action="project_index.php">
                <button type="submit" name="department" value="Civil" class="<?php echo $department == 'civil' ? 'active' : ''; ?>">Civil</button>
            </form>
        </div>

        <?php while ($project = $projects->fetch_assoc()): ?>
            <div class="project">
                <h4><?php echo htmlspecialchars($project['title']); ?></h4>
                <p><strong>Department:</strong> <?php echo htmlspecialchars($project['department']); ?></p>
                <p><strong>Domain:</strong> <?php echo htmlspecialchars($project['domain']); ?></p>
                <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($project['description'])); ?></p>
                <small>Posted by: <?php echo htmlspecialchars($project['username']); ?> on <?php echo htmlspecialchars($project['created_at']); ?></small>
            </div>
        <?php endwhile; ?>
        
        <div class="button-container">
            <button class="add-button" id="openModal">+</button>
        </div>
    </div>

    <div id="projectModal" class="modal">
        <div class="project-form">
            <span class="close">&times;</span>
            <h1>Add Project</h1>
            <form id="project-form" method="post" action="add_project.php">
            <div class="input-group">
                <label for="department">Department</label>
                <select name="department" id="department" required>
                    <option value="" disabled selected>Select Department</option>
                    <option value="cse">CSE</option>
                    <option value="ece">ECE</option>
                    <option value="it">IT</option>
                    <option value="civil">Civil</option>
                </select>
            </div>
            <div class="input-group">
                <label for="domain">Domain</label>
                <input type="text" id="domain" name="domain" placeholder="Enter project domain..." required>
            </div>
            <div class="input-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" placeholder="Enter project title..." required>
            </div>
            <div class="input-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Enter project description..." required></textarea>
            </div>
            <div class="bt">
                <button type="submit">Submit</button>
            </div>
        </form>
        </div>
    </div>

    <script>
        var modal = document.getElementById("projectModal");
        var btn = document.getElementById("openModal");
        var span = document.getElementsByClassName("close")[0];
        var content = document.getElementById("content");

        btn.onclick = function() {
            modal.style.display = "block";
            content.classList.add("blur");
        }

        span.onclick = function() {
            modal.style.display = "none";
            content.classList.remove("blur");
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                content.classList.remove("blur");
            }
        }
    </script>
</body>
</html>
