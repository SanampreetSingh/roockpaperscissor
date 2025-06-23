<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Play vs Computer</title>
    <link rel="stylesheet" href="css/game.css">
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
        button {
            background: #6a11cb;
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 4px;
            margin-top: 1rem;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Play Against Computer</h1>
        <form action="result_cpu.php" method="POST">
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
            <button type="submit">Play</button>
        </form>
    </div>
</body>
</html>
