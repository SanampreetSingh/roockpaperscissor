<?php
session_start();
$_SESSION['cpu_score'] = ['win' => 0, 'lose' => 0, 'tie' => 0];
header("Location: play_cpu.php");
exit;
?>
