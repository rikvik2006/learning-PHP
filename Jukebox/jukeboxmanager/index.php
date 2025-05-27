<?php

declare(strict_types=1);

require_once __DIR__ . "/../models/ArtistModel.php";
require_once __DIR__ . "/../classes/Artist.php";
require_once __DIR__ . "/../models/SongModel.php";
require_once __DIR__ . "/../classes/Song.php";
require_once __DIR__ . "/../models/InterpretationModel.php";

function sanitize_input(string $data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Gestione della cancellazione di brani/artisti
if (isset($_POST['delete_song']) && !empty($_POST['song_id'])) {
    $song_id = sanitize_input($_POST['song_id']);
    $songModel = new SongModel();
    try {
        $songModel->deleteSong($song_id);
        $success_message = "Brano eliminato con successo";
    } catch (Exception $e) {
        $error_message = "Errore nell'eliminazione del brano: " . $e->getMessage();
    }
}

if (isset($_POST['delete_artist']) && !empty($_POST['artist_id'])) {
    $artist_id = sanitize_input($_POST['artist_id']);
    $artistModel = new ArtistModel();
    try {
        $artistModel->deleteArtist($artist_id);
        $success_message = "Artista eliminato con successo";
    } catch (Exception $e) {
        $error_message = "Errore nell'eliminazione dell'artista: " . $e->getMessage();
    }
}

// Caricamento dei dati
$songModel = new SongModel();
$songs = $songModel->getAllSongs();

$artistModel = new ArtistModel();
$artists = $artistModel->getAllArtist();

$interpretationModel = new InterpretationModel();

// Parametro di ricerca
$search_term = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/main.css">
    <link rel="stylesheet" href="../styles/home/home.css">
    <link rel="stylesheet" href="../styles/global/navbar.css">
    <link rel="stylesheet" href="../styles/global/song.css">
    <link rel="stylesheet" href="../styles/global/artist.css">
    <link rel="stylesheet" href="../styles/manager/manager.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Jukebox Manager</title>
</head>

<body>
    <div class="main">
        <section class="content_section">
            <div class="header_container">
                <div class="links_container">
                    <a href="../">Home</a>
                    <a href="./">Jukebox Manager</a>
                </div>
                <form class="searchbar" action="" method="get">
                    <img src="../img/magnifing_glass.svg" alt="Search Icon">
                    <input type="text" name="search" id="searchInput" placeholder="Cerca brani o artisti" value="<?= $search_term ?>">
                </form>
            </div>

            <?php if (isset($success_message)): ?>
                <div class="notification success">
                    <?= $success_message ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="notification error">
                    <?= $error_message ?>
                </div>
            <?php endif; ?>

            <section class="manager-section" id="songs-section">
                <h1 class="section-title">Tutti i brani</h1>
                <a href="../addsong/" class="add-button">
                    <i class="fas fa-plus"></i> Aggiungi nuovo brano
                </a>

                <div class="item-list" id="songs-list">
                    <?php
                    $song_found = false;
                    foreach ($songs as $song):
                        // Ottieni tutti gli artisti associati al brano
                        $song_artists = $interpretationModel->getArtistsBySong($song->id);

                        // Separa gli artisti principali e featuring
                        $main_artists = [];
                        $featuring_artists = [];

                        foreach ($song_artists as $artist) {
                            $type = $interpretationModel->getInterpretationType($artist->id, $song->id);
                            if ($type === 'main') {
                                $main_artists[] = $artist;
                            } else if ($type === 'featured') {
                                $featuring_artists[] = $artist;
                            }
                        }

                        // Converti gli array in stringhe per la ricerca
                        $main_artists_names = array_map(fn($artist) => $artist->stage_name, $main_artists);
                        $featuring_artists_names = array_map(fn($artist) => $artist->stage_name, $featuring_artists);
                        $all_artists_string = implode(", ", array_merge($main_artists_names, $featuring_artists_names));

                        // Filtraggio in base alla ricerca
                        if (
                            !empty($search_term) &&
                            stripos($song->title, $search_term) === false &&
                            stripos($all_artists_string, $search_term) === false
                        ) {
                            continue;
                        }

                        $song_found = true;
                    ?>
                        <div class="item-row">
                            <div class="item-image">
                                <a href="../song/?id=<?= htmlspecialchars($song->id) ?>">
                                    <img src="../database/data/songs_covers/<?= htmlspecialchars($song->getCoverImageFileName()) ?>" alt="Cover di <?= htmlspecialchars($song->title) ?>">
                                </a>
                            </div>
                            <div class="item-info">
                                <div class="item-title">
                                    <a href="../song/?id=<?= htmlspecialchars($song->id) ?>" class="song-link"><?= htmlspecialchars($song->title) ?></a>
                                </div>
                                <div class="item-subtitle artists-container">
                                    <?php if (!empty($main_artists)): ?>
                                        <div class="artist-group">
                                            <span class="artist-type-label main-label">Artisti</span>
                                            <div class="artists-list">
                                                <?php
                                                $artist_links = array_map(function ($artist) {
                                                    return '<a href="../artist/?id=' . htmlspecialchars($artist->id) . '" class="artist-link">' . htmlspecialchars($artist->stage_name) . '</a>';
                                                }, $main_artists);
                                                echo implode(', ', $artist_links);
                                                ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($featuring_artists)): ?>
                                        <div class="artist-group">
                                            <span class="artist-type-label featuring-label">Feat.</span>
                                            <div class="artists-list">
                                                <?php
                                                $feat_links = array_map(function ($artist) {
                                                    return '<a href="../artist/?id=' . htmlspecialchars($artist->id) . '" class="artist-link">' . htmlspecialchars($artist->stage_name) . '</a>';
                                                }, $featuring_artists);
                                                echo implode(', ', $feat_links);
                                                ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="item-actions">
                                <a href="../song/?id=<?= htmlspecialchars($song->id) ?>" class="action-button" title="Visualizza brano">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form method="post" onsubmit="return confirm('Sei sicuro di voler eliminare questo brano?');">
                                    <input type="hidden" name="song_id" value="<?= htmlspecialchars($song->id) ?>">
                                    <button type="submit" name="delete_song" class="action-button delete" title="Elimina brano">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                <a href="../modifysong/?id=<?= htmlspecialchars($song->id) ?>" class="action-button edit" title="Modifica brano">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if (!$song_found): ?>
                        <div class="no-results">Nessun brano trovato con "<?= htmlspecialchars($search_term) ?>"</div>
                    <?php endif; ?>
                </div>
            </section>

            <section class="manager-section" id="artists-section">
                <h1 class="section-title">Tutti gli artisti</h1>
                <a href="../addartist/" class="add-button">
                    <i class="fas fa-plus"></i> Aggiungi nuovo artista
                </a>

                <div class="item-list" id="artists-list">
                    <?php
                    $artist_found = false;
                    foreach ($artists as $artist):
                        // Filtraggio in base alla ricerca
                        if (!empty($search_term) && stripos($artist->stage_name, $search_term) === false) {
                            continue;
                        }

                        $artist_found = true;
                    ?>
                        <div class="item-row">
                            <div class="item-image artist-image">
                                <a href="../artist/?id=<?= htmlspecialchars($artist->id) ?>">
                                    <img src="../database/data/profile_pictures/<?= htmlspecialchars($artist->getProfilePictureFileName()) ?>" alt="Foto di <?= htmlspecialchars($artist->stage_name) ?>">
                                </a>
                            </div>
                            <div class="item-info">
                                <div class="item-title">
                                    <a href="../artist/?id=<?= htmlspecialchars($artist->id) ?>" class="song-link"><?= htmlspecialchars($artist->stage_name) ?></a>
                                </div>
                                <div class="item-subtitle">Artista</div>
                            </div>
                            <div class="item-actions">
                                <a href="../artist/?id=<?= htmlspecialchars($artist->id) ?>" class="action-button" title="Visualizza artista">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form method="post" onsubmit="return confirm('Sei sicuro di voler eliminare questo artista?');">
                                    <input type="hidden" name="artist_id" value="<?= htmlspecialchars($artist->id) ?>">
                                    <button type="submit" name="delete_artist" class="action-button delete" title="Elimina artista">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                <a href="edit_artist.php?id=<?= htmlspecialchars($artist->id) ?>" class="action-button edit" title="Modifica artista">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if (!$artist_found): ?>
                        <div class="no-results">Nessun artista trovato con "<?= htmlspecialchars($search_term) ?>"</div>
                    <?php endif; ?>
                </div>
            </section>
        </section>
    </div>

    <script>
        // Script per le animazioni e interazioni
        document.addEventListener('DOMContentLoaded', function() {
            // Nascondere le notifiche dopo 5 secondi
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notification => {
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 5000);
            });

            // Animazione per gli elementi al caricamento
            const items = document.querySelectorAll('.item-row');
            items.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-20px)';
                setTimeout(() => {
                    item.style.transition = 'all 0.3s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, 100 + (index * 50));
            });
        });
    </script>
</body>

</html>