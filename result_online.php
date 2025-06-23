<?php
session_start();

// Implement file locking
$room = $_POST['room'];
$lockFile = "storage/rooms/$room.lock";
$lock = fopen($lockFile, 'c');
flock($lock, LOCK_EX);

try {
    $roomData = "storage/rooms/$room.json";
    $data = json_decode(file_get_contents($roomData), true);
    
    // Store player move
    $player = $_POST['player'];
    $data['players'][$player] = [
        'choice' => $_POST['choice'],
        'last_active' => time()
    ];

    // Wait for opponent's move (max 10 seconds)
    $opponentFound = false;
    for ($i = 0; $i < 5; $i++) {
        foreach ($data['players'] as $id => $p) {
            if ($id !== $player && isset($p['choice'])) {
                $opponentFound = true;
                break 2;
            }
        }
        sleep(2);
        clearstatcache();
        $data = json_decode(file_get_contents($roomData), true);
    }

    if (!$opponentFound) {
        // Opponent timeout - award win
        $_SESSION['online_score']['win'] = ($_SESSION['online_score']['win'] ?? 0) + 1;
        unlink($roomData);
        ?>
        <h3>Opponent timed out - You win!</h3>
        <?php
    } else {
        // Normal game resolution
        $result = determineWinner($data['players'][$player]['choice'], 
                                $data['players'][array_keys($data['players'])[0]]['choice']);
        $_SESSION['online_score'][$result]++;
        ?>
        <h3>You <?= $result ?>!</h3>
        <?php
    }
    
    // Reset room for rematch
    foreach ($data['players'] as &$p) {
        $p['choice'] = null;
    }
    file_put_contents($roomData, json_encode($data));
    
} finally {
    flock($lock, LOCK_UN);
    fclose($lock);
}

function determineWinner($p1, $p2) {
    if ($p1 === $p2) return 'tied';
    $wins = ['rock' => 'scissors', 'paper' => 'rock', 'scissors' => 'paper'];
    return $wins[$p1] === $p2 ? 'won' : 'lost';
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
