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

// Gestione degli errori e messaggi
$errors = [];

// Controllo se è stato passato l'ID dell'artista
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $errors[] = "ID artista non specificato";
} else {
    $artist_id = sanitize_input($_GET['id']);

    try {
        // Carica i dati dell'artista
        $artistModel = new ArtistModel();
        $artist = $artistModel->getArtistById($artist_id);

        // Se l'artista non esiste, mostra un errore
        if (!$artist) {
            $errors[] = "Artista non trovato";
        } else {
            // Carica le canzoni dell'artista
            $interpretationModel = new InterpretationModel();
            $songModel = new SongModel();

            // Ottieni tutte le canzoni dell'artista divise per tipo di interpretazione
            $mainSongs = [];
            $featuredSongs = [];

            $allArtistSongs = $interpretationModel->getSongsByArtist($artist_id);

            foreach ($allArtistSongs as $song) {
                // Per ogni canzone, controlla il tipo di interpretazione
                $type = $interpretationModel->getInterpretationType($artist_id, $song->id);

                if ($type === 'main') {
                    $mainSongs[] = $song;
                } else if ($type === 'featured') {
                    $featuredSongs[] = $song;
                }
            }

            // Calcola l'età dell'artista
            $birthDate = new DateTime($artist->birth_date);
            $today = new DateTime();
            $age = $today->diff($birthDate)->y;

            // Formatta la data di nascita
            $birthDateFormatted = date('d/m/Y', strtotime($artist->birth_date));

            // Traduci il genere
            $genderTranslated = [
                'male' => 'Uomo',
                'female' => 'Donna',
                'other' => 'Altro'
            ][$artist->gender] ?? $artist->gender;
        }
    } catch (Exception $e) {
        $errors[] = "Errore durante il caricamento dei dati: " . $e->getMessage();
    }
}

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
    <link rel="stylesheet" href="../styles/addsong/addsong.css">
    <link rel="stylesheet" href="../styles/artist/artist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title><?= isset($artist) ? htmlspecialchars($artist->stage_name) : 'Artista' ?> | Jukebox</title>
</head>

<body>
    <div class="main">
        <section class="content_section">
            <div class="header_container">
                <div class="links_container">
                    <a href="../">Home</a>
                    <a href="../jukeboxmanager/">Jukebox Manager</a>
                </div>
                <div class="searchbar">
                    <img src="../img/magnifing_glass.svg" alt="Search Icon">
                    <input type="text" placeholder="Cerca brani o artisti" disabled>
                </div>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="error-container">
                    <ul style="padding-left: 1rem;">
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php elseif (isset($artist)): ?>
                <div class="artist-header">
                    <div class="banner-image" style="background-image: url('../database/data/profile_pictures/<?= htmlspecialchars($artist->getProfilePictureFileName()) ?>');"></div>
                    <div class="banner-overlay"></div>
                    <div class="artist-header-content">
                        <div class="artist-profile-image">
                            <img src="../database/data/profile_pictures/<?= htmlspecialchars($artist->getProfilePictureFileName()) ?>" alt="<?= htmlspecialchars($artist->stage_name) ?>">
                        </div>
                        <div class="artist-info">
                            <h1 class="artist-name"><?= htmlspecialchars($artist->stage_name) ?></h1>
                            <div class="artist-real-name"><?= htmlspecialchars($artist->name . ' ' . $artist->surname) ?></div>
                            <div class="artist-meta">
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span><?= $birthDateFormatted ?> (<?= $age ?> anni)</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-user"></i>
                                    <span><?= $genderTranslated ?></span>
                                </div>
                                <div class="profile-status <?= $artist->visible ? '' : 'not-visible' ?>">
                                    <i class="fas <?= $artist->visible ? 'fa-eye' : 'fa-eye-slash' ?>"></i>
                                    <span><?= $artist->visible ? 'Profilo pubblico' : 'Profilo privato' ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tabs-container">
                    <div class="tabs-nav">
                        <div class="tab-item active" data-tab="music">Musica</div>
                        <div class="tab-item" data-tab="bio">Biografia</div>
                    </div>

                    <div class="tab-content active" id="music-tab">
                        <!-- Sezione brani come artista principale -->
                        <div class="songs-container">
                            <h2 class="section-title">Brani come artista principale</h2>
                            <?php if (empty($mainSongs)): ?>
                                <div class="no-songs">Nessun brano trovato in cui <?= htmlspecialchars($artist->stage_name) ?> è l'artista principale.</div>
                            <?php else: ?>
                                <div class="songs-grid">
                                    <?php foreach ($mainSongs as $index => $song): ?>
                                        <?php
                                        $allArtists = $interpretationModel->getArtistsBySong($song->id);
                                        $otherArtists = array_filter($allArtists, function ($a) use ($artist) {
                                            return $a->id !== $artist->id;
                                        });
                                        $featuredArtists = array_map(function ($a) {
                                            return $a->stage_name;
                                        }, $otherArtists);
                                        $artistsText = empty($featuredArtists) ? '' : ' (feat. ' . implode(', ', $featuredArtists) . ')';
                                        ?>
                                        <div class="song-card" data-index="<?= $index ?>" onclick="location.href='../song/?id=<?= htmlspecialchars($song->id) ?>'">
                                            <div class="song-card-image">
                                                <img src="../database/data/songs_covers/<?= htmlspecialchars($song->getCoverImageFileName()) ?>" alt="<?= htmlspecialchars($song->title) ?>">
                                            </div>
                                            <div class="song-card-content">
                                                <h3 class="song-card-title"><?= htmlspecialchars($song->title) ?></h3>
                                                <div class="song-card-subtitle"><?= htmlspecialchars($artist->stage_name . $artistsText) ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Sezione brani come featuring -->
                        <div class="songs-container">
                            <h2 class="section-title">Brani in featuring</h2>
                            <?php if (empty($featuredSongs)): ?>
                                <div class="no-songs">Nessun brano trovato in cui <?= htmlspecialchars($artist->stage_name) ?> è in featuring.</div>
                            <?php else: ?>
                                <div class="songs-grid">
                                    <?php foreach ($featuredSongs as $index => $song): ?>
                                        <?php
                                        $mainArtists = array_filter(
                                            $interpretationModel->getArtistsBySong($song->id),
                                            function ($a) {
                                                global $interpretationModel, $song;
                                                return $interpretationModel->getInterpretationType($a->id, $song->id) === 'main';
                                            }
                                        );
                                        $mainArtistsNames = array_map(function ($a) {
                                            return $a->stage_name;
                                        }, $mainArtists);
                                        ?>
                                        <div class="song-card" data-index="<?= $index + count($mainSongs) ?>" onclick="location.href='../song/?id=<?= htmlspecialchars($song->id) ?>'">
                                            <div class="song-card-image">
                                                <img src="../database/data/songs_covers/<?= htmlspecialchars($song->getCoverImageFileName()) ?>" alt="<?= htmlspecialchars($song->title) ?>">
                                            </div>
                                            <div class="song-card-content">
                                                <h3 class="song-card-title"><?= htmlspecialchars($song->title) ?></h3>
                                                <div class="song-card-subtitle"><?= htmlspecialchars(implode(', ', $mainArtistsNames)) ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="tab-content" id="bio-tab">
                        <div class="artist-bio">
                            <div class="bio-content">
                                <?= nl2br(htmlspecialchars($artist->biography)) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </section>

        <section class="player_section">
            <div class="preview-panel">
                <div class="preview-title">
                    <?= isset($artist) ? htmlspecialchars($artist->stage_name) : 'Artista' ?>
                </div>
                <div class="song-preview-container">
                    <div class="song-preview-image artist-preview-image">
                        <?php if (isset($artist)): ?>
                            <img src="../database/data/profile_pictures/<?= htmlspecialchars($artist->getProfilePictureFileName()) ?>" alt="<?= htmlspecialchars($artist->stage_name) ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="song-preview-details">
                    <div class="preview-song-title"><?= isset($artist) ? htmlspecialchars($artist->stage_name) : 'Artista' ?></div>
                    <div class="preview-song-artists">
                        <?php if (isset($mainSongs) && isset($featuredSongs)): ?>
                            <?= count($mainSongs) ?> brani principali · <?= count($featuredSongs) ?> featuring
                        <?php else: ?>
                            Nessun brano disponibile
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (isset($artist)): ?>
                    <div class="preview-player">
                        <div class="preview-play-button">
                            <i class="fas fa-play"></i>
                        </div>
                        <div class="preview-time">
                            <span>0:00</span>
                            <span>0:00</span>
                        </div>
                        <div class="preview-progress">
                            <div class="preview-progress-bar"></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestione delle tab
            const tabItems = document.querySelectorAll('.tab-item');
            const tabContents = document.querySelectorAll('.tab-content');

            tabItems.forEach(item => {
                item.addEventListener('click', () => {
                    // Rimuovi la classe active da tutti gli elementi
                    tabItems.forEach(tab => tab.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));

                    // Aggiungi la classe active all'elemento corrente e al contenuto corrispondente
                    item.classList.add('active');
                    document.getElementById(`${item.dataset.tab}-tab`).classList.add('active');
                });
            });

            // Animazioni per le schede delle canzoni
            const songCards = document.querySelectorAll('.song-card');
            songCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100 + (index * 50));
            });

            // Animazione per il banner dell'artista
            const artistHeader = document.querySelector('.artist-header');
            if (artistHeader) {
                artistHeader.style.opacity = '0';
                artistHeader.style.transform = 'translateY(-20px)';

                setTimeout(() => {
                    artistHeader.style.transition = 'all 0.8s ease';
                    artistHeader.style.opacity = '1';
                    artistHeader.style.transform = 'translateY(0)';
                }, 100);
            }

            // Animazione per il contenuto delle tab
            const tabsContainer = document.querySelector('.tabs-container');
            if (tabsContainer) {
                tabsContainer.style.opacity = '0';
                tabsContainer.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    tabsContainer.style.transition = 'all 0.5s ease';
                    tabsContainer.style.opacity = '1';
                    tabsContainer.style.transform = 'translateY(0)';
                }, 400);
            }

            // Simula il funzionamento del player (solo estetico)
            const playButton = document.querySelector('.preview-play-button');
            if (playButton) {
                playButton.addEventListener('click', function() {
                    const icon = this.querySelector('i');

                    if (icon.classList.contains('fa-play')) {
                        icon.classList.remove('fa-play');
                        icon.classList.add('fa-pause');
                    } else {
                        icon.classList.remove('fa-pause');
                        icon.classList.add('fa-play');
                    }
                });
            }

            // Aggiunge la funzione getInterpretationType al modello InterpretationModel
            // Questa funzione deve essere aggiunta nel file InterpretationModel.php
            // per funzionare correttamente
        });
    </script>
</body>

</html>