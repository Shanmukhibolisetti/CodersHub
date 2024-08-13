<?php
include 'db.php';
session_start();

$stmt = $conn->prepare("
    SELECT t.id, t.techName, t.description, t.difficultyLevel, t.useCases, t.created_at, t.username, 
           GROUP_CONCAT(CASE WHEN l.type = 'paid' THEN l.link END) AS paidLinks,
           GROUP_CONCAT(CASE WHEN l.type = 'unpaid' THEN l.link END) AS unpaidLinks
    FROM technologies t
    LEFT JOIN links l ON t.id = l.technology_id
    GROUP BY t.id
    ORDER BY t.created_at DESC
");
$stmt->execute();
$technologies = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/3236b0edfe.js" crossorigin="anonymous"></script>
    <title>Road Map</title>
    <style>
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
        .technology {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }
        .technology h4 {
            margin: 0 0 10px 0;
        }
        .technology p {
            margin: 0 0 10px 0;
        }
        .technology small {
            display: block;
            margin-top: 10px;
            color: #666;
        }
        .links {
            margin-top: 10px;
        }
        .link-title {
            font-weight: bold;
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
    <div class="container">
        <h1>Road Map</h1>

        <?php while ($tech = $technologies->fetch_assoc()): ?>
            <div class="technology">
                <h4><?php echo htmlspecialchars($tech['techName']); ?></h4>
                <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($tech['description'])); ?></p>
                <p><strong>Difficulty Level:</strong> <?php echo htmlspecialchars($tech['difficultyLevel']); ?></p>
                <p><strong>Use Cases:</strong> <?php echo nl2br(htmlspecialchars($tech['useCases'])); ?></p>
                <div class="links">
                    <?php if (!empty($tech['paidLinks'])): ?>
                        <p class="link-title">Paid Links:</p>
                        <?php foreach (explode(',', $tech['paidLinks']) as $link): ?>
                            <a href="<?php echo htmlspecialchars($link); ?>" target="_blank"><?php echo htmlspecialchars($link); ?></a><br>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if (!empty($tech['unpaidLinks'])): ?>
                        <p class="link-title">Unpaid Links:</p>
                        <?php foreach (explode(',', $tech['unpaidLinks']) as $link): ?>
                            <a href="<?php echo htmlspecialchars($link); ?>" target="_blank"><?php echo htmlspecialchars($link); ?></a><br>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <small>Posted by: <?php echo htmlspecialchars($tech['username']); ?> on <?php echo htmlspecialchars($tech['created_at']); ?></small>
            </div>
        <?php endwhile; ?>
        
        <div class="button-container">
            <a href="learn_form.html">
                <button class="add-button">+</button>
            </a>
        </div>
    </div>
</body>
</html>
