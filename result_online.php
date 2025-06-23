<?php
session_start();

// Ensure the required POST parameters are set
if (!isset($_POST['room'], $_POST['player'], $_POST['opponent'], $_POST['choice'])) {
    header("Location: online.php?error=invalid_parameters");
    exit;
}

$room = $_POST['room'];
$player = $_POST['player'];
$choice = $_POST['choice'];
$opponent = $_POST['opponent'];

// File paths
$roomData = "storage/rooms/$room.json";

// Check if room exists
if (!file_exists($roomData)) {
    header("Location: online.php?error=room_not_found");
    exit;
}

// Load room data
$data = json_decode(file_get_contents($roomData), true);

// Initialize variables
$opponentChoice = null;
$result = null;

// Check if opponent has made a choice
foreach ($data['players'] as $id => $playerData) {
    if ($id !== $player && isset($playerData['choice'])) {
        $opponentChoice = $playerData['choice'];
        break;
    }
}

// Determine the result
if ($opponentChoice) {
    // Both players made a choice
    $result = determineWinner($choice, $opponentChoice);
} else {
    // Opponent did not respond
    $result = "Opponent timed out - You win!";
    $_SESSION['online_score']['win'] = ($_SESSION['online_score']['win'] ?? 0) + 1;
}

// Update score
if (!isset($_SESSION['online_score'])) {
    $_SESSION['online_score'] = ['win' => 0, 'lose' => 0, 'tie' => 0];
}

if ($result === "You win!") {
    $_SESSION['online_score']['win']++;
} elseif ($result === "You lose!") {
    $_SESSION['online_score']['lose']++;
} else {
    $_SESSION['online_score']['tie']++;
}

// Clean up room data for the next round
foreach ($data['players'] as &$playerData) {
    $playerData['choice'] = null; // Reset choices for the next round
}
file_put_contents($roomData, json_encode($data));

function determineWinner($playerChoice, $opponentChoice) {
    if ($playerChoice === $opponentChoice) {
        return "It's a tie!";
    }
    if (
        ($playerChoice === 'rock' && $opponentChoice === 'scissors') ||
        ($playerChoice === 'scissors' && $opponentChoice === 'paper') ||
        ($playerChoice === 'paper' && $opponentChoice === 'rock')
    ) {
        return "You win!";
    }
    return "You lose!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Result</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/game.css">
</head>
<body>
    <div class="container">
        <h2>Game Result</h2>
        <p>You chose: <strong><?= htmlspecialchars($choice) ?></strong></p>
        <p>Opponent chose: <strong><?= htmlspecialchars($opponentChoice ?? 'No response') ?></strong></p>
        <h3><?= $result ?></h3>

        <div class="scoreboard">
            <h4>Current Score</h4>
            <p>Wins: <?= $_SESSION['online_score']['win'] ?></p>
            <p>Losses: <?= $_SESSION['online_score']['lose'] ?></p>
            <p>Ties: <?= $_SESSION['online_score']['tie'] ?></p>
        </div>

        <div class="action-buttons">
            <a href="play_online.php?room=<?= htmlspecialchars($room) ?>&id=<?= htmlspecialchars($player) ?>&opponent=<?= urlencode($opponent) ?>" class="game-btn">Play Again</a>
            <a href="index.php" class="game-btn">Main Menu</a>
        </div>
    </div>
</body>
</html>
