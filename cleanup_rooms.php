<?php
foreach (glob("storage/rooms/*.json") as $file) {
    $data = json_decode(file_get_contents($file), true);
    $activePlayers = 0;
    
    foreach ($data['players'] as $p) {
        if (time() - ($p['last_active'] ?? 0) < 3600) { // 1 hour timeout
            $activePlayers++;
        }
    }
    
    if ($activePlayers === 0) {
        unlink($file);
        unlink(str_replace('.json', '.lock', $file));
    }
}
?>
