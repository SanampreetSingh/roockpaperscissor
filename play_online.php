<?php
session_start();

$room = preg_replace('/[^a-zA-Z0-9]/', '', $_GET['room'] ?? '');
$player = $_GET['id'] ?? '';
$opponent = $_GET['opponent'] ?? '';

if (empty($room) || empty($player) || empty($opponent)) {
    header("Location: online.php?error=invalid_parameters");
    exit;
}

// Verify room exists
if (!isset($_SESSION['rooms'][$room])) {
    header("Location: waiting.php?room=$room&player=$player&opponent=".urlencode($opponent));
    exit;
}

// Store game in session
$_SESSION['current_game'] = [
    'room' => $room,
    'player_id' => $player,
    'opponent' => $opponent
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rock Paper Scissors</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
        .choices { display: flex; justify-content: center; gap: 20px; margin: 30px 0; }
        .choice { 
            padding: 15px 25px; 
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .choice:hover {
            border-color: #6a11cb;
            transform: scale(1.05);
        }
        input[type="radio"] { display: none; }
        input[type="radio"]:checked + .choice {
            border-color: #6a11cb;
            background: rgba(106, 17, 203, 0.1);
        }
        .btn { 
            display: inline-block;
            padding: 10px 20px;
            background: #6a11cb;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px;
        }
    </style>
</head>
<body>
    <h1>Playing against <?= htmlspecialchars($opponent) ?></h1>
    
    <form action="result_online.php" method="POST">
        <input type="hidden" name="room" value="<?= htmlspecialchars($room) ?>">
        <input type="hidden" name="player" value="<?= htmlspecialchars($player) ?>">
        <input type="hidden" name="opponent" value="<?= htmlspecialchars($opponent) ?>">
        
        <div class="choices">
            <label>
                <input type="radio" name="choice" value="rock" required>
                <div class="choice">✊ Rock</div>
            </label>
            <label>
                <input type="radio" name="choice" value="paper">
                <div class="choice">✋ Paper</div>
            </label>
            <label>
                <input type="radio" name="choice" value="scissors">
                <div class="choice">✌️ Scissors</div>
            </label>
        </div>
        
        <button type="submit" class="btn">Submit Move</button>
        <a href="index.php" class="btn">Main Menu</a>
    </form>
</body>
</html>
