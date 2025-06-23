<?php
session_start();
$room = $_POST['room'];
$player = $_POST['player'];
$choice = $_POST['choice'];
$opponent = $_POST['opponent'];

$path = "storage/rooms/$room.json";

// Initialize score if not exists
if (!isset($_SESSION['online_score'])) {
    $_SESSION['online_score'] = ['win' => 0, 'lose' => 0, 'tie' => 0];
}

// Check if opponent left
if (!file_exists($path)) {
    header("Location: waiting.php?room=$room&player=$player&opponent=".urlencode($opponent));
    exit;
}

$data = json_decode(file_get_contents($path), true);
$data[$player]['choice'] = $choice;
file_put_contents($path, json_encode($data));

// Wait for opponent's move
$opponentChoice = null;
$wait = 0;

while ($wait < 10) {
    $data = json_decode(file_get_contents($path), true);
    
    foreach ($data as $id => $info) {
        if ($id !== $player && isset($info['choice'])) {
            $opponentChoice = $info['choice'];
            break;
        }
    }
    
    if ($opponentChoice !== null) break;
    sleep(1);
    $wait++;
}

function getResult($you, $them) {
    if ($you === $them) return "tie";
    if (
        ($you == 'rock' && $them == 'scissors') ||
        ($you == 'scissors' && $them == 'paper') ||
        ($you == 'paper' && $them == 'rock')
    ) return "win";
    return "lose";
}

if ($opponentChoice) {
    $result = getResult($choice, $opponentChoice);
    $_SESSION['online_score'][$result]++;
    unlink($path);
} else {
    $result = "Opponent did not respond in time";
}
?>

<!DOCTYPE html>
<html>
<!-- Result display with score and options -->
<div class="result-container">
  <h2>Game Result</h2>
  <p>You chose: <?= htmlspecialchars($choice) ?></p>
  <p>Opponent chose: <?= htmlspecialchars($opponentChoice ?? 'No response') ?></p>
  <h3><?= $result ?></h3>
  
  <div class="scoreboard">
    <h4>Current Score</h4>
    <p>Wins: <?= $_SESSION['online_score']['win'] ?></p>
    <p>Losses: <?= $_SESSION['online_score']['lose'] ?></p>
    <p>Ties: <?= $_SESSION['online_score']['tie'] ?></p>
  </div>
  
  <div class="action-buttons">
    <a href="play_online.php?room=<?= $room ?>&id=<?= $player ?>&opponent=<?= urlencode($opponent) ?>" 
       class="game-btn">Play Again</a>
    <a href="index.php" class="game-btn">Main Menu</a>
  </div>
</div>
