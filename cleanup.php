<?php
/**
 * Script de nettoyage v5 — vider public_html sauf .private et tmp
 * À SUPPRIMER après utilisation
 */

if (!isset($_GET['token']) || $_GET['token'] !== 'carinci2026cleanup') {
    die('Accès refusé');
}

$publicHtml = dirname(__DIR__);

echo "<h2>Nettoyage v5 — vider public_html</h2>";
echo "<p>Dossier : " . htmlspecialchars($publicHtml) . "</p><ul>";

$deleted = 0;

function deleteRecursive($path) {
    if (is_dir($path)) {
        $it = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir()) rmdir($file->getRealPath());
            else unlink($file->getRealPath());
        }
        return rmdir($path);
    } else {
        return unlink($path);
    }
}

$items = scandir($publicHtml);
foreach ($items as $item) {
    if ($item === '.' || $item === '..') continue;
    if ($item === '.private' || $item === 'tmp') {
        echo "<li style='color:blue'>⏭ Conservé : $item</li>";
        continue;
    }
    $path = $publicHtml . '/' . $item;
    if (deleteRecursive($path)) {
        echo "<li style='color:green'>✓ Supprimé : " . htmlspecialchars($item) . "</li>";
        $deleted++;
    } else {
        echo "<li style='color:red'>✗ Échec : " . htmlspecialchars($item) . "</li>";
    }
}

echo "</ul><p><strong>$deleted éléments supprimés.</strong></p>";

echo "<h3>Contenu final de public_html :</h3><ul>";
foreach (scandir($publicHtml) as $r) {
    if ($r === '.' || $r === '..') continue;
    echo "<li>" . htmlspecialchars($r) . "</li>";
}
echo "</ul>";
echo "<p><strong>Prochaine étape :</strong> supprimer le dépôt GIT 'tmp' sur Hostinger, puis recréer avec répertoire VIDE.</p>";
