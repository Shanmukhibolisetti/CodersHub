<?php
include 'db.php';
session_start();


if (!isset($_SESSION['username'])) {
    echo "You must be logged in to post an answer.";
    exit();
}


$question = [];
$error = '';


if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['question_id'])) {
    $question_id = intval($_GET['question_id']);

    $stmt = $conn->prepare("SELECT id, username, question, created_at FROM questions WHERE id = ?");
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $question = $result->fetch_assoc();
    } else {
        echo "No question found.";
        exit();
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['question_id'])) {
    $question_id = intval($_POST['question_id']);
    $username = $_SESSION['username'];
    $answer = isset($_POST['answer']) ? htmlspecialchars($_POST['answer']) : '';

    if (!empty($answer)) {
        $sql = "INSERT INTO answers (question_id, username, answer) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $question_id, $username, $answer);

        if ($stmt->execute()) {
            header("Location: view_replies.php?question_id=" . $question_id);
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        $stmt = $conn->prepare("SELECT id, username, question, created_at FROM questions WHERE id = ?");
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $question = $result->fetch_assoc();
        } else {
            echo "No question found.";
            exit();
        }

        $error = "";
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
    <title>Add Answer</title>
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
        textarea {
            width: 100%;
            height: 100px;
            margin-bottom: 10px;
        }
        button {
            padding: 10px 20px;
            background-color: rgb(160, 99, 160);
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
        <h1>Add Answer</h1>
        <?php if ($error): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (!empty($question)): ?>
            <div class="question">
                <p><strong><?php echo htmlspecialchars($question['username']); ?></strong>: <?php echo htmlspecialchars($question['question']); ?></p>
                <small>Posted on: <?php echo htmlspecialchars($question['created_at']); ?></small>
            </div>
            <form action="add_answer.php" method="post">
                <input type="hidden" name="question_id" value="<?php echo htmlspecialchars($question['id']); ?>">
                <textarea name="answer" placeholder="Your answer" required></textarea>
                <button type="submit">Submit Answer</button>
            </form>
        <?php else: ?>
            <p>No question found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
