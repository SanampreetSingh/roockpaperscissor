<?php
session_start();

// Get all required parameters from POST or session
$room = substr(preg_replace('/[^a-zA-Z0-9]/', '', $_POST['room'] ?? ($_SESSION['current_game']['room'] ?? '')), 0, 20);
$player_id = $_POST['player_id'] ?? ($_SESSION['current_game']['player_id'] ?? '');
$opponent = $_POST['opponent'] ?? ($_SESSION['current_game']['opponent'] ?? '');
$choice = $_POST['choice'] ?? '';

// Validate all required parameters
if (empty($room) || empty($player_id) || empty($opponent) || empty($choice) || !isset($_SESSION['rooms'][$room])) {
    header("Location: online.php?error=invalid_parameters");
    exit;
}

// Update player's choice and activity
$_SESSION['rooms'][$room]['players'][$player_id]['choice'] = $choice;
$_SESSION['rooms'][$room]['players'][$player_id]['last_active'] = time();

// Check opponent's choice
$opponentChoice = null;
$opponentActive = false;

foreach ($_SESSION['rooms'][$room]['players'] as $id => $player) {
    if ($id !== $player_id) {
        if (isset($player['choice'])) {
            $opponentChoice = $player['choice'];
        }
        if (time() - $player['last_active'] < 30) {
            $opponentActive = true;
        }
    }
}

// If opponent hasn't chosen yet, wait
if ($opponentChoice === null && $opponentActive) {
    header("Refresh: 3");
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Waiting</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f5f7fa;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                margin: 0;
                text-align: center;
            }
            .container {
                background: white;
                padding: 2rem;
                border-radius: 10px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                width: 90%;
                max-width: 500px;
            }
            .spinner {
                border: 4px solid rgba(0,0,0,0.1);
                border-radius: 50%;
                border-top: 4px solid #6a11cb;
                width: 40px;
                height: 40px;
                animation: spin 1s linear infinite;
                margin: 20px auto;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Waiting for opponent to choose...</h2>
            <div class="spinner"></div>
            <p>This page refreshes automatically every 3 seconds</p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Determine game result
function determineWinner($playerChoice, $opponentChoice) {
    if ($playerChoice === $opponentChoice) return "tie";
    $winConditions = [
        'rock' => 'scissors',
        'scissors' => 'paper',
        'paper' => 'rock'
    ];
    return ($winConditions[$playerChoice] === $opponentChoice) ? "win" : "lose";
}

// Initialize score if not exists
if (!isset($_SESSION['online_score'])) {
    $_SESSION['online_score'] = ['win' => 0, 'lose' => 0, 'tie' => 0];
}

// Handle different result scenarios
if ($opponentChoice !== null) {
    // Normal game result
    $result = determineWinner($choice, $opponentChoice);
    $_SESSION['online_score'][$result]++;
} elseif (!$opponentActive) {
    // Opponent disconnected
    $result = "Opponent disconnected - You win!";
    $_SESSION['online_score']['win']++;
} else {
    // Shouldn't reach here, but just in case
    $result = "Game error occurred";
}

// Reset choices for rematch but keep room active
foreach ($_SESSION['rooms'][$room]['players'] as &$player) {
    $player['choice'] = null;
}

// Store current game in session
$_SESSION['current_game'] = [
    'room' => $room,
    'player_id' => $player_id,
    'opponent' => $opponent
];
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
        .result {
            margin: 1.5rem 0;
        }
        .choice {
            font-weight: bold;
            color: #2c3e50;
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
            color: white;
        }
        .play-again {
            background: #6a11cb;
        }
        .menu {
            background: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Game Result</h2>
        
        <div class="result">
            <p>You chose: <span class="choice"><?= htmlspecialchars($choice) ?></span></p>
            <p>Opponent chose: <span class="choice"><?= htmlspecialchars($opponentChoice ?? 'No response') ?></span></p>
            <h3><?= htmlspecialchars($result) ?></h3>
        </div>
        
        <div class="scoreboard">
            <h4>Current Score</h4>
            <p>Wins: <?= $_SESSION['online_score']['win'] ?? 0 ?></p>
            <p>Losses: <?= $_SESSION['online_score']['lose'] ?? 0 ?></p>
            <p>Ties: <?= $_SESSION['online_score']['tie'] ?? 0 ?></p>
        </div>
        
        <div>
            <a href="play_online.php?room=<?= htmlspecialchars($room) ?>&player_id=<?= htmlspecialchars($player_id) ?>&opponent=<?= urlencode($opponent) ?>" 
               class="btn play-again">Play Again</a>
            <a href="index.php" class="btn menu">Main Menu</a>
        </div>
    </div>
</body>
</html>
