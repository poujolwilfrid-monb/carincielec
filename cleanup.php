<?php
if (!isset($_GET['token']) || $_GET['token'] !== 'carinci2026cleanup') die('Accès refusé');

// Force git reset dans le dossier live
$liveDir = __DIR__;
echo "<h2>Force reset git</h2>";
echo "<pre>";
echo shell_exec('cd ' . escapeshellarg($liveDir) . ' && git fetch origin 2>&1');
echo shell_exec('cd ' . escapeshellarg($liveDir) . ' && git reset --hard origin/master 2>&1');
echo "</pre>";
echo "<p>Terminé. Vérifiez le site.</p>";
