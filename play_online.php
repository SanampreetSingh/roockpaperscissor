<?php
$player = $_GET['id'];
$opponent = $_GET['opponent'];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Online Game</title>
</head>
<body>
  <h1>You're playing against <?= $opponent ?></h1>
  <form action="result_online.php" method="POST">
    <input type="hidden" name="player" value="<?= $player ?>">
    <input type="hidden" name="opponent" value="<?= $opponent ?>">
    <label>Choose:</label>
    <input type="radio" name="choice" value="rock" required> Rock
    <input type="radio" name="choice" value="paper"> Paper
    <input type="radio" name="choice" value="scissors"> Scissors
    <button type="submit">Submit</button>
  </form>
</body>
</html>
