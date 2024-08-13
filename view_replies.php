<?php
include 'db.php';
session_start();

if (isset($_POST['question_id']) || isset($_GET['question_id'])) {
    $question_id = isset($_POST['question_id']) ? $_POST['question_id'] : $_GET['question_id'];

    $sql = "SELECT q.username AS question_username, q.question, q.created_at AS question_created_at, a.username AS answer_username, a.answer, a.created_at AS answer_created_at
            FROM questions q
            LEFT JOIN answers a ON q.id = a.question_id
            WHERE q.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $question = null;
        $answers = [];

        while ($row = $result->fetch_assoc()) {
            if (!$question) {
                $question = [
                    'username' => $row['question_username'],
                    'question' => $row['question'],
                    'created_at' => $row['question_created_at']
                ];
            }
            if ($row['answer']) {
                $answers[] = $row;
            }
        }
    } else {
        echo "No question found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/3236b0edfe.js" crossorigin="anonymous"></script>
    <title>View Replies</title>
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
        .question, .answer {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }
        .answer {
            margin-left: 20px;
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
            /* padding: 15px; */
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
            color: white;
        }
        button {
            padding: 10px 20px;
            background-color: rgb(160, 99, 160);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
        </header>
        </div>
    <div class="container">
        <h1>View Replies</h1>
        <?php if ($question): ?>
            <div class="question">
                <strong><?php echo htmlspecialchars($question['username']); ?></strong>: <p><?php echo nl2br(htmlspecialchars($question['question'])); ?></p>
                <small>Posted on: <?php echo htmlspecialchars($question['created_at']); ?></small>
            </div>
            <?php foreach ($answers as $answer): ?>
                <div class="answer">
                    <strong><?php echo htmlspecialchars($answer['answer_username']); ?></strong>: <p><?php echo nl2br(htmlspecialchars($answer['answer'])); ?></p>
                    <small>Answered on: <?php echo htmlspecialchars($answer['answer_created_at']); ?></small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No question found.</p>
        <?php endif; ?>
        <button><a href="code_index.php" class="btn">Back to Questions</a></button>
    </div>
</body>
</html>
