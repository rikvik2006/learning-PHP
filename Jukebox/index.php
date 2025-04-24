<?php

declare(strict_types=1);

require_once __DIR__ . "/models/ArtistModel.php";
require_once __DIR__ . "/classes/Artist.php";
require_once __DIR__ . "/models/SongModel.php";
require_once __DIR__ . "/classes/Song.php";
require_once __DIR__ . "/models/InterpretationModel.php";

function sanitize_input(string $data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Ottieni tutti i brani e gli artisti
$songModel = new SongModel();
$allSongs = $songModel->getAllSongs();

$artistModel = new ArtistModel();
$allArtists = $artistModel->getAllArtist();

$interpretationModel = new InterpretationModel();

// Gestione della sessione per tenere traccia dei brani ascoltati recentemente
session_start();

// Inizializza array di brani ascoltati recentemente se non esiste
if (!isset($_SESSION['recently_played'])) {
    $_SESSION['recently_played'] = [];
}

// Gestione del brano selezionato
$currentSong = null;

// Se è stato passato un ID brano tramite GET, aggiornalo come brano corrente
if (isset($_GET['play_song']) && !empty($_GET['play_song'])) {
    $song_id = sanitize_input($_GET['play_song']);

    // Verifica che il brano esista
    $currentSong = $songModel->getSongById($song_id);

    // Se il brano è valido, aggiornalo come brano corrente nella sessione
    if ($currentSong) {
        $_SESSION['current_song'] = $song_id;

        // Aggiungi all'inizio dell'array dei brani ascoltati recentemente
        // e rimuovi eventuali duplicati
        $_SESSION['recently_played'] = array_unique(
            array_merge([$song_id], $_SESSION['recently_played']),
            SORT_REGULAR
        );

        // Limita a 10 brani recenti
        $_SESSION['recently_played'] = array_slice($_SESSION['recently_played'], 0, 10);
    }
}
// Altrimenti, se c'è un brano corrente nella sessione, caricalo
elseif (isset($_SESSION['current_song'])) {
    $currentSong = $songModel->getSongById($_SESSION['current_song']);
}
// Altrimenti, seleziona un brano casuale
elseif (!empty($allSongs)) {
    $currentSong = $allSongs[array_rand($allSongs)];
    $_SESSION['current_song'] = $currentSong->id;
}

// Carica i dati per il player
if ($currentSong) {
    // Carica gli artisti associati al brano
    $songArtists = $interpretationModel->getArtistsBySong($currentSong->id);

    // Separa gli artisti principali e featuring
    $mainArtists = [];
    $featuredArtists = [];

    foreach ($songArtists as $artist) {
        $type = $interpretationModel->getInterpretationType($artist->id, $currentSong->id);
        if ($type === 'main') {
            $mainArtists[] = $artist;
        } else if ($type === 'featured') {
            $featuredArtists[] = $artist;
        }
    }

    // Formatta la durata del brano
    $minutes = floor($currentSong->duration / 60);
    $seconds = $currentSong->duration % 60;
    $formattedDuration = sprintf('%d:%02d', $minutes, $seconds);

    // Controllo se il brano ha un canvas background
    $hasCanvas = !empty($currentSong->canvas_background_image);
    $canvasExt = $hasCanvas ? pathinfo($currentSong->getCanvasFileName(), PATHINFO_EXTENSION) : '';
    $isCanvasVideo = $hasCanvas && in_array($canvasExt, ['mp4', 'webm']);
}

// Preparazione dei brani recenti
$recentlyPlayedSongs = [];
foreach ($_SESSION['recently_played'] as $songId) {
    $song = $songModel->getSongById($songId);
    if ($song) {
        $recentlyPlayedSongs[] = $song;
    }
}

// Se non ci sono brani recenti, usa i più recenti dal database
if (empty($recentlyPlayedSongs)) {
    $recentlyPlayedSongs = array_slice($allSongs, 0, 5);
}

// Preparazione degli artisti ascoltati di recente (basato sui brani ascoltati)
$recentArtistIds = [];
foreach ($recentlyPlayedSongs as $song) {
    $songArtists = $interpretationModel->getArtistsBySong($song->id);
    foreach ($songArtists as $artist) {
        $recentArtistIds[$artist->id] = true;
    }
}

// Ottieni gli artisti recenti
$recentlyViewedArtists = [];
foreach (array_keys($recentArtistIds) as $artistId) {
    $artist = $artistModel->getArtistById($artistId);
    if ($artist) {
        $recentlyViewedArtists[] = $artist;
    }
}

// Limita a 5 artisti
$recentlyViewedArtists = array_slice($recentlyViewedArtists, 0, 5);

// Se non ci sono artisti recenti, usa i più recenti dal database
if (empty($recentlyViewedArtists)) {
    $recentlyViewedArtists = array_slice($allArtists, 0, 5);
}

// Funzione per formattare gli artisti di un brano
function formatSongArtists($songId, $interpretationModel)
{
    $artists = $interpretationModel->getArtistsBySong($songId);

    $mainArtists = [];
    $featuredArtists = [];

    foreach ($artists as $artist) {
        $type = $interpretationModel->getInterpretationType($artist->id, $songId);
        if ($type === 'main') {
            $mainArtists[] = $artist;
        } else if ($type === 'featured') {
            $featuredArtists[] = $artist;
        }
    }

    $mainArtistLinks = array_map(function ($artist) {
        return '<a href="./artist/?id=' . htmlspecialchars($artist->id) . '" class="artist-link">' . htmlspecialchars($artist->stage_name) . '</a>';
    }, $mainArtists);

    $html = implode(', ', $mainArtistLinks);

    if (!empty($featuredArtists)) {
        $featuredArtistLinks = array_map(function ($artist) {
            return '<a href="./artist/?id=' . htmlspecialchars($artist->id) . '" class="artist-link">' . htmlspecialchars($artist->stage_name) . '</a>';
        }, $featuredArtists);

        $html .= '<span class="featured-artists"> (feat. ' . implode(', ', $featuredArtistLinks) . ')</span>';
    }

    return $html;
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/main.css">
    <link rel="stylesheet" href="./styles/home/home.css">
    <link rel="stylesheet" href="./styles/global/navbar.css">
    <link rel="stylesheet" href="./styles/global/song.css">
    <link rel="stylesheet" href="./styles/global/artist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Home Jukebox</title>
</head>

<body>
    <div class="main">
        <section class="content_section">
            <div class="header_container">
                <div class="links_container">
                    <a href="./">Home</a>
                    <a href="./jukeboxmanager/">Jukebox Manager</a>
                </div>
                <form class="searchbar" action="" method="get">
                    <img src="./img/magnifing_glass.svg" alt="Search Icon">
                    <input type="text" name="search" placeholder="Cerca brani o artisti">
                </form>
            </div>

            <?php if (!empty($recentlyPlayedSongs)): ?>
                <section class="page-section">
                    <h2 class="section-title">Ascoltati di recente</h2>
                    <div class="horizontal-scroll">
                        <?php foreach ($recentlyPlayedSongs as $song): ?>
                            <div class="song-card">
                                <a href="./song/?id=<?= htmlspecialchars($song->id) ?>" class="song-link">
                                    <div class="song-card-image">
                                        <img src="./database/data/songs_covers/<?= htmlspecialchars($song->getCoverImageFileName()) ?>" alt="Cover di <?= htmlspecialchars($song->title) ?>">
                                    </div>
                                </a>
                                <div class="song-card-content">
                                    <h3 class="song-card-title">
                                        <a href="./song/?id=<?= htmlspecialchars($song->id) ?>" class="song-link">
                                            <?= htmlspecialchars($song->title) ?>
                                        </a>
                                    </h3>
                                    <div class="song-card-subtitle">
                                        <?= formatSongArtists($song->id, $interpretationModel) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <section class="page-section">
                <h2 class="section-title">Tutti i brani</h2>
                <div class="content-grid">
                    <?php foreach ($allSongs as $song): ?>
                        <div class="song-card">
                            <a href="./song/?id=<?= htmlspecialchars($song->id) ?>" class="song-link">
                                <div class="song-card-image">
                                    <img src="./database/data/songs_covers/<?= htmlspecialchars($song->getCoverImageFileName()) ?>" alt="Cover di <?= htmlspecialchars($song->title) ?>">
                                </div>
                            </a>
                            <div class="song-card-content">
                                <h3 class="song-card-title">
                                    <a href="./song/?id=<?= htmlspecialchars($song->id) ?>" class="song-link">
                                        <?= htmlspecialchars($song->title) ?>
                                    </a>
                                </h3>
                                <div class="song-card-subtitle">
                                    <?= formatSongArtists($song->id, $interpretationModel) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <?php if (!empty($recentlyViewedArtists)): ?>
                <section class="page-section">
                    <h2 class="section-title">Artisti ascoltati di recente</h2>
                    <div class="horizontal-scroll">
                        <?php foreach ($recentlyViewedArtists as $artist): ?>
                            <a href="./artist/?id=<?= htmlspecialchars($artist->id) ?>" class="artist-card">
                                <div class="artist-card-image">
                                    <img src="./database/data/profile_pictures/<?= htmlspecialchars($artist->getProfilePictureFileName()) ?>" alt="Foto di <?= htmlspecialchars($artist->stage_name) ?>">
                                </div>
                                <h3 class="artist-card-name"><?= htmlspecialchars($artist->stage_name) ?></h3>
                                <div class="artist-card-type">Artista</div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <section class="page-section">
                <h2 class="section-title">Tutti gli artisti</h2>
                <div class="content-grid">
                    <?php foreach ($allArtists as $artist): ?>
                        <a href="./artist/?id=<?= htmlspecialchars($artist->id) ?>" class="artist-card">
                            <div class="artist-card-image">
                                <img src="./database/data/profile_pictures/<?= htmlspecialchars($artist->getProfilePictureFileName()) ?>" alt="Foto di <?= htmlspecialchars($artist->stage_name) ?>">
                            </div>
                            <h3 class="artist-card-name"><?= htmlspecialchars($artist->stage_name) ?></h3>
                            <div class="artist-card-type">Artista</div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        </section>

        <section class="player_section">
            <?php if (isset($currentSong)): ?>
                <div class="player-section">
                    <div class="canvas-container">
                        <?php if ($hasCanvas): ?>
                            <?php if ($isCanvasVideo): ?>
                                <video class="canvas-media canvas-video" autoplay muted loop>
                                    <source src="./database/data/songs_canvas/<?= htmlspecialchars($currentSong->getCanvasFileName()) ?>" type="video/<?= $canvasExt ?>">
                                </video>
                            <?php else: ?>
                                <img class="canvas-media canvas-image" src="./database/data/songs_canvas/<?= htmlspecialchars($currentSong->getCanvasFileName()) ?>" alt="Canvas Background">
                            <?php endif; ?>
                        <?php else: ?>
                            <img class="canvas-media canvas-image" src="./database/data/songs_covers/<?= htmlspecialchars($currentSong->getCoverImageFileName()) ?>" alt="Cover Image">
                        <?php endif; ?>

                        <div class="canvas-overlay">
                            <div class="player-controls">
                                <div class="player-info">
                                    <div class="player-title"><?= htmlspecialchars($currentSong->title) ?></div>
                                    <div class="player-artists">
                                        <?php
                                        $allArtistNames = array_map(function ($artist) {
                                            return htmlspecialchars($artist->stage_name);
                                        }, array_merge($mainArtists ?? [], $featuredArtists ?? []));
                                        echo implode(', ', $allArtistNames);
                                        ?>
                                    </div>
                                </div>

                                <div class="control-buttons">
                                    <div class="secondary-button" id="prevButton">
                                        <i class="fas fa-backward"></i>
                                    </div>
                                    <div class="play-button" id="playButton">
                                        <i class="fas fa-play" id="playIcon"></i>
                                    </div>
                                    <div class="secondary-button" id="nextButton">
                                        <i class="fas fa-forward"></i>
                                    </div>
                                </div>

                                <div class="progress-container">
                                    <div class="progress-bar" id="progressBar">
                                        <div class="progress-fill" id="progressFill">
                                            <div class="progress-handle"></div>
                                        </div>
                                    </div>
                                    <div class="progress-time">
                                        <span id="currentTime">0:00</span>
                                        <span id="totalTime"><?= $formattedDuration ?? '0:00' ?></span>
                                    </div>
                                </div>

                                <div class="volume-container">
                                    <div class="volume-icon" id="volumeIcon">
                                        <i class="fas fa-volume-up"></i>
                                    </div>
                                    <div class="volume-bar" id="volumeBar">
                                        <div class="volume-fill" id="volumeFill"></div>
                                    </div>
                                </div>

                                <div class="player-actions">
                                    <div class="action-button" title="Mi piace">
                                        <i class="far fa-heart"></i>
                                    </div>
                                    <div class="action-button" title="Aggiungi alla playlist">
                                        <i class="fas fa-plus"></i>
                                    </div>
                                    <div class="action-button" title="Ripeti">
                                        <i class="fas fa-redo-alt"></i>
                                    </div>
                                    <div class="action-button" title="Shuffle">
                                        <i class="fas fa-random"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <audio id="audioPlayer" src="./database/data/songs_audio/<?= htmlspecialchars($currentSong->audio_file) ?>"></audio>
                </div>
            <?php else: ?>
                <div class="player-section">
                    <div style="padding: 2rem; text-align: center;">
                        <p>Nessun brano disponibile</p>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animazioni per le card
            const songCards = document.querySelectorAll('.song-card');
            songCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 50 + (index % 10) * 50); // Anima in gruppi di 10 per non rallentare con molti elementi
            });

            const artistCards = document.querySelectorAll('.artist-card');
            artistCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 50 + (index % 10) * 50);
            });

            // Gestione del player audio
            const audioPlayer = document.getElementById('audioPlayer');
            if (!audioPlayer) return;

            const playButton = document.getElementById('playButton');
            const playIcon = document.getElementById('playIcon');
            const prevButton = document.getElementById('prevButton');
            const nextButton = document.getElementById('nextButton');
            const progressBar = document.getElementById('progressBar');
            const progressFill = document.getElementById('progressFill');
            const currentTime = document.getElementById('currentTime');
            const totalTime = document.getElementById('totalTime');
            const volumeBar = document.getElementById('volumeBar');
            const volumeFill = document.getElementById('volumeFill');
            const volumeIcon = document.getElementById('volumeIcon');

            // Funzione per formattare il tempo in minuti:secondi
            function formatTime(seconds) {
                const minutes = Math.floor(seconds / 60);
                const secs = Math.floor(seconds % 60);
                return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
            }

            // Gestione play/pause
            playButton.addEventListener('click', function() {
                if (audioPlayer.paused) {
                    audioPlayer.play();
                    playIcon.classList.replace('fa-play', 'fa-pause');
                } else {
                    audioPlayer.pause();
                    playIcon.classList.replace('fa-pause', 'fa-play');
                }
            });

            // Aggiornamento della progress bar durante la riproduzione
            audioPlayer.addEventListener('timeupdate', function() {
                const progress = (audioPlayer.currentTime / audioPlayer.duration) * 100;
                progressFill.style.width = progress + '%';
                currentTime.textContent = formatTime(audioPlayer.currentTime);
            });

            // Gestione click sulla progress bar
            progressBar.addEventListener('click', function(e) {
                const width = this.clientWidth;
                const clickX = e.offsetX;
                const duration = audioPlayer.duration;
                audioPlayer.currentTime = (clickX / width) * duration;
            });

            // Gestione volume
            volumeBar.addEventListener('click', function(e) {
                const width = this.clientWidth;
                const clickX = e.offsetX;
                const newVolume = clickX / width;
                audioPlayer.volume = newVolume;
                volumeFill.style.width = (newVolume * 100) + '%';

                // Aggiorna l'icona del volume
                updateVolumeIcon(newVolume);
            });

            // Toggle mute
            volumeIcon.addEventListener('click', function() {
                if (audioPlayer.muted) {
                    audioPlayer.muted = false;
                    volumeFill.style.width = (audioPlayer.volume * 100) + '%';
                    updateVolumeIcon(audioPlayer.volume);
                } else {
                    audioPlayer.muted = true;
                    volumeFill.style.width = '0%';
                    volumeIcon.innerHTML = '<i class="fas fa-volume-mute"></i>';
                }
            });

            // Funzione per aggiornare l'icona del volume
            function updateVolumeIcon(volume) {
                volumeIcon.innerHTML = volume === 0 ? '<i class="fas fa-volume-mute"></i>' :
                    volume < 0.5 ? '<i class="fas fa-volume-down"></i>' :
                    '<i class="fas fa-volume-up"></i>';
            }

            // Gestione fine brano
            audioPlayer.addEventListener('ended', function() {
                playIcon.classList.replace('fa-pause', 'fa-play');
                progressFill.style.width = '0%';
                currentTime.textContent = '0:00';
            });

            // Pulsanti precedente/successivo
            prevButton.addEventListener('click', function() {
                this.style.transform = 'scale(1.1)';
                setTimeout(() => this.style.transform = 'scale(1)', 200);
                // In una app reale qui cambierei il brano
                window.location.href = './?play_song=<?= isset($allSongs) && count($allSongs) > 0 ? htmlspecialchars($allSongs[array_rand($allSongs)]->id) : '' ?>';
            });

            nextButton.addEventListener('click', function() {
                this.style.transform = 'scale(1.1)';
                setTimeout(() => this.style.transform = 'scale(1)', 200);
                // In una app reale qui cambierei il brano
                window.location.href = './?play_song=<?= isset($allSongs) && count($allSongs) > 0 ? htmlspecialchars($allSongs[array_rand($allSongs)]->id) : '' ?>';
            });

            // Pulsanti azione (like, playlist, repeat, shuffle)
            document.querySelectorAll('.player-actions .action-button').forEach(button => {
                button.addEventListener('click', function() {
                    this.classList.toggle('active');
                });
            });

            // Impostazione volume iniziale
            audioPlayer.volume = 0.7;
            volumeFill.style.width = '70%';

            // Animazione player
            const playerSection = document.querySelector('.player-section');
            if (playerSection) {
                playerSection.style.opacity = '0';

                setTimeout(() => {
                    playerSection.style.transition = 'all 0.8s ease';
                    playerSection.style.opacity = '1';
                }, 300);
            }

            // Animazione sezioni
            const pageSections = document.querySelectorAll('.page-section');
            pageSections.forEach((section, index) => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    section.style.transition = 'all 0.6s ease';
                    section.style.opacity = '1';
                    section.style.transform = 'translateY(0)';
                }, 200 + (index * 100));
            });
        });
    </script>
</body>

</html>