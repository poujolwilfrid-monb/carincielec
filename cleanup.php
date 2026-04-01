<?php
if (!isset($_GET['token']) || $_GET['token'] !== 'carinci2026cleanup') die('Accès refusé');

echo "<h2>Sync fichiers depuis GitHub</h2>";

$files = [
    'styles.css',
    'index.html',
    'services.html',
    'contact.html',
    '404.html',
    'mentions-legales.html',
    'politique-de-confidentialite.html',
    'merci.html',
    '.htaccess',
    'script.js',
    'electricien-porto-vecchio.html',
    'electricien-bonifacio.html',
    'electricien-propriano.html',
    'electricien-sartene.html',
    'electricien-zonza.html',
    'sitemap.xml',
    'robots.txt',
];

$baseUrl = 'https://raw.githubusercontent.com/poujolwilfrid-monb/carincielec/master/';
$dir = __DIR__;
$ok = 0;
$fail = 0;

echo "<ul>";
foreach ($files as $file) {
    $url = $baseUrl . $file;
    $content = @file_get_contents($url);
    if ($content !== false) {
        if (file_put_contents($dir . '/' . $file, $content)) {
            echo "<li style='color:green'>✓ $file</li>";
            $ok++;
        } else {
            echo "<li style='color:red'>✗ Écriture échouée : $file</li>";
            $fail++;
        }
    } else {
        echo "<li style='color:red'>✗ Téléchargement échoué : $file</li>";
        $fail++;
    }
}
echo "</ul>";
echo "<p><strong>$ok fichiers mis à jour, $fail erreurs.</strong></p>";
