<!-- Add this CSS -->
<style>
.choice-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 15px;
}

@media (min-width: 768px) {
  .choice-container {
    flex-direction: row;
    justify-content: center;
  }
}

.choice {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 15px;
  border: 2px solid #ddd;
  border-radius: 50%;
  width: 80px;
  height: 80px;
  cursor: pointer;
  transition: all 0.3s;
  font-size: 40px;
  margin: 5px;
}

.choice:hover {
  border-color: #6a11cb;
  transform: scale(1.1);
}

input[type="radio"]:checked + .choice {
  border-color: #6a11cb;
  background-color: rgba(106, 17, 203, 0.1);
}
</style>

<!-- Updated choices section -->
<div class="choice-container">
  <label>
    <input type="radio" name="choice" value="rock" required>
    <span class="choice">✊</span>
  </label>
  <label>
    <input type="radio" name="choice" value="paper">
    <span class="choice">✋</span>
  </label>
  <label>
    <input type="radio" name="choice" value="scissors">
    <span class="choice">✌️</span>
  </label>
</div>
