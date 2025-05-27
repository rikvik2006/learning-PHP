<?php

declare(strict_types=1);

require_once __DIR__ . "/../models/SongModel.php";
require_once __DIR__ . "/../classes/Song.php";
require_once __DIR__ . "/../models/ArtistModel.php";
require_once __DIR__ . "/../classes/Artist.php";
require_once __DIR__ . "/../models/InterpretationModel.php";

// Funzione per formattare la durata del brano
function formatDuration(string $duration): string
{
    $seconds = intval($duration);
    $minutes = floor($seconds / 60);
    $remainingSeconds = $seconds % 60;
    return sprintf("%d:%02d", $minutes, $remainingSeconds);
}

// Funzione per evidenziare la query di ricerca nel testo
function highlightQuery(string $text, string $query): string
{
    if (empty($query)) return $text;

    // Proteggi i caratteri speciali delle espressioni regolari
    $query = preg_quote($query, '/');

    // Sostituisci con la versione evidenziata (case insensitive)
    return preg_replace('/(' . $query . ')/i', '<span class="highlight">$1</span>', $text);
}

// Verifica se la richiesta contiene la query di ricerca
if (!isset($_GET['q']) || empty(trim($_GET['q']))) {
    header("Location: ../");
    exit;
}

$query = trim($_GET['q']);
$encodedQuery = htmlspecialchars($query);

try {
    // Istanza dei modelli necessari
    $songModel = new SongModel();
    $artistModel = new ArtistModel();
    $interpretationModel = new InterpretationModel();

    // Recupero di tutti i brani e artisti
    $allSongs = $songModel->getAllSongs();
    $allArtists = $artistModel->getAllArtist();

    // Array per i risultati filtrati
    $matchingSongs = [];
    $matchingArtists = [];

    // Filtraggio brani in base alla query
    foreach ($allSongs as $song) {
        // Calcola un punteggio di rilevanza
        $score = 0;

        // Corrispondenza esatta nel titolo (massima priorità)
        if (mb_strtolower($song->title) === mb_strtolower($query)) {
            $score += 100;
        }
        // Titolo contiene la query
        elseif (mb_stripos($song->title, $query) !== false) {
            $score += 50;
        }

        // Corrispondenza nelle lyrics
        if (mb_stripos($song->lyrics, $query) !== false) {
            $score += 30;

            // Bonus per occorrenze multiple
            $occurrences = mb_substr_count(mb_strtolower($song->lyrics), mb_strtolower($query));
            if ($occurrences > 1) {
                $score += min($occurrences * 2, 20); // Max 20 punti bonus
            }
        }

        // Se c'è corrispondenza, aggiungi ai risultati
        if ($score > 0) {
            // Recupera gli artisti per questo brano
            $songArtists = $interpretationModel->getArtistsBySong($song->id);

            // Aggiunge un punteggio se l'artista corrisponde alla query
            foreach ($songArtists as $artist) {
                if (mb_stripos($artist->stage_name, $query) !== false) {
                    $score += 40;
                    break; // Solo una volta
                }
            }

            // Aggiungi alla lista con il punteggio
            $matchingSongs[] = [
                'song' => $song,
                'score' => $score,
                'artists' => $songArtists
            ];
        }
    }

    // Filtraggio artisti in base alla query
    foreach ($allArtists as $artist) {
        $score = 0;

        // Corrispondenza esatta nel nome d'arte (massima priorità)
        if (mb_strtolower($artist->stage_name) === mb_strtolower($query)) {
            $score += 100;
        }
        // Nome d'arte contiene la query
        elseif (mb_stripos($artist->stage_name, $query) !== false) {
            $score += 60;
        }

        // Nome o cognome corrispondono alla query
        if (mb_stripos($artist->name, $query) !== false || mb_stripos($artist->surname, $query) !== false) {
            $score += 30;
        }

        // Se c'è corrispondenza, aggiungi ai risultati
        if ($score > 0) {
            $matchingArtists[] = [
                'artist' => $artist,
                'score' => $score
            ];
        }
    }

    // Ordinamento dei risultati per punteggio (decrescente)
    usort($matchingSongs, function ($a, $b) {
        return $b['score'] - $a['score'];
    });

    usort($matchingArtists, function ($a, $b) {
        return $b['score'] - $a['score'];
    });

    // Risultati più rilevanti
    $topResult = null;
    $topArtist = null;

    // Determina il risultato più rilevante tra brani e artisti
    if (!empty($matchingSongs) && !empty($matchingArtists)) {
        if ($matchingSongs[0]['score'] >= $matchingArtists[0]['score']) {
            $topResult = $matchingSongs[0];
            $topSongs = array_slice($matchingSongs, 1, 6); // Escludi il primo che è già il top result
        } else {
            $topArtist = $matchingArtists[0];
            $topSongs = array_slice($matchingSongs, 0, 6);
        }
    } elseif (!empty($matchingSongs)) {
        $topResult = $matchingSongs[0];
        $topSongs = array_slice($matchingSongs, 1, 6);
    } elseif (!empty($matchingArtists)) {
        $topArtist = $matchingArtists[0];
        $topSongs = [];
    } else {
        $topSongs = [];
    }

    // Lista di artisti (escluso il top artist)
    $displayArtists = [];
    if (!empty($matchingArtists)) {
        $startIndex = ($topArtist !== null) ? 1 : 0;
        $displayArtists = array_slice($matchingArtists, $startIndex, 6);
    }

    // Altri brani rimanenti (esclusi quelli già mostrati)
    $moreSongs = [];
    $topSongIds = array_map(function ($item) {
        return $item['song']->id;
    }, $topSongs);

    $topSongIds[] = $topResult ? $topResult['song']->id : '';

    foreach ($matchingSongs as $songData) {
        if (!in_array($songData['song']->id, $topSongIds)) {
            $moreSongs[] = $songData;
        }
    }
} catch (Exception $e) {
    $error = "Si è verificato un errore: " . $e->getMessage();
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
    <link rel="stylesheet" href="../styles/search/search.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Ricerca: <?= $encodedQuery ?> | Jukebox</title>
</head>

<body>
    <div class="main">
        <section class="content_section">
            <div class="header_container">
                <div class="links_container">
                    <a href="../">Home</a>
                    <a href="../jukeboxmanager/">Jukebox Manager</a>
                </div>
                <form class="searchbar" action="../search/" method="get">
                    <img src="../img/magnifing_glass.svg" alt="Search Icon">
                    <input type="text" name="q" placeholder="Cerca brani o artisti" value="<?= $encodedQuery ?>">
                </form>
            </div>

            <?php if (isset($error)): ?>
                <div class="error-container">
                    <p><?= $error ?></p>
                </div>
            <?php elseif (empty($matchingSongs) && empty($matchingArtists)): ?>
                <div class="no-results">
                    <i class="fas fa-search"></i>
                    <h2>Nessun risultato trovato per "<?= $encodedQuery ?>"</h2>
                    <p>Prova a cercare con termini diversi o controlla l'ortografia.</p>
                </div>
            <?php else: ?>
                <div class="search-results">
                    <!-- Sezione risultato principale e brani correlati -->
                    <div class="top-results-container">
                        <?php if ($topResult): ?>
                            <!-- Risultato più rilevante (brano) -->
                            <div class="top-result">
                                <h2 class="section-title">Risultato più rilevante</h2>
                                <a href="../song/?id=<?= htmlspecialchars($topResult['song']->id) ?>" class="text-decoration-none">
                                    <div class="top-song-card">
                                        <div class="top-song-image">
                                            <img src="../database/data/songs_covers/<?= htmlspecialchars($topResult['song']->getCoverImageFileName()) ?>" alt="<?= htmlspecialchars($topResult['song']->title) ?>">
                                        </div>
                                        <div class="top-song-info">
                                            <h3 class="song-title"><?= highlightQuery(htmlspecialchars($topResult['song']->title), $query) ?></h3>
                                            <div class="song-subtitle">
                                                <?php
                                                $artistNames = array_map(function ($artist) use ($query) {
                                                    return '<a href="../artist/?id=' . htmlspecialchars($artist->id) . '" class="artist-link">' . highlightQuery(htmlspecialchars($artist->stage_name), $query) . '</a>';
                                                }, $topResult['artists']);
                                                echo "Brano • " . implode(', ', $artistNames);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php elseif ($topArtist): ?>
                            <!-- Risultato più rilevante (artista) -->
                            <div class="top-result">
                                <h2 class="section-title">Risultato più rilevante</h2>
                                <a href="../artist/?id=<?= htmlspecialchars($topArtist['artist']->id) ?>" class="top-artist-card">
                                    <div class="top-artist-image">
                                        <img src="../database/data/profile_pictures/<?= htmlspecialchars($topArtist['artist']->getProfilePictureFileName()) ?>" alt="<?= htmlspecialchars($topArtist['artist']->stage_name) ?>">
                                    </div>
                                    <div class="top-artist-info">
                                        <h3 class="artist-name"><?= highlightQuery(htmlspecialchars($topArtist['artist']->stage_name), $query) ?></h3>
                                        <div class="artist-type">Artista</div>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- Brani correlati -->
                        <?php if (!empty($topSongs)): ?>
                            <div class="top-songs">
                                <h2 class="section-title">Brani</h2>
                                <div class="songs-list">
                                    <?php foreach ($topSongs as $songData): ?>
                                        <div class="song-item">
                                            <a href="../song/?id=<?= htmlspecialchars($songData['song']->id) ?>" class="song-link">
                                                <div class="song-image">
                                                    <img src="../database/data/songs_covers/<?= htmlspecialchars($songData['song']->getCoverImageFileName()) ?>" alt="<?= htmlspecialchars($songData['song']->title) ?>">
                                                    <div class="play-overlay"><i class="fas fa-play"></i></div>
                                                </div>
                                                <div class="song-details">
                                                    <div class="song-title"><?= highlightQuery(htmlspecialchars($songData['song']->title), $query) ?></div>
                                                    <div class="song-artist">
                                                        <?php
                                                        $artistNames = array_map(function ($artist) use ($query) {
                                                            return highlightQuery(htmlspecialchars($artist->stage_name), $query);
                                                        }, $songData['artists']);
                                                        echo implode(', ', $artistNames);
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="song-duration">
                                                    <?= formatDuration($songData['song']->duration) ?>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Sezione artisti -->
                    <?php if (!empty($displayArtists)): ?>
                        <div class="artists-section">
                            <h2 class="section-title">Artisti</h2>
                            <div class="artists-grid">
                                <?php foreach ($displayArtists as $artistData): ?>
                                    <a href="../artist/?id=<?= htmlspecialchars($artistData['artist']->id) ?>" class="artist-card">
                                        <div class="artist-image">
                                            <img src="../database/data/profile_pictures/<?= htmlspecialchars($artistData['artist']->getProfilePictureFileName()) ?>" alt="<?= htmlspecialchars($artistData['artist']->stage_name) ?>">
                                        </div>
                                        <div class="artist-name"><?= highlightQuery(htmlspecialchars($artistData['artist']->stage_name), $query) ?></div>
                                        <div class="artist-type">Artista</div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Sezione altri brani -->
                    <?php if (!empty($moreSongs)): ?>
                        <div class="more-songs-section">
                            <h2 class="section-title">Altri brani</h2>
                            <div class="more-songs-list">
                                <?php foreach ($moreSongs as $songData): ?>
                                    <div class="song-row">
                                        <a href="../song/?id=<?= htmlspecialchars($songData['song']->id) ?>" class="song-link">
                                            <div class="song-row-image">
                                                <img src="../database/data/songs_covers/<?= htmlspecialchars($songData['song']->getCoverImageFileName()) ?>" alt="<?= htmlspecialchars($songData['song']->title) ?>">
                                                <div class="play-overlay"><i class="fas fa-play"></i></div>
                                            </div>
                                            <div class="song-row-details">
                                                <div class="song-row-title"><?= highlightQuery(htmlspecialchars($songData['song']->title), $query) ?></div>
                                                <div class="song-row-artist">
                                                    <?php
                                                    $artistNames = array_map(function ($artist) use ($query) {
                                                        return highlightQuery(htmlspecialchars($artist->stage_name), $query);
                                                    }, $songData['artists']);
                                                    echo implode(', ', $artistNames);
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="song-row-meta">
                                                <div class="song-release-date">
                                                    <?= date('d/m/Y', strtotime($songData['song']->release_date)) ?>
                                                </div>
                                                <div class="song-row-duration">
                                                    <?= formatDuration($songData['song']->duration) ?>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>

        <section class="player_section">
            <!-- Player codice qui -->
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animazione di entrata per i risultati
            const sections = document.querySelectorAll('.top-result, .top-songs, .artists-section, .more-songs-section');

            sections.forEach((section, index) => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    section.style.transition = 'all 0.5s ease';
                    section.style.opacity = '1';
                    section.style.transform = 'translateY(0)';
                }, 100 + (index * 150));
            });

            // Animazioni hover per gli elementi
            const songItems = document.querySelectorAll('.song-item, .song-row, .top-song-card, .top-artist-card, .artist-card');

            songItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    const playOverlay = this.querySelector('.play-overlay, .play-icon');
                    if (playOverlay) {
                        playOverlay.style.opacity = '1';
                        playOverlay.style.transform = 'scale(1)';
                    }
                });

                item.addEventListener('mouseleave', function() {
                    const playOverlay = this.querySelector('.play-overlay, .play-icon');
                    if (playOverlay) {
                        playOverlay.style.opacity = '0';
                        playOverlay.style.transform = 'scale(0.8)';
                    }
                });
            });

            // Focus sulla barra di ricerca all'ingresso della pagina
            const searchInput = document.querySelector('.searchbar input');
            setTimeout(() => {
                searchInput.focus();
            }, 300);
        });
    </script>
</body>

</html>