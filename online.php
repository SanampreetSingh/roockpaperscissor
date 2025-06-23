<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rock Paper Scissors Online</title>
    <style>
       .form-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 20px;
    }
    
    .form-section {
      width: 100%;
      max-width: 500px;
      margin: 20px 0;
      animation: slideUp 0.5s ease-out;
    }
    
    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Rock Paper Scissors Online</h1>
    
        <div class="form-section join-form">
            <h2>Join Room</h2>
            <form action="join_room.php" method="GET">
                <input type="text" name="room" placeholder="Room ID" required>
                <input type="text" name="player_name" placeholder="Your Name" required>
                <button type="submit">Join Room</button>
            </form>
        </div>
        
        <div class="form-section create-form">
            <h2>Create Room</h2>
            <form action="create_room.php" method="POST">
                <input type="text" name="room" placeholder="Room Name" required>
                <input type="text" name="player_name" placeholder="Your Name" required>
                <button type="submit">Create Room</button>
            </form>
        </div>
    </div>
</body>
</html>
