<?php
/**
 * Script de nettoyage temporaire — supprime les anciens fichiers de public_html
 * À SUPPRIMER après utilisation
 */

// Sécurité : token simple pour éviter l'exécution par n'importe qui
if (!isset($_GET['token']) || $_GET['token'] !== 'carinci2026cleanup') {
    die('Accès refusé');
}

$publicHtml = dirname(__DIR__); // remonte de /site/ vers /public_html/

// Liste des anciens fichiers à supprimer dans public_html
$oldFiles = [
    'Borne électrique  .webp',
    'Caméra (800 x 600 px).svg',
    'Dépannage.webp',
    'Eclairage extérieur.webp',
    'Personnage tire câble.svg',
    'Personnage téléphone.png',
    'Personnages tire câble.png',
    'Rénovation(800 x 600 px).svg',
    'Téléphone.json',
];

// Supprimer le dossier _backup
$backupDir = $publicHtml . '/_backup';

echo "<h2>Nettoyage de public_html</h2>";
echo "<p>Dossier cible : " . htmlspecialchars($publicHtml) . "</p>";
echo "<ul>";

// Supprimer les anciens fichiers
foreach ($oldFiles as $file) {
    $path = $publicHtml . '/' . $file;
    if (file_exists($path)) {
        if (unlink($path)) {
            echo "<li style='color:green'>✓ Supprimé : " . htmlspecialchars($file) . "</li>";
        } else {
            echo "<li style='color:red'>✗ Échec : " . htmlspecialchars($file) . "</li>";
        }
    } else {
        echo "<li style='color:gray'>— Introuvable : " . htmlspecialchars($file) . "</li>";
    }
}

// Supprimer le dossier _backup récursivement
function deleteDir($dir) {
    if (!is_dir($dir)) return false;
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = $dir . '/' . $item;
        if (is_dir($path)) {
            deleteDir($path);
        } else {
            unlink($path);
        }
    }
    return rmdir($dir);
}

if (is_dir($backupDir)) {
    if (deleteDir($backupDir)) {
        echo "<li style='color:green'>✓ Supprimé : dossier _backup/</li>";
    } else {
        echo "<li style='color:red'>✗ Échec : dossier _backup/</li>";
    }
} else {
    echo "<li style='color:gray'>— Introuvable : _backup/</li>";
}

// Supprimer le .htaccess de redirection à la racine de public_html
$htaccess = $publicHtml . '/.htaccess';
if (file_exists($htaccess)) {
    // Lire le contenu pour vérifier que c'est bien notre redirect
    $content = file_get_contents($htaccess);
    if (strpos($content, '/site/') !== false) {
        if (unlink($htaccess)) {
            echo "<li style='color:green'>✓ Supprimé : .htaccess (redirect /site/)</li>";
        } else {
            echo "<li style='color:red'>✗ Échec : .htaccess</li>";
        }
    } else {
        echo "<li style='color:orange'>⚠ .htaccess existe mais ne contient pas la redirect /site/ — non touché</li>";
    }
}

echo "</ul>";

// Lister ce qui reste dans public_html
echo "<h3>Fichiers restants dans public_html :</h3><ul>";
$remaining = scandir($publicHtml);
foreach ($remaining as $item) {
    if ($item === '.' || $item === '..') continue;
    echo "<li>" . htmlspecialchars($item) . "</li>";
}
echo "</ul>";

echo "<p><strong>Nettoyage terminé.</strong> Supprimez maintenant ce script du repo GitHub.</p>";
