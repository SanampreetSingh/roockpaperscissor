<?php
session_start();

// 1. Validate all required parameters
$required_params = ['room', 'player', 'opponent', 'choice'];
foreach ($required_params as $param) {
    if (!isset($_POST[$param])) {
        header("Location: online.php?error=missing_$param");
        exit;
    }
}

// 2. Sanitize inputs
$room = preg_replace('/[^a-zA-Z0-9]/', '', $_POST['room']);
$player = $_POST['player'];
$choice = $_POST['choice'];
$opponent = $_POST['opponent'];

// 3. File paths and locking
$roomFile = "storage/rooms/$room.lock";
$roomData = "storage/rooms/$room.json";

// 4. Atomic file operations with proper locking
$lock = fopen($roomFile, 'c+');
if (!flock($lock, LOCK_EX)) {
    header("Location: online.php?error=room_busy");
    exit;
}

try {
    // 5. Load and validate room data
    if (!file_exists($roomData)) {
        header("Location: online.php?error=room_expired");
        exit;
    }
    
    $data = json_decode(file_get_contents($roomData), true);
    if (json_last_error() !== JSON_ERROR_NONE || !isset($data['players'])) {
        header("Location: online.php?error=invalid_room_data");
        exit;
    }

    // 6. Update current player's choice
    if (!isset($data['players'][$player])) {
        header("Location: online.php?error=player_not_found");
        exit;
    }
    
    $data['players'][$player]['choice'] = $choice;
    $data['players'][$player]['last_active'] = time();

    // 7. Check opponent's status
    $opponentChoice = null;
    $opponentActive = false;
    
    foreach ($data['players'] as $id => $playerData) {
        if ($id !== $player) {
            if (isset($playerData['choice'])) {
                $opponentChoice = $playerData['choice'];
            }
            if (time() - ($playerData['last_active'] ?? 0) < 30) {
                $opponentActive = true;
            }
        }
    }

    // 8. Determine game outcome
    if ($opponentChoice !== null) {
        // Both players have submitted
        $result = determineWinner($choice, $opponentChoice);
    } elseif (!$opponentActive) {
        // Opponent disconnected
        $result = "Opponent disconnected - You win!";
    } else {
        // Opponent hasn't submitted yet
        $result = "Waiting for opponent...";
        file_put_contents($roomData, json_encode($data));
        header("Refresh: 3"); // Auto-refresh in 3 seconds
        ?>
        <!DOCTYPE html>
        <html>
        <head><title>Waiting</title></head>
        <body>
            <h2>Waiting for opponent to submit choice...</h2>
            <p>This page will refresh automatically</p>
        </body>
        </html>
        <?php
        exit;
    }

    // 9. Update scores (only when game is complete)
    if (!isset($_SESSION['online_score'])) {
        $_SESSION['online_score'] = ['win' => 0, 'lose' => 0, 'tie' => 0];
    }
    
    if ($result === "You win!") {
        $_SESSION['online_score']['win']++;
    } elseif ($result === "You lose!") {
        $_SESSION['online_score']['lose']++;
    } elseif (strpos($result, "win") !== false) {
        $_SESSION['online_score']['win']++;
    } else {
        $_SESSION['online_score']['tie']++;
    }

    // 10. Clean up completed game
    if ($opponentChoice !== null || !$opponentActive) {
        unlink($roomData);
    } else {
        file_put_contents($roomData, json_encode($data));
    }

} finally {
    flock($lock, LOCK_UN);
    fclose($lock);
}

function determineWinner($playerChoice, $opponentChoice) {
    if ($playerChoice === $opponentChoice) return "It's a tie!";
    $wins = [
        'rock' => 'scissors',
        'scissors' => 'paper', 
        'paper' => 'rock'
    ];
    return $wins[$playerChoice] === $opponentChoice ? "You win!" : "You lose!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Game Result</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
        .result { margin: 20px 0; font-size: 1.2em; }
        .choice { font-weight: bold; color: #2c3e50; }
        .scoreboard { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px auto; max-width: 300px; }
        .btn { display: inline-block; padding: 10px 20px; margin: 10px; background: #2c3e50; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
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
        <a href="play_online.php?room=<?= $room ?>&id=<?= $player ?>&opponent=<?= urlencode($opponent) ?>" class="btn">Play Again</a>
        <a href="index.php" class="btn">Main Menu</a>
    </div>
</body>
</html>
