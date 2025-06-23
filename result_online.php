<?php
session_start();

// Validate all required parameters
$required_params = ['room', 'player', 'opponent', 'choice'];
foreach ($required_params as $param) {
    if (!isset($_POST[$param])) {
        header("Location: online.php?error=missing_$param");
        exit;
    }
}

$room = preg_replace('/[^a-zA-Z0-9]/', '', $_POST['room']);
$player = $_POST['player'];
$choice = $_POST['choice'];
$opponent = $_POST['opponent'];

$roomFile = "storage/rooms/$room.lock";
$roomData = "storage/rooms/$room.json";

if (!file_exists($roomData)) {
    header("Location: waiting.php?room=$room&player=$player&opponent=".urlencode($opponent));
    exit;
}

$lock = fopen($roomFile, 'c+');
if (!flock($lock, LOCK_EX)) {
    header("Location: online.php?error=room_busy");
    exit;
}

try {
    $data = json_decode(file_get_contents($roomData), true);
    
    // Reset choices for new game
    $data['players'][$player]['choice'] = $choice;
    $data['players'][$player]['last_active'] = time();
    
    // Check opponent's choice
    $opponentChoice = null;
    foreach ($data['players'] as $id => $p) {
        if ($id !== $player && isset($p['choice'])) {
            $opponentChoice = $p['choice'];
            break;
        }
    }

    if ($opponentChoice === null) {
        // Save and wait for opponent
        file_put_contents($roomData, json_encode($data));
        header("Refresh: 3");
        ?>
        <!DOCTYPE html>
        <html>
        <head><title>Waiting</title></head>
        <body>
            <h2>Waiting for opponent to choose...</h2>
            <p>This page will refresh automatically</p>
        </body>
        </html>
        <?php
        exit;
    }

    // Determine winner
    $result = determineWinner($choice, $opponentChoice);
    
    // Update scores
    if (!isset($_SESSION['online_score'])) {
        $_SESSION['online_score'] = ['win' => 0, 'lose' => 0, 'tie' => 0];
    }
    
    switch ($result) {
        case "win": $_SESSION['online_score']['win']++; break;
        case "lose": $_SESSION['online_score']['lose']++; break;
        default: $_SESSION['online_score']['tie']++;
    }
    
    // Reset choices for rematch but keep room active
    foreach ($data['players'] as &$p) {
        $p['choice'] = null;
    }
    file_put_contents($roomData, json_encode($data));

} finally {
    flock($lock, LOCK_UN);
    fclose($lock);
}

function determineWinner($p1, $p2) {
    if ($p1 === $p2) return "tie";
    $wins = ['rock'=>'scissors', 'scissors'=>'paper', 'paper'=>'rock'];
    return $wins[$p1] === $p2 ? "win" : "lose";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Game Result</title>
    <style>
        /* Your existing styles here */
    </style>
</head>
<body>
    <!-- Your existing result display here -->
    <!-- Important: Update Play Again link to include all parameters -->
    <a href="play_online.php?room=<?= $room ?>&id=<?= $player ?>&opponent=<?= urlencode($opponent) ?>" 
       class="btn">Play Again</a>
</body>
</html>
