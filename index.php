<?php
session_start();

// Clean up old rooms (older than 1 hour)
if (isset($_SESSION['rooms'])) {
    foreach ($_SESSION['rooms'] as $room => $data) {
        if (time() - ($data['created_at'] ?? 0) > 3600) {
            unset($_SESSION['rooms'][$room]);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rock Paper Scissors</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 90%;
            max-width: 500px;
        }
        .game-options {
            margin-top: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .game-btn {
            display: block;
            padding: 1rem;
            background: #6a11cb;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s;
        }
        .game-btn:hover {
            background: #5a0db3;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Rock Paper Scissors</h1>
        <div class="game-options">
            <a href="play_cpu.php" class="game-btn">Play vs Computer</a>
            <a href="online.php" class="game-btn">Online Multiplayer</a>
        </div>
    </div>
</body>
</html>
