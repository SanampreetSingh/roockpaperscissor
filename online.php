<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Online Multiplayer</title>
    <link rel="stylesheet" href="css/main.css">
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
        }
        .form-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
        input {
            display: block;
            width: 100%;
            padding: 0.8rem;
            margin: 0.5rem 0;
            border: 1px solid #ddd;
            border-radius: 4px;
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
        .error {
            color: #e74c3c;
            margin-bottom: 1rem;
            padding: 0.5rem;
            background: #fdecea;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Online Multiplayer</h1>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="error">
                <?php 
                switch($_GET['error']) {
                    case 'room_not_found': echo "Room not found!"; break;
                    case 'room_full': echo "Room is full!"; break;
                    case 'invalid_input': echo "Invalid input!"; break;
                    default: echo "An error occurred!";
                }
                ?>
            </div>
        <?php endif; ?>
        
        <div class="form-section">
            <h2>Join Room</h2>
            <form action="join_room.php" method="GET">
                <input type="text" name="room" placeholder="Room ID" required maxlength="20">
                <input type="text" name="player_name" placeholder="Your Name" required maxlength="20">
                <button type="submit">Join Room</button>
            </form>
        </div>
        
        <div class="form-section">
            <h2>Create Room</h2>
            <form action="create_room.php" method="POST">
                <input type="text" name="room" placeholder="Room Name" required maxlength="20">
                <input type="text" name="player_name" placeholder="Your Name" required maxlength="20">
                <button type="submit">Create Room</button>
            </form>
        </div>
    </div>
</body>
</html>
