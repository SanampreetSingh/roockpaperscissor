<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Multiplayer</title>
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/online.css">
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
    
    <div class="form-section join-form" style="animation-delay: 0.2s;">
      <h2>Join Existing Room</h2>
      <form action="join_room.php" method="GET">
        <input type="text" name="room" placeholder="Room ID" required>
        <input type="text" name="player_name" placeholder="Your Name" required>
        <button type="submit">Join Room</button>
      </form>
    </div>
    
    <div class="form-section create-form" style="animation-delay: 0.4s;">
      <h2>Create New Room</h2>
      <form action="create_room.php" method="POST">
        <input type="text" name="room" placeholder="Room Name" required>
        <input type="text" name="player_name" placeholder="Your Name" required>
        <button type="submit">Create Room</button>
      </form>
    </div>
  </div>
</body>
</html>
