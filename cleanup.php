<?php
/**
 * Script de nettoyage temporaire v2 — supprime les anciens fichiers par scan
 * À SUPPRIMER après utilisation
 */

if (!isset($_GET['token']) || $_GET['token'] !== 'carinci2026cleanup') {
    die('Accès refusé');
}

$publicHtml = dirname(__DIR__);

echo "<h2>Nettoyage de public_html (v2)</h2>";
echo "<p>Dossier cible : " . htmlspecialchars($publicHtml) . "</p>";
echo "<ul>";

$deleted = 0;
$kept = 0;

// Scanner tous les fichiers/dossiers dans public_html
$items = scandir($publicHtml);
foreach ($items as $item) {
    if ($item === '.' || $item === '..') continue;

    $path = $publicHtml . '/' . $item;

    // Garder : .private (système Hostinger) et site/ (notre déploiement)
    if ($item === '.private' || $item === 'site') {
        echo "<li style='color:blue'>⏭ Conservé : " . htmlspecialchars($item) . "</li>";
        $kept++;
        continue;
    }

    // Supprimer tout le reste
    if (is_dir($path)) {
        // Suppression récursive des dossiers
        $it = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        if (rmdir($path)) {
            echo "<li style='color:green'>✓ Supprimé (dossier) : " . htmlspecialchars($item) . "</li>";
            $deleted++;
        } else {
            echo "<li style='color:red'>✗ Échec (dossier) : " . htmlspecialchars($item) . "</li>";
        }
    } else {
        if (unlink($path)) {
            echo "<li style='color:green'>✓ Supprimé : " . htmlspecialchars($item) . "</li>";
            $deleted++;
        } else {
            echo "<li style='color:red'>✗ Échec : " . htmlspecialchars($item) . "</li>";
        }
    }
}

echo "</ul>";
echo "<p><strong>$deleted supprimé(s), $kept conservé(s).</strong></p>";

// Vérification finale
echo "<h3>Contenu final de public_html :</h3><ul>";
$remaining = scandir($publicHtml);
foreach ($remaining as $r) {
    if ($r === '.' || $r === '..') continue;
    echo "<li>" . htmlspecialchars($r) . "</li>";
}
echo "</ul>";

echo "<p><strong>Nettoyage terminé.</strong> Prochaine étape : supprimer le dépôt GIT sur Hostinger, le recréer sans sous-dossier.</p>";
