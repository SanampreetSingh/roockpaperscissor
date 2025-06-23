<?php
session_start();

// Reset the CPU score
if (isset($_SESSION['cpu_score'])) {
    $_SESSION['cpu_score'] = ['win' => 0, 'lose' => 0, 'tie' => 0];
}

// Redirect back to the play CPU page
header("Location: play_cpu.php");
exit;
?>
