<?php
session_start();

// Validate input
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['choice'])) {
    header("Location: play_cpu.php");
    exit;
}

$validChoices = ['rock', 'paper', 'scissors'];
$userChoice = $_POST['choice'];

// If invalid choice is somehow submitted
if (!in_array($userChoice, $validChoices)) {
    header("Location: play_cpu.php");
    exit;
}

// Initialize score if not exists
if (!isset($_SESSION['cpu_score'])) {
    $_SESSION['cpu_score'] = ['win' => 0, 'lose' => 0, 'tie' => 0];
}

// Computer's random choice
$computerChoice = $validChoices[array_rand($validChoices)];

// Determine winner
$result = '';
if ($userChoice === $computerChoice) {
    $result = "It's a tie!";
    $_SESSION['cpu_score']['tie']++;
} elseif (
    ($userChoice === 'rock' && $computerChoice === 'scissors') ||
    ($userChoice === 'paper' && $computerChoice === 'rock') ||
    ($userChoice === 'scissors' && $computerChoice === 'paper')
) {
    $result = "You win!";
    $_SESSION['cpu_score']['win']++;
} else {
    $result = "Computer wins!";
    $_SESSION['cpu_score']['lose']++;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Result: Vs Computer</title>
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
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 90%;
            max-width: 500px;
            text-align: center;
        }
        .result {
            margin: 2rem 0;
        }
        .choices {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2c3e50;
        }
        .result-text {
            font-size: 1.5rem;
            color: #6a11cb;
            margin: 1rem 0;
        }
        .scoreboard {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 2rem 0;
        }
        .actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: #6a11cb;
            color: white;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #5a0db3;
            transform: translateY(-2px);
        }
        .reset {
            background: #e74c3c;
        }
        .reset:hover {
            background: #d62c1a;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Game Result</h2>
        
        <div class="result">
            <p>You chose: <span class="choices"><?= htmlspecialchars(ucfirst($userChoice)) ?></span></p>
            <p>Computer chose: <span class="choices"><?= htmlspecialchars(ucfirst($computerChoice)) ?></span></p>
            <p class="result-text"><?= $result ?></p>
        </div>
        
        <div class="scoreboard">
            <h3>Current Score</h3>
            <p>Wins: <?= $_SESSION['cpu_score']['win'] ?></p>
            <p>Losses: <?= $_SESSION['cpu_score']['lose'] ?></p>
            <p>Ties: <?= $_SESSION['cpu_score']['tie'] ?></p>
        </div>
        
        <div class="actions">
            <a href="play_cpu.php" class="btn">Play Again</a>
            <a href="reset_cpu.php" class="btn reset">Reset Score</a>
            <a href="index.php" class="btn">Main Menu</a>
        </div>
    </div>
</body>
</html>
