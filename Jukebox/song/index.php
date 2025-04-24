<?php

declare(strict_types=1);

require_once __DIR__ . "/../models/SongModel.php";
require_once __DIR__ . "/../classes/Song.php";
require_once __DIR__ . "/../models/InterpretationModel.php";
require_once __DIR__ . "/../models/ArtistModel.php";
require_once __DIR__ . "/../classes/Artist.php";

function sanitize_input(string $data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Gestione degli errori e messaggi
$errors = [];

// Controllo se è stato passato l'ID del brano
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $errors[] = "ID brano non specificato";
} else {
    $song_id = sanitize_input($_GET['id']);

    try {
        // Carica i dati del brano
        $songModel = new SongModel();
        $song = $songModel->getSongById($song_id);

        // Se il brano non esiste, mostra un errore
        if (!$song) {
            $errors[] = "Brano non trovato";
        } else {
            // Carica gli artisti associati al brano
            $interpretationModel = new InterpretationModel();
            $artists = $interpretationModel->getArtistsBySong($song_id);

            // Separa gli artisti principali e featuring
            $mainArtists = [];
            $featuredArtists = [];

            foreach ($artists as $artist) {
                $type = $interpretationModel->getInterpretationType($artist->id, $song_id);
                if ($type === 'main') {
                    $mainArtists[] = $artist;
                } else if ($type === 'featured') {
                    $featuredArtists[] = $artist;
                }
            }

            // Formatta la durata del brano (da secondi a minuti:secondi)
            $minutes = floor($song->duration / 60);
            $seconds = $song->duration % 60;
            $formattedDuration = sprintf('%d:%02d', $minutes, $seconds);

            // Formatta la data di rilascio
            $releaseDate = date('d/m/Y', strtotime($song->release_date));

            // Controllo se il brano ha un canvas background
            $hasCanvas = !empty($song->canvas_background_image);
            $canvasExt = $hasCanvas ? pathinfo($song->getCanvasFileName(), PATHINFO_EXTENSION) : '';
            $isCanvasVideo = $hasCanvas && in_array($canvasExt, ['mp4', 'webm']);
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
    <link rel="stylesheet" href="../styles/song/song.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title><?= isset($song) ? htmlspecialchars($song->title) : 'Brano' ?> | Jukebox</title>
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
            <?php elseif (isset($song)): ?>
                <div class="song-header">
                    <div class="banner-media">
                        <?php if ($hasCanvas): ?>
                            <?php if ($isCanvasVideo): ?>
                                <video class="banner-video" autoplay muted loop>
                                    <source src="../database/data/songs_canvas/<?= htmlspecialchars($song->getCanvasFileName()) ?>" type="video/<?= $canvasExt ?>">
                                </video>
                            <?php else: ?>
                                <img class="banner-image" src="../database/data/songs_canvas/<?= htmlspecialchars($song->getCanvasFileName()) ?>" alt="Canvas Background">
                            <?php endif; ?>
                        <?php else: ?>
                            <img class="banner-image" src="../database/data/songs_covers/<?= htmlspecialchars($song->getCoverImageFileName()) ?>" alt="Cover Image">
                        <?php endif; ?>
                    </div>
                    <div class="banner-overlay"></div>
                    <div class="song-header-content">
                        <div class="song-cover-image">
                            <img src="../database/data/songs_covers/<?= htmlspecialchars($song->getCoverImageFileName()) ?>" alt="<?= htmlspecialchars($song->title) ?>">
                        </div>
                        <div class="song-info">
                            <h1 class="song-title"><?= htmlspecialchars($song->title) ?></h1>
                            <div class="song-artists">
                                <?php
                                $mainArtistNames = array_map(function ($artist) {
                                    return '<a href="../artist/?id=' . htmlspecialchars($artist->id) . '" class="artist-link">' . htmlspecialchars($artist->stage_name) . '</a>';
                                }, $mainArtists);
                                echo implode(', ', $mainArtistNames);

                                if (!empty($featuredArtists)) {
                                    $featuredArtistNames = array_map(function ($artist) {
                                        return '<a href="../artist/?id=' . htmlspecialchars($artist->id) . '" class="artist-link">' . htmlspecialchars($artist->stage_name) . '</a>';
                                    }, $featuredArtists);
                                    echo '<span class="featured-artists"> (feat. ' . implode(', ', $featuredArtistNames) . ')</span>';
                                }
                                ?>
                            </div>
                            <div class="song-meta">
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span><?= $releaseDate ?></span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-clock"></i>
                                    <span><?= $formattedDuration ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($song->lyrics)): ?>
                    <div class="lyrics-container">
                        <h2 class="lyrics-title">Testo</h2>
                        <div class="lyrics-content">
                            <?= nl2br(htmlspecialchars($song->lyrics)) ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="lyrics-container">
                        <h2 class="lyrics-title">Testo</h2>
                        <div class="no-lyrics">Nessun testo disponibile per questo brano.</div>
                    </div>
                <?php endif; ?>

            <?php endif; ?>
        </section>

        <section class="player_section">
            <?php if (isset($song)): ?>
                <div class="player-section">
                    <div class="canvas-container">
                        <?php if ($hasCanvas): ?>
                            <?php if ($isCanvasVideo): ?>
                                <video class="canvas-media canvas-video" autoplay muted loop>
                                    <source src="../database/data/songs_canvas/<?= htmlspecialchars($song->getCanvasFileName()) ?>" type="video/<?= $canvasExt ?>">
                                </video>
                            <?php else: ?>
                                <img class="canvas-media canvas-image" src="../database/data/songs_canvas/<?= htmlspecialchars($song->getCanvasFileName()) ?>" alt="Canvas Background">
                            <?php endif; ?>
                        <?php else: ?>
                            <img class="canvas-media canvas-image" src="../database/data/songs_covers/<?= htmlspecialchars($song->getCoverImageFileName()) ?>" alt="Cover Image">
                        <?php endif; ?>

                        <div class="canvas-overlay">
                            <div class="player-controls">
                                <div class="player-info">
                                    <div class="player-title"><?= htmlspecialchars($song->title) ?></div>
                                    <div class="player-artists">
                                        <?php
                                        $allArtistNames = array_map(function ($artist) {
                                            return htmlspecialchars($artist->stage_name);
                                        }, array_merge($mainArtists, $featuredArtists));
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
                                        <span id="totalTime"><?= $formattedDuration ?></span>
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

                    <audio id="audioPlayer" src="../database/data/songs_audio/<?= htmlspecialchars($song->audio_file) ?>"></audio>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestione del player audio
            const audioPlayer = document.getElementById('audioPlayer');
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

            if (audioPlayer) {
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

                // Interazioni estetiche per i pulsanti precedente/successivo
                prevButton.addEventListener('click', function() {
                    this.style.transform = 'scale(1.1)';
                    setTimeout(() => this.style.transform = 'scale(1)', 200);
                    // In un'app reale, qui ci sarebbe la logica per passare al brano precedente
                });

                nextButton.addEventListener('click', function() {
                    this.style.transform = 'scale(1.1)';
                    setTimeout(() => this.style.transform = 'scale(1)', 200);
                    // In un'app reale, qui ci sarebbe la logica per passare al brano successivo
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
            }

            // Animazioni di entrata
            const songHeader = document.querySelector('.song-header');
            if (songHeader) {
                songHeader.style.opacity = '0';
                songHeader.style.transform = 'translateY(-20px)';

                setTimeout(() => {
                    songHeader.style.transition = 'all 0.8s ease';
                    songHeader.style.opacity = '1';
                    songHeader.style.transform = 'translateY(0)';
                }, 100);
            }

            const lyricsContainer = document.querySelector('.lyrics-container');
            if (lyricsContainer) {
                lyricsContainer.style.opacity = '0';
                lyricsContainer.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    lyricsContainer.style.transition = 'all 0.5s ease';
                    lyricsContainer.style.opacity = '1';
                    lyricsContainer.style.transform = 'translateY(0)';
                }, 400);
            }

            const playerSection = document.querySelector('.player-section');
            if (playerSection) {
                playerSection.style.opacity = '0';

                setTimeout(() => {
                    playerSection.style.transition = 'all 0.8s ease';
                    playerSection.style.opacity = '1';
                }, 300);
            }
        });
    </script>
</body>

</html>