<?php
session_start();

$room = substr(preg_replace('/[^a-zA-Z0-9]/', '', $_GET['room'] ?? ''), 0, 20);
$player_id = $_GET['player_id'] ?? '';
$opponent = $_GET['opponent'] ?? '';

if (empty($room) || empty($player_id) || empty($opponent) || !isset($_SESSION['rooms'][$room])) {
    header("Location: online.php?error=invalid_parameters");
    exit;
}

// Store game in session
$_SESSION['current_game'] = [
    'room' => $room,
    'player_id' => $player_id,
    'opponent' => $opponent
];

// Update activity
if (isset($_SESSION['rooms'][$room]['players'][$player_id])) {
    $_SESSION['rooms'][$room]['players'][$player_id]['last_active'] = time();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Play Online</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 90%;
            max-width: 500px;
            text-align: center;
        }
        .choices {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin: 2rem 0;
        }
        .choice {
            padding: 1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .choice:hover {
            border-color: #6a11cb;
        }
        input[type="radio"] {
            display: none;
        }
        input[type="radio"]:checked + .choice {
            border-color: #6a11cb;
            background: rgba(106, 17, 203, 0.1);
        }
        button {
            background: #6a11cb;
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 4px;
            margin-top: 1rem;
            cursor: pointer;
        }
        a {
            display: inline-block;
            margin-top: 1rem;
            color: #6a11cb;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Playing against <?= htmlspecialchars($opponent) ?></h1>
        <form action="result_online.php" method="POST">
            <input type="hidden" name="room" value="<?= htmlspecialchars($room) ?>">
            <input type="hidden" name="player_id" value="<?= htmlspecialchars($player_id) ?>">
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
            
            <button type="submit">Submit Move</button>
            <a href="index.php">Main Menu</a>
        </form>
    </div>
</body>
</html>
