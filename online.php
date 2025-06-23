<!-- Add this CSS in the head section -->
<style>
.join-form, .create-form {
  animation: fadeIn 0.5s ease-out;
}

.join-form {
  margin-bottom: 40px;
}

.form-section {
  background: #f8f9fa;
  padding: 25px;
  border-radius: 10px;
  margin-bottom: 30px;
}

.form-title {
  color: #6a11cb;
  margin-bottom: 20px;
}

.form-row {
  margin-bottom: 20px;
}

.form-btn {
  background: linear-gradient(90deg, #6a11cb, #2575fc);
  color: white;
  border: none;
  padding: 12px 30px;
  border-radius: 30px;
  font-weight: 600;
  cursor: pointer;
  margin-top: 10px;
  transition: all 0.3s;
  width: 100%;
  max-width: 250px;
}

.form-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(106, 17, 203, 0.3);
}
</style>

<!-- Updated form sections -->
<div class="container">
  <h1>Rock Paper Scissors Online</h1>
  
  <div class="form-section join-form">
    <h2 class="form-title">Join Room</h2>
    <form action="join_room.php" method="GET">
      <div class="form-row">
        <input type="text" name="room" placeholder="Room ID" class="form-input" required>
      </div>
      <div class="form-row">
        <input type="text" name="player_name" placeholder="Your Name" class="form-input" required>
      </div>
      <button type="submit" class="form-btn">Join Room</button>
    </form>
  </div>
  
  <div class="form-section create-form">
    <h2 class="form-title">Create Room</h2>
    <form action="create_room.php" method="POST">
      <div class="form-row">
        <input type="text" name="room" placeholder="Room Name" class="form-input" required>
      </div>
      <div class="form-row">
        <input type="text" name="player_name" placeholder="Your Name" class="form-input" required>
      </div>
      <button type="submit" class="form-btn">Create Room</button>
    </form>
  </div>
</div>
