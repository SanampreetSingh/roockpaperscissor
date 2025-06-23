<?php
session_start();
$room = $_POST['room'];
$player = $_POST['player'];
$choice = $_POST['choice'];

$path = "storage/rooms/$room.json";
$data = json_decode(file_get_contents($path), true);

// Save this player's choice
$data[$player]['choice'] = $choice;
file_put_contents($path, json_encode($data));

// Wait for second player
$opponentChoice = null;
$wait = 0;

while ($wait < 10) {
    $data = json_decode(file_get_contents($path), true);
    $otherPlayer = null;

    foreach ($data['players'] as $id => $info) {
        if ($id !== $player && $info['choice'] !== null) {
            $opponentChoice = $info['choice'];
            $otherPlayer = $id;
            break;
        }
    }

    if ($opponentChoice !== null) break;
    sleep(1);
    $wait++;
}

function getResult($you, $them) {
    if ($you === $them) return "It's a tie!";
    if (
        ($you == 'rock' && $them == 'scissors') ||
        ($you == 'scissors' && $them == 'paper') ||
        ($you == 'paper' && $them == 'rock')
    ) return "You win!";
    return "You lose!";
}

if ($opponentChoice) {
    $result = getResult($choice, $opponentChoice);
    unlink("storage/rooms/$room.json"); // Clean room
} else {
    $result = "Opponent did not respond in time.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Match Result</title>
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/game.css">
</head>
<body>
  <div class="container">
    <h2>Online Game Result</h2>
    <p>You chose: <strong><?= htmlspecialchars($choice) ?></strong></p>
    <p>Opponent chose: <strong><?= htmlspecialchars($opponentChoice ?? 'No response') ?></strong></p>
    <h3><?= $result ?></h3>
    <div class="btn-group">
      <a href="index.php" class="game-btn">Back to Main Menu</a>
    </div>
  </div>
</body>
</html>
