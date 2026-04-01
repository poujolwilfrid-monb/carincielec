<?php
/**
 * Script de migration v4 — déplace les fichiers de deploy/ vers public_html/ et nettoie
 * À SUPPRIMER après utilisation
 */

if (!isset($_GET['token']) || $_GET['token'] !== 'carinci2026cleanup') {
    die('Accès refusé');
}

$publicHtml = dirname(__DIR__);
$siteDir = $publicHtml . '/site';
$deployDir = $publicHtml . '/deploy';

echo "<h2>Migration v4 — deploy/ → public_html/</h2>";

// Étape 1 : supprimer l'ancien dossier site/
if (is_dir($siteDir)) {
    $it = new RecursiveDirectoryIterator($siteDir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $file) {
        if ($file->isDir()) rmdir($file->getRealPath());
        else unlink($file->getRealPath());
    }
    rmdir($siteDir);
    echo "<p style='color:green'>✓ Ancien dossier site/ supprimé</p>";
} else {
    echo "<p>Pas de dossier site/ à supprimer</p>";
}

// Étape 2 : copier tout de deploy/ vers public_html/
if (is_dir($deployDir)) {
    $count = 0;
    $it = new RecursiveDirectoryIterator($deployDir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it);
    foreach ($files as $file) {
        $relativePath = substr($file->getRealPath(), strlen($deployDir) + 1);
        $destPath = $publicHtml . '/' . $relativePath;
        $destDir = dirname($destPath);
        if (!is_dir($destDir)) mkdir($destDir, 0755, true);
        copy($file->getRealPath(), $destPath);
        $count++;
    }
    echo "<p style='color:green'>✓ $count fichiers copiés de deploy/ vers public_html/</p>";

    // Supprimer le dossier deploy/
    $it2 = new RecursiveDirectoryIterator($deployDir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files2 = new RecursiveIteratorIterator($it2, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files2 as $file) {
        if ($file->isDir()) rmdir($file->getRealPath());
        else unlink($file->getRealPath());
    }
    rmdir($deployDir);
    echo "<p style='color:green'>✓ Dossier deploy/ supprimé</p>";
} else {
    echo "<p style='color:red'>✗ Dossier deploy/ introuvable</p>";
}

// Vérification finale
echo "<h3>Contenu de public_html :</h3><ul>";
$remaining = scandir($publicHtml);
foreach ($remaining as $r) {
    if ($r === '.' || $r === '..') continue;
    $isDir = is_dir($publicHtml . '/' . $r) ? ' (dossier)' : '';
    echo "<li>" . htmlspecialchars($r) . $isDir . "</li>";
}
echo "</ul>";
echo "<p><strong>Migration terminée !</strong> Le site devrait maintenant fonctionner directement depuis public_html/.</p>";
