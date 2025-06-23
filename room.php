<?php
session_start();
$room = preg_replace('/[^a-zA-Z0-9]/', '', $_GET['room']);
$playerId = $_GET['id'];
$roomPath = "storage/rooms/$room.json";

// Check if room file exists
if (!file_exists($roomPath)) {
    header("Location: online.php?error=room_not_found");
    exit;
}

$data = json_decode(file_get_contents($roomPath), true);

// If already two players, redirect to play
if (count($data['players']) === 2) {
    $opponentName = "";
    foreach ($data['players'] as $id => $player) {
        if ($id !== $playerId) {
            $opponentName = $player['name'];
            break;
        }
    }
    header("Location: play_online.php?room=$room&id=$playerId&opponent=".urlencode($opponentName));
    exit;
}

// If creator, show waiting screen
if ($data['creator'] === $playerId) {
    // Check for new joins periodically
    header("Refresh: 5"); // Auto-refresh every 5 seconds
    ?>
    <!DOCTYPE html>
    <html>
    <!-- Waiting room HTML -->
    <div class="waiting-room">
      <h2>Room: <?= htmlspecialchars($room) ?></h2>
      <p>Waiting for opponent to join...</p>
      <p>Share this room ID: <strong><?= $room ?></strong></p>
      <div class="loading-spinner"></div>
      Refresh is automatic
      <a href="index.php" class="game-btn">Cancel</a>
    </div>
    <?php
    exit;
}

// If player is trying to join
$playerData = [
    'name' => $_GET['player_name'] ?? 'Player',
    'choice' => null
];
$data['players'][$playerId] = $playerData;
file_put_contents($roomPath, json_encode($data));

// Redirect to play screen
$creatorName = $data['creator_name'];
header("Location: play_online.php?room=$room&id=$playerId&opponent=".urlencode($creatorName));
exit;
?>
