<?php
$id = $_GET['id'];
$queueFile = 'storage/match.json';
$queue = json_decode(file_get_contents($queueFile), true);

foreach ($queue as $index => $player) {
    if ($player !== $id) {
        // Match found
        unset($queue[$index]);
        file_put_contents($queueFile, json_encode(array_values($queue)));

        // Store match data
        $opponent = $player;
        $playerFile = "storage/$id.json";
        $opponentFile = "storage/$opponent.json";

        file_put_contents($playerFile, json_encode(["status" => "matched", "opponent" => $opponent]));
        file_put_contents($opponentFile, json_encode(["status" => "matched", "opponent" => $id]));

        echo json_encode(["status" => "matched", "opponent" => $opponent]);
        exit;
    }
}

// Not matched yet
echo json_encode(["status" => "waiting"]);
