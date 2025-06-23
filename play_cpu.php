<?php
session_start();

// Initialize score if not exists
if (!isset($_SESSION['cpu_score'])) {
    $_SESSION['cpu_score'] = ['win' => 0, 'lose' => 0, 'tie' => 0];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Play vs Computer</title>
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
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 90%;
            max-width: 500px;
            text-align: center;
        }
        .choices {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin: 2rem 0;
            flex-wrap: wrap;
        }
        label {
            cursor: pointer;
        }
        .choice {
            padding: 1.5rem;
            border: 2px solid #ddd;
            border-radius: 10px;
            transition: all 0.3s;
            min-width: 100px;
        }
        .choice:hover {
            border-color: #6a11cb;
            transform: scale(1.05);
        }
        input[type="radio"]:checked + .choice {
            border-color: #6a11cb;
            background-color: rgba(106, 17, 203, 0.1);
        }
        button[type="submit"] {
            background: #6a11cb;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 50px;
            margin-top: 2rem;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
            width: 100%;
            max-width: 200px;
        }
        button[type="submit"]:hover {
            background: #5a0db3;
            transform: translateY(-2px);
        }
        .score-display {
            margin: 1rem 0;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Battle the Computer</h1>
        
        <div class="score-display">
            <h3>Current Score</h3>
            <p>Wins: <?= $_SESSION['cpu_score']['win'] ?> | 
               Losses: <?= $_SESSION['cpu_score']['lose'] ?> | 
               Ties: <?= $_SESSION['cpu_score']['tie'] ?></p>
        </div>
        
        <form action="result_cpu.php" method="POST" onsubmit="return validateSelection()">
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
            
            <button type="submit">PLAY</button>
        </form>
        
        <p style="margin-top: 2rem;">
            <a href="reset_cpu.php" style="color: #e74c3c;">Reset Score</a> | 
            <a href="index.php">Main Menu</a>
        </p>
    </div>

    <script>
        function validateSelection() {
            const selected = document.querySelector('input[name="choice"]:checked');
            if (!selected) {
                alert('Please select Rock, Paper, or Scissors first!');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
