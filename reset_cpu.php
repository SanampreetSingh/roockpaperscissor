<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['choice'])) {
    $userChoice = $_POST['choice'];
    $choices = ['rock', 'paper', 'scissors'];
    $computerChoice = $choices[array_rand($choices)];
    
    // Initialize score if not exists
    if (!isset($_SESSION['cpu_score'])) {
        $_SESSION['cpu_score'] = ['win' => 0, 'lose' => 0, 'tie' => 0];
    }
    
    // Determine result
    if ($userChoice == $computerChoice) {
        $result = "It's a tie!";
        $_SESSION['cpu_score']['tie']++;
    } elseif (
        ($userChoice == 'rock' && $computerChoice == 'scissors') ||
        ($userChoice == 'scissors' && $computerChoice == 'paper') ||
        ($userChoice == 'paper' && $computerChoice == 'rock')
    ) {
        $result = "You win!";
        $_SESSION['cpu_score']['win']++;
    } else {
        $result = "Computer wins!";
        $_SESSION['cpu_score']['lose']++;
    }
} else {
    header("Location: play_cpu.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Game Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 90%;
            max-width: 500px;
            text-align: center;
        }
        .scoreboard {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin: 1.5rem 0;
        }
        .btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            margin: 0.5rem;
            text-decoration: none;
            border-radius: 4px;
        }
        .play-again {
            background: #6a11cb;
            color: white;
        }
        .reset {
            background: #e74c3c;
            color: white;
        }
        .menu {
            background: #2c3e50;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Result</h2>
        <p>You chose: <strong><?= htmlspecialchars($userChoice) ?></strong></p>
        <p>Computer chose: <strong><?= htmlspecialchars($computerChoice) ?></strong></p>
        <h3><?= $result ?></h3>
        
        <div class="scoreboard">
            <h4>Scoreboard</h4>
            <p>Wins: <?= $_SESSION['cpu_score']['win'] ?></p>
            <p>Losses: <?= $_SESSION['cpu_score']['lose'] ?></p>
            <p>Ties: <?= $_SESSION['cpu_score']['tie'] ?></p>
        </div>
        
        <a href="play_cpu.php" class="btn play-again">Play Again</a>
        <a href="reset_cpu.php" class="btn reset">Reset Score</a>
        <a href="index.php" class="btn menu">Main Menu</a>
    </div>
</body>
</html>
