<?php
session_start();

if (!isset($_GET['room'], $_GET['id'], $_GET['opponent'])) {
    header("Location: online.php?error=invalid_parameters");
    exit;
}

$room = preg_replace('/[^a-zA-Z0-9]/', '', $_GET['room']);
$player = $_GET['id'];
$opponent = $_GET['opponent'];

// Verify room exists
$roomData = "storage/rooms/$room.json";
if (!file_exists($roomData)) {
    header("Location: waiting.php?room=$room&player=$player&opponent=".urlencode($opponent));
    exit;
}

// Store game info in session
$_SESSION['current_game'] = compact('room', 'player', 'opponent');
?>
<!-- Rest of your existing HTML -->

?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Game</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/game.css">
    <style>
        .choice-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 30px 0;
        }
        .choice {
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
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
            background: rgba(106,17,203,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Playing against <?= htmlspecialchars($opponent) ?></h1>
        
        <form action="result_online.php" method="POST" id="gameForm">
            <input type="hidden" name="room" value="<?= htmlspecialchars($room) ?>">
            <input type="hidden" name="player" value="<?= htmlspecialchars($player) ?>">
            <input type="hidden" name="opponent" value="<?= htmlspecialchars($opponent) ?>">
            
            <div class="choice-container">
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
            
            <div class="action-buttons">
                <button type="submit" class="game-btn">Submit Move</button>
                <a href="index.php" class="game-btn">Main Menu</a>
            </div>
        </form>
    </div>

    <script>
        // Ensure form submits all required parameters
        document.getElementById('gameForm').addEventListener('submit', function() {
            const selectedChoice = document.querySelector('input[name="choice"]:checked');
            if (!selectedChoice) {
                alert('Please select a move first!');
                return false;
            }
            return true;
        });
    </script>
</body>
</html>
