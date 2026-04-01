<?php
/**
 * Script v6 — créer .htaccess de rewrite à la racine de public_html + nettoyer tmp
 * À SUPPRIMER après utilisation
 */

if (!isset($_GET['token']) || $_GET['token'] !== 'carinci2026cleanup') {
    die('Accès refusé');
}

$publicHtml = dirname(__DIR__);

echo "<h2>Configuration v6</h2>";

// Étape 1 : supprimer le dossier tmp s'il existe
$tmpDir = $publicHtml . '/tmp';
if (is_dir($tmpDir)) {
    $it = new RecursiveDirectoryIterator($tmpDir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $file) {
        if ($file->isDir()) rmdir($file->getRealPath());
        else unlink($file->getRealPath());
    }
    rmdir($tmpDir);
    echo "<p style='color:green'>✓ Dossier tmp/ supprimé</p>";
}

// Étape 2 : créer le .htaccess à la racine de public_html
$htaccess = $publicHtml . '/.htaccess';
$content = 'RewriteEngine On
RewriteCond %{REQUEST_URI} !^/live/
RewriteRule ^(.*)$ /live/$1 [L]
';

if (file_put_contents($htaccess, $content)) {
    echo "<p style='color:green'>✓ .htaccess créé dans public_html (rewrite vers /live/)</p>";
} else {
    echo "<p style='color:red'>✗ Échec création .htaccess</p>";
}

// Vérification
echo "<h3>Contenu de public_html :</h3><ul>";
foreach (scandir($publicHtml) as $r) {
    if ($r === '.' || $r === '..') continue;
    echo "<li>" . htmlspecialchars($r) . "</li>";
}
echo "</ul>";
echo "<p><strong>Terminé !</strong> Testez https://carincielec.com — puis supprimez cleanup.php du repo.</p>";
