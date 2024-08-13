<?php
include 'db.php';
session_start();

$stmt = $conn->prepare("SELECT id, username, question, created_at FROM questions ORDER BY created_at DESC");
$stmt->execute();
$questions = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/3236b0edfe.js" crossorigin="anonymous"></script>
    <title>Code Tackle Page</title>
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
        form {
            margin-bottom: 20px;
        }
        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
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
        .question {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }
        .answer-form {
            display: inline-block;
            margin-right: 10px;
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
        <h1>Ask a Question</h1>
        <form action="add_question.php" method="post">
            <textarea name="question" placeholder="Your question" required></textarea>
            <button type="submit">Submit Question</button>
        </form>

        <?php while ($question = $questions->fetch_assoc()): ?>
            <div class="question">
                <h4><?php echo htmlspecialchars($question['username']); ?></h4>
                <p><?php echo htmlspecialchars($question['question']); ?></p>
                <small>Posted on: <?php echo htmlspecialchars($question['created_at']); ?></small>
                
                <form action="add_answer.php" method="post" class="answer-form">
                    <input type="hidden" name="question_id" value="<?php echo htmlspecialchars($question['id']); ?>">
                    <button type="submit">Add Reply</button>
                </form>
                
                <form action="view_replies.php" method="post" class="answer-form">
                    <input type="hidden" name="question_id" value="<?php echo htmlspecialchars($question['id']); ?>">
                    <button type="submit">View Replies</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
    <script>
        <script>
        // Ensure correct redirection on back button press
        window.onpopstate = function(event) {
            window.location.href = 'index.php'; 
        };

        // Push state to history stack
        window.history.pushState({}, '');
    </script>
</body>
</html>
