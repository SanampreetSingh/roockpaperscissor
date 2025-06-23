<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Multiplayer</title>
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/online.css">
</head>
<body>
  <div class="container">
    <h1>Online Multiplayer</h1>
    
    <section class="join-form">
      <h2>Join Existing Room</h2>
      <form action="join_room.php" method="GET">
        <div class="input-group">
          <input type="text" name="room" placeholder="Room ID" required>
        </div>
        <div class="input-group">
          <input type="text" name="player_name" placeholder="Your Name" required>
        </div>
        <div class="btn-group">
          <button type="submit" class="game-btn">Join Room</button>
        </div>
        <?php if (isset($_GET['error'])): ?>
          <p class="error-message">
            <?php 
              echo match($_GET['error']) {
                'room_not_found' => 'Room not found!',
                'room_full' => 'Room is full!',
                default => 'Error joining room'
              };
            ?>
          </p>
        <?php endif; ?>
      </form>
    </section>
    
    <section class="create-form">
      <h2>Create New Room</h2>
      <form action="create_room.php" method="POST">
        <div class="input-group">
          <input type="text" name="room" placeholder="Room Name" required>
        </div>
        <div class="input-group">
          <input type="text" name="player_name" placeholder="Your Name" required>
        </div>
        <div class="btn-group">
          <button type="submit" class="game-btn">Create Room</button>
        </div>
      </form>
    </section>
  </div>
</body>
</html>
