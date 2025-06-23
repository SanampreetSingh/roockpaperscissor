<?php
session_start();
$_SESSION['online_score'] = ['win' => 0, 'lose' => 0, 'tie' => 0];
header("Location: play_online.php?".http_build_query($_SESSION['current_game'] ?? []));
exit;
?>
