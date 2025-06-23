<?php
$playerId = uniqid();
file_put_contents("storage/$playerId.json", json_encode(["status" => "waiting", "opponent" => null]));

// Add player to queue
$queueFile = 'storage/match.json';
$queue = file_exists($queueFile) ? json_decode(file_get_contents($queueFile), true) : [];

$queue[] = $playerId;
file_put_contents($queueFile, json_encode($queue));
?>

<!DOCTYPE html>
<html>
<head>
  <title>Online Match</title>
  <script>
    const playerId = "<?= $playerId ?>";

    setInterval(() => {
      fetch("check_match.php?id=" + playerId)
        .then(res => res.json())
        .then(data => {
          if (data.status === "matched") {
            window.location.href = "play_online.php?id=" + playerId + "&opponent=" + data.opponent;
          }
        });
    }, 1000);
  </script>
</head>
<body>
  <h2>Finding an opponent...</h2>
</body>
</html>
