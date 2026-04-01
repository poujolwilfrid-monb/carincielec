<?php
/**
 * Script de nettoyage v3 — supprime le dossier site/ de public_html
 * À SUPPRIMER après utilisation
 */

if (!isset($_GET['token']) || $_GET['token'] !== 'carinci2026cleanup') {
    die('Accès refusé');
}

$publicHtml = dirname(__DIR__);
$siteDir = $publicHtml . '/site';

echo "<h2>Suppression du dossier site/</h2>";

if (is_dir($siteDir)) {
    $it = new RecursiveDirectoryIterator($siteDir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
    $count = 0;
    foreach ($files as $file) {
        if ($file->isDir()) {
            rmdir($file->getRealPath());
        } else {
            unlink($file->getRealPath());
        }
        $count++;
    }
    if (rmdir($siteDir)) {
        echo "<p style='color:green'>✓ Dossier site/ supprimé ($count éléments nettoyés)</p>";
    } else {
        echo "<p style='color:red'>✗ Échec suppression du dossier site/</p>";
    }
} else {
    echo "<p>Dossier site/ introuvable.</p>";
}

echo "<h3>Contenu final de public_html :</h3><ul>";
$remaining = scandir($publicHtml);
foreach ($remaining as $r) {
    if ($r === '.' || $r === '..') continue;
    echo "<li>" . htmlspecialchars($r) . "</li>";
}
echo "</ul>";
