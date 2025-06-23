<?php
session_start();

// Get parameters from POST or session
$room = $_POST['room'] ?? ($_SESSION['current_game']['room'] ?? '');
$player = $_POST['player'] ?? ($_SESSION['current_game']['player_id'] ?? '');
$opponent = $_POST['opponent'] ?? ($_SESSION['current_game']['opponent'] ?? '');
$choice = $_POST['choice'] ?? '';

if (empty($room) || empty($player) || empty($opponent) || empty($choice)) {
    header("Location: online.php?error=missing_parameters");
    exit;
}

// Game logic
if (!isset($_SESSION['rooms'][$room])) {
    header("Location: waiting.php?room=$room&player=$player&opponent=".urlencode($opponent));
    exit;
}

// Update player choice
$_SESSION['rooms'][$room]['players'][$player]['choice'] = $choice;
$_SESSION['rooms'][$room]['players'][$player]['last_active'] = time();

// Check opponent's choice
$opponentChoice = null;
foreach ($_SESSION['rooms'][$room]['players'] as $id => $p) {
    if ($id !== $player && isset($p['choice'])) {
        $opponentChoice = $p['choice'];
    }
}

if ($opponentChoice === null) {
    // Wait for opponent
    header("Refresh: 3");
    ?>
    <!DOCTYPE html>
    <html><body>
        <h2>Waiting for opponent to choose...</h2>
        <p>Page will refresh automatically</p>
    </body></html>
    <?php
    exit;
}

// Determine winner
function determineWinner($p1, $p2) {
    if ($p1 === $p2) return "tie";
    $wins = ['rock'=>'scissors', 'scissors'=>'paper', 'paper'=>'rock'];
    return $wins[$p1] === $p2 ? "win" : "lose";
}

$result = determineWinner($choice, $opponentChoice);

// Update scores
if (!isset($_SESSION['online_score'])) {
    $_SESSION['online_score'] = ['win' => 0, 'lose' => 0, 'tie' => 0];
}
$_SESSION['online_score'][$result]++;

// Reset choices for rematch
foreach ($_SESSION['rooms'][$room]['players'] as &$p) {
    $p['choice'] = null;
}

// Store current game in session
$_SESSION['current_game'] = compact('room', 'player', 'opponent');
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
    <p>You chose: <?= htmlspecialchars($choice) ?></p>
    <p>Opponent chose: <?= htmlspecialchars($opponentChoice) ?></p>
    <h3>You <?= $result ?>!</h3>
    
    <div class="scoreboard">
        <p>Wins: <?= $_SESSION['online_score']['win'] ?></p>
        <p>Losses: <?= $_SESSION['online_score']['lose'] ?></p>
        <p>Ties: <?= $_SESSION['online_score']['tie'] ?></p>
    </div>
    
    <a href="play_online.php?room=<?= $room ?>&id=<?= $player ?>&opponent=<?= urlencode($opponent) ?>">
        Play Again
    </a>
    <a href="index.php">Main Menu</a>
</body>
</html>
