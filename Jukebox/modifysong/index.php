<?php

declare(strict_types=1);

require_once __DIR__ . "/../models/ArtistModel.php";
require_once __DIR__ . "/../classes/Artist.php";
require_once __DIR__ . "/../models/SongModel.php";
require_once __DIR__ . "/../classes/Song.php";
require_once __DIR__ . "/../models/InterpretationModel.php";
require_once __DIR__ . "/../classes/Utils.php";

function sanitize_input(string $data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Gestione degli errori e messaggi
$errors = [];
$success_message = "";

// Controllo se è stato passato l'ID del brano
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $errors[] = "ID brano non specificato";
    header("Location: ../jukeboxmanager/");
    exit;
}

$song_id = sanitize_input($_GET['id']);

try {
    // Carica i dati del brano
    $songModel = new SongModel();
    $song = $songModel->getSongById($song_id);

    if (!$song) {
        $errors[] = "Brano non trovato";
        header("Location: ../jukeboxmanager/");
        exit;
    }

    // Carica gli artisti associati al brano
    $interpretationModel = new InterpretationModel();
    $artistModel = new ArtistModel();

    // Ottieni tutti gli artisti per la ricerca
    $allArtists = $artistModel->getAllArtist();

    // Ottieni gli artisti principali e featuring di questo brano
    $songArtists = $interpretationModel->getArtistsBySong($song_id);
    $mainArtists = [];
    $featuredArtists = [];

    foreach ($songArtists as $artist) {
        $type = $interpretationModel->getInterpretationType($artist->id, $song_id);
        if ($type === 'main') {
            $mainArtists[] = $artist;
        } else if ($type === 'featured') {
            $featuredArtists[] = $artist;
        }
    }
} catch (Exception $e) {
    $errors[] = "Errore durante il caricamento dei dati: " . $e->getMessage();
}

// Gestione del form di aggiornamento
if (isset($_POST['update_song'])) {
    // Validazione e sanitizzazione degli input
    $title = !empty($_POST['title']) ? sanitize_input($_POST['title']) : '';
    $release_date = !empty($_POST['release_date']) ? sanitize_input($_POST['release_date']) : '';
    $lyrics = !empty($_POST['lyrics']) ? sanitize_input($_POST['lyrics']) : '';
    $main_artists = isset($_POST['main_artists']) ? $_POST['main_artists'] : [];
    $featured_artists = isset($_POST['featured_artists']) ? $_POST['featured_artists'] : [];

    // Validazione
    if (empty($title)) {
        $errors[] = "Il titolo del brano è obbligatorio";
    }

    if (empty($release_date)) {
        $errors[] = "La data di rilascio è obbligatoria";
    }

    if (empty($main_artists)) {
        $errors[] = "È necessario selezionare almeno un artista principale";
    }

    // Se non ci sono errori, procedi con l'aggiornamento
    if (empty($errors)) {
        try {
            // Gestione upload del file audio (se caricato)
            $audio_file = $song->audio_file; // Mantieni il file esistente come default
            if (isset($_FILES['audio_file']) && $_FILES['audio_file']['error'] == 0) {
                $audio_extensions = ['mp3', 'mp4', 'flac', 'wav'];
                $audio_file_info = pathinfo($_FILES['audio_file']['name']);
                $audio_extension = strtolower($audio_file_info['extension']);

                if (in_array($audio_extension, $audio_extensions)) {
                    $audio_file = $song_id . '.' . $audio_extension;

                    FilesystemManager::deleteSongAudio($song_id); // Elimina il vecchio file audio se esiste

                    move_uploaded_file(
                        $_FILES['audio_file']['tmp_name'],
                        __DIR__ . '/../database/data/songs_audio/' . $audio_file
                    );
                } else {
                    $errors[] = "Formato file audio non supportato. Formati accettati: " . implode(', ', $audio_extensions);
                }
            }

            // Gestione upload dell'immagine di copertina (se caricata)
            $cover_image = $song->cover_image; // Mantieni l'immagine esistente come default
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
                $image_extensions = ['jpg', 'jpeg', 'png', 'webp'];
                $image_file_info = pathinfo($_FILES['cover_image']['name']);
                $image_extension = strtolower($image_file_info['extension']);

                if (in_array($image_extension, $image_extensions)) {
                    $cover_image_file = $song_id . '.' . $image_extension;

                    FilesystemManager::deleteSongCover($song_id); // Elimina la vecchia copertina se esiste

                    move_uploaded_file(
                        $_FILES['cover_image']['tmp_name'],
                        __DIR__ . '/../database/data/songs_covers/' . $cover_image_file
                    );
                } else {
                    $errors[] = "Formato immagine copertina non supportato. Formati accettati: " . implode(', ', $image_extensions);
                }
            }

            // Gestione upload dell'immagine di sfondo canvas (se caricata)
            $canvas_background = $song->canvas_background_image; // Mantieni l'immagine esistente come default
            if (isset($_FILES['canvas_background']) && $_FILES['canvas_background']['error'] == 0) {
                $canvas_extensions = ['jpg', 'jpeg', 'png', 'webp', 'mp4'];
                $canvas_file_info = pathinfo($_FILES['canvas_background']['name']);
                $canvas_extension = strtolower($canvas_file_info['extension']);

                if (in_array($canvas_extension, $canvas_extensions)) {
                    $canvas_file = $song_id . '.' . $canvas_extension;

                    FilesystemManager::deleteSongCanvas($song_id); // Elimina il vecchio canvas se esiste

                    move_uploaded_file(
                        $_FILES['canvas_background']['tmp_name'],
                        __DIR__ . '/../database/data/songs_canvas/' . $canvas_file
                    );
                } else {
                    $errors[] = "Formato immagine/video canvas non supportato. Formati accettati: " . implode(', ', $canvas_extensions);
                }
            }

            if (empty($errors)) {
                // Aggiorna le informazioni del brano nel database
                $songModel->updateSong(
                    $song_id,
                    $title,
                    (int)$song->duration,
                    $release_date,
                    $lyrics,
                    $audio_file,
                    $canvas_background
                );

                // Rimuovi tutte le associazioni artista-brano esistenti
                $interpretationModel->deleteInterpretationsBySong($song_id);

                // Aggiungi le nuove associazioni artista-brano
                foreach ($main_artists as $artist_id) {
                    $interpretationModel->associateArtistToSong($artist_id, $song_id, 'main');
                }

                foreach ($featured_artists as $artist_id) {
                    $interpretationModel->associateArtistToSong($artist_id, $song_id, 'featured');
                }

                $success_message = "Brano aggiornato con successo!";

                // Reindirizza alla pagina del brano dopo 2 secondi
                header("refresh:2;url=../song/?id=" . $song_id);

                // Ricarica i dati del brano per mostrare i cambiamenti
                $song = $songModel->getSongById($song_id);
                $songArtists = $interpretationModel->getArtistsBySong($song_id);
                $mainArtists = [];
                $featuredArtists = [];

                foreach ($songArtists as $artist) {
                    $type = $interpretationModel->getInterpretationType($artist->id, $song_id);
                    if ($type === 'main') {
                        $mainArtists[] = $artist;
                    } else if ($type === 'featured') {
                        $featuredArtists[] = $artist;
                    }
                }
            }
        } catch (Exception $e) {
            $errors[] = "Errore durante l'aggiornamento del brano: " . $e->getMessage();
        }
    }
}

// Gestione eliminazione brano
if (isset($_POST['delete_song'])) {
    try {
        // Rimuovi le associazioni artista-brano
        $interpretationModel->deleteInterpretationsBySong($song_id);

        // Elimina il brano
        $songModel->deleteSong($song_id);

        $success_message = "Brano eliminato con successo!";

        // Reindirizza alla pagina principale dopo 2 secondi
        header("refresh:2;url=../jukeboxmanager/");
        exit;
    } catch (Exception $e) {
        $errors[] = "Errore durante l'eliminazione del brano: " . $e->getMessage();
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
    <link rel="stylesheet" href="../styles/modifysong/modifysong.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Modifica Brano | Jukebox Manager</title>
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
            <?php endif; ?>

            <?php if (!empty($success_message)): ?>
                <div class="success-message">
                    <?= $success_message ?>
                </div>
            <?php endif; ?>

            <?php if (isset($song) && empty($errors)): ?>
                <div class="edit-song-container">
                    <form method="post" enctype="multipart/form-data" id="editSongForm">
                        <div class="song-header">
                            <label for="coverImageInput" class="song-cover-placeholder" id="coverImageLabel">
                                <img src="../database/data/songs_covers/<?= htmlspecialchars($song->getCoverImageFileName()) ?>" alt="<?= htmlspecialchars($song->title) ?>" id="coverPreview">
                                <div class="overlay"><i class="fas fa-camera"></i></div>
                                <input type="file" name="cover_image" id="coverImageInput" accept=".jpg,.jpeg,.png,.webp" style="display: none;">
                            </label>
                            <div class="song-header-content">
                                <h1 class="song-title">Modifica brano</h1>
                                <p class="song-description"><?= htmlspecialchars($song->title) ?></p>
                            </div>
                        </div>

                        <div class="form-section">
                            <h2>Informazioni brano</h2>
                            <div class="form-group">
                                <label for="title">Titolo brano *</label>
                                <input type="text" name="title" id="title" class="form-control" required placeholder="Inserisci il titolo del brano" value="<?= htmlspecialchars($song->title) ?>">
                            </div>

                            <div class="form-group">
                                <label for="release_date">Data di rilascio *</label>
                                <input type="date" name="release_date" id="release_date" class="form-control" required max="<?= date('Y-m-d') ?>" value="<?= htmlspecialchars($song->release_date) ?>">
                            </div>

                            <div class="form-group">
                                <label for="lyrics">Testo (opzionale)</label>
                                <textarea name="lyrics" id="lyrics" class="form-control" rows="5" placeholder="Inserisci il testo del brano"><?= htmlspecialchars($song->lyrics) ?></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label>File audio</label>
                                        <div class="file-upload" id="audioUploadContainer">
                                            <div class="upload-icon">
                                                <i class="fas fa-music"></i>
                                            </div>
                                            <div class="upload-text">
                                                <div class="upload-title">Aggiorna file audio</div>
                                                <div class="upload-description">File attuale: <?= htmlspecialchars($song->audio_file) ?></div>
                                            </div>
                                            <input type="file" name="audio_file" id="audioFileInput" accept=".mp3,.mp4,.flac,.wav">
                                        </div>
                                        <div id="selectedAudioFile" style="display: none;"></div>
                                    </div>
                                </div>

                                <div class="form-col">
                                    <div class="form-group">
                                        <label>Canvas background (opzionale)</label>
                                        <div class="file-upload" id="canvasUploadContainer">
                                            <div class="upload-icon">
                                                <i class="fas fa-image"></i>
                                            </div>
                                            <div class="upload-text">
                                                <div class="upload-title">Aggiorna immagine/video canvas</div>
                                                <div class="upload-description">
                                                    <?php if ($song->canvas_background_image): ?>
                                                        File attuale: <?= htmlspecialchars($song->canvas_background_image) ?>
                                                    <?php else: ?>
                                                        Nessun canvas presente
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <input type="file" name="canvas_background" id="canvasFileInput" accept=".jpg,.jpeg,.png,.webp,.mp4">
                                        </div>
                                        <div id="selectedCanvasFile" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="artist-selection">
                                <div class="artist-column">
                                    <h2>Artisti principali</h2>
                                    <div class="selected-artists" id="mainArtistsContainer">
                                        <!-- Gli artisti principali verranno inseriti qui tramite JavaScript -->
                                    </div>
                                    <div class="artist-search-container">
                                        <div class="artist-search-wrapper">
                                            <div class="fas fa-search search-icon"></div>
                                            <input type="text" class="artist-search" id="mainArtistSearch" placeholder="Cerca artista">
                                        </div>
                                        <div class="artist-results" id="mainArtistResults"></div>
                                    </div>
                                </div>

                                <div class="artist-column">
                                    <h2>Artisti featuring</h2>
                                    <div class="selected-artists" id="featuredArtistsContainer">
                                        <!-- Gli artisti featuring verranno inseriti qui tramite JavaScript -->
                                    </div>
                                    <div class="artist-search-container">
                                        <i class="fas fa-search search-icon"></i>
                                        <input type="text" class="artist-search" id="featuredArtistSearch" placeholder="Cerca artista">
                                        <div class="artist-results" id="featuredArtistResults"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <button type="button" id="deleteButton" class="btn btn-delete">Elimina brano</button>
                            <a href="../song/?id=<?= htmlspecialchars($song_id) ?>" class="btn btn-cancel">Annulla modifiche</a>
                            <button type="submit" name="update_song" class="btn btn-create">Salva modifiche</button>
                        </div>

                        <!-- Input nascosti per memorizzare gli artisti selezionati -->
                        <div id="mainArtistInputs"></div>
                        <div id="featuredArtistInputs"></div>
                    </form>

                    <!-- Form separato per l'eliminazione -->
                    <form method="post" id="deleteForm" style="display: none;">
                        <input type="hidden" name="delete_song" value="1">
                    </form>
                </div>
            <?php endif; ?>
        </section>

        <section class="player_section">
            <div class="preview-panel">
                <div class="preview-title">Anteprima brano</div>
                <div class="song-preview-container">
                    <div class="song-preview-image" id="previewImage">
                        <img src="../database/data/songs_covers/<?= htmlspecialchars($song->getCoverImageFileName()) ?>" alt="Anteprima copertina">
                    </div>
                </div>
                <div class="song-preview-details">
                    <div class="preview-song-title" id="previewTitle"><?= htmlspecialchars($song->title) ?></div>
                    <div class="preview-song-artists" id="previewArtists">
                        <?php
                        $mainArtistNames = array_map(function ($artist) {
                            return htmlspecialchars($artist->stage_name);
                        }, $mainArtists);

                        $featuredArtistNames = array_map(function ($artist) {
                            return htmlspecialchars($artist->stage_name);
                        }, $featuredArtists);

                        $artistsText = implode(', ', $mainArtistNames);
                        if (!empty($featuredArtistNames)) {
                            $artistsText .= ' (feat. ' . implode(', ', $featuredArtistNames) . ')';
                        }
                        echo $artistsText;
                        ?>
                    </div>
                </div>
                <div class="preview-player">
                    <div class="preview-play-button">
                        <i class="fas fa-play"></i>
                    </div>
                    <div class="preview-time">
                        <span>0:00</span>
                        <span>
                            <?php
                            $minutes = floor($song->duration / 60);
                            $seconds = $song->duration % 60;
                            echo sprintf('%d:%02d', $minutes, $seconds);
                            ?>
                        </span>
                    </div>
                    <div class="preview-progress">
                        <div class="preview-progress-bar"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementi DOM
            const coverImageInput = document.getElementById('coverImageInput');
            const coverPreview = document.getElementById('coverPreview');
            const audioFileInput = document.getElementById('audioFileInput');
            const canvasFileInput = document.getElementById('canvasFileInput');
            const selectedAudioFile = document.getElementById('selectedAudioFile');
            const selectedCanvasFile = document.getElementById('selectedCanvasFile');
            const titleInput = document.getElementById('title');
            const mainArtistSearch = document.getElementById('mainArtistSearch');
            const featuredArtistSearch = document.getElementById('featuredArtistSearch');
            const mainArtistResults = document.getElementById('mainArtistResults');
            const featuredArtistResults = document.getElementById('featuredArtistResults');
            const mainArtistsContainer = document.getElementById('mainArtistsContainer');
            const featuredArtistsContainer = document.getElementById('featuredArtistsContainer');
            const mainArtistInputs = document.getElementById('mainArtistInputs');
            const featuredArtistInputs = document.getElementById('featuredArtistInputs');
            const deleteButton = document.getElementById('deleteButton');
            const deleteForm = document.getElementById('deleteForm');

            // Preview elements
            const previewImage = document.getElementById('previewImage');
            const previewTitle = document.getElementById('previewTitle');
            const previewArtists = document.getElementById('previewArtists');

            // Dati degli artisti dal database
            const allArtists = <?= json_encode(array_map(function ($artist) {
                                    return [
                                        'id' => $artist->id,
                                        'name' => $artist->stage_name,
                                        'image' => '../database/data/profile_pictures/' . $artist->getProfilePictureFileName(),
                                    ];
                                }, $allArtists)) ?>;

            // Carica gli artisti preselezionati
            const selectedMainArtists = <?= json_encode(array_map(function ($artist) {
                                            return [
                                                'id' => $artist->id,
                                                'name' => $artist->stage_name,
                                                'image' => '../database/data/profile_pictures/' . $artist->getProfilePictureFileName(),
                                            ];
                                        }, $mainArtists)) ?>;

            const selectedFeaturedArtists = <?= json_encode(array_map(function ($artist) {
                                                return [
                                                    'id' => $artist->id,
                                                    'name' => $artist->stage_name,
                                                    'image' => '../database/data/profile_pictures/' . $artist->getProfilePictureFileName(),
                                                ];
                                            }, $featuredArtists)) ?>;

            // Inizializza gli artisti selezionati
            renderSelectedArtists(mainArtistsContainer, selectedMainArtists, 'main');
            renderSelectedArtists(featuredArtistsContainer, selectedFeaturedArtists, 'featured');
            updateHiddenInputs();

            // Gestione upload immagine di copertina
            coverImageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        coverPreview.src = e.target.result;
                        updatePreviewImage(e.target.result);
                    };

                    reader.readAsDataURL(file);
                }
            });

            // Gestione upload file audio
            audioFileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];

                    selectedAudioFile.innerHTML = `
                        <div class="selected-file">
                            <i class="fas fa-music"></i>
                            <span class="selected-file-name">${file.name}</span>
                        </div>
                    `;
                    selectedAudioFile.style.display = 'block';
                }
            });

            // Gestione upload canvas background
            canvasFileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];

                    selectedCanvasFile.innerHTML = `
                        <div class="selected-file">
                            <i class="fas fa-${file.type.startsWith('image') ? 'image' : 'video'}"></i>
                            <span class="selected-file-name">${file.name}</span>
                        </div>
                    `;
                    selectedCanvasFile.style.display = 'block';
                }
            });

            // Aggiorna l'anteprima del titolo
            titleInput.addEventListener('input', function() {
                updatePreviewTitle(this.value || '<?= addslashes(htmlspecialchars($song->title)) ?>');
            });

            // Gestione ricerca artisti principali
            mainArtistSearch.addEventListener('focus', function() {
                showArtistResults(mainArtistResults, allArtists, 'main', this.value);
            });

            mainArtistSearch.addEventListener('input', function() {
                showArtistResults(mainArtistResults, allArtists, 'main', this.value);
            });

            document.addEventListener('click', function(e) {
                if (!mainArtistSearch.contains(e.target) && !mainArtistResults.contains(e.target)) {
                    mainArtistResults.style.display = 'none';
                }
                if (!featuredArtistSearch.contains(e.target) && !featuredArtistResults.contains(e.target)) {
                    featuredArtistResults.style.display = 'none';
                }
            });

            // Gestione ricerca artisti featuring
            featuredArtistSearch.addEventListener('focus', function() {
                showArtistResults(featuredArtistResults, allArtists, 'featured', this.value);
            });

            featuredArtistSearch.addEventListener('input', function() {
                showArtistResults(featuredArtistResults, allArtists, 'featured', this.value);
            });

            // Gestione pulsante elimina
            deleteButton.addEventListener('click', function() {
                if (confirm('Sei sicuro di voler eliminare questo brano? Quest\'azione non può essere annullata.')) {
                    deleteForm.submit();
                }
            });

            // Funzioni di supporto
            function showArtistResults(container, artists, type, query) {
                container.style.display = 'block';
                container.innerHTML = '';

                const filteredArtists = filterArtists(artists, query);

                // Filtra gli artisti già selezionati in entrambe le liste (main e featured)
                const filteredAvailableArtists = filteredArtists.filter(artist => {
                    return !selectedMainArtists.some(a => a.id === artist.id) &&
                        !selectedFeaturedArtists.some(a => a.id === artist.id);
                });

                if (filteredAvailableArtists.length === 0) {
                    container.innerHTML = '<div class="artist-result-item">Nessun artista trovato</div>';
                    return;
                }

                for (let i = 0; i < Math.min(5, filteredAvailableArtists.length); i++) {
                    const artist = filteredAvailableArtists[i];

                    const artistItem = document.createElement('div');
                    artistItem.classList.add('artist-result-item');
                    artistItem.innerHTML = `
                    <div class="artist-result-image">
                        <img src="${artist.image}" alt="${artist.name}">
                    </div>
                    <div class="artist-result-info">
                        <div class="artist-result-name">${artist.name}</div>
                        <div class="artist-result-type">Artista</div>
                    </div>
                    `;

                    artistItem.addEventListener('click', function() {
                        addArtist(artist, type);
                        container.style.display = 'none';
                        if (type === 'main') {
                            mainArtistSearch.value = '';
                        } else {
                            featuredArtistSearch.value = '';
                        }
                    });

                    container.appendChild(artistItem);
                }
            }

            function filterArtists(artists, query) {
                if (!query) return artists;

                query = query.toLowerCase();
                return artists.filter(artist => artist.name.toLowerCase().includes(query));
            }

            function addArtist(artist, type) {
                if (type === 'main' && !selectedMainArtists.some(a => a.id === artist.id)) {
                    selectedMainArtists.push(artist);
                    renderSelectedArtists(mainArtistsContainer, selectedMainArtists, 'main');
                } else if (type === 'featured' && !selectedFeaturedArtists.some(a => a.id === artist.id)) {
                    selectedFeaturedArtists.push(artist);
                    renderSelectedArtists(featuredArtistsContainer, selectedFeaturedArtists, 'featured');
                }

                // Aggiorna gli input nascosti per il form
                updateHiddenInputs();

                // Aggiorna l'anteprima degli artisti
                updatePreviewArtists();
            }

            function removeArtist(artistId, type) {
                if (type === 'main') {
                    const index = selectedMainArtists.findIndex(a => a.id === artistId);
                    if (index !== -1) {
                        selectedMainArtists.splice(index, 1);
                        renderSelectedArtists(mainArtistsContainer, selectedMainArtists, 'main');
                    }
                } else if (type === 'featured') {
                    const index = selectedFeaturedArtists.findIndex(a => a.id === artistId);
                    if (index !== -1) {
                        selectedFeaturedArtists.splice(index, 1);
                        renderSelectedArtists(featuredArtistsContainer, selectedFeaturedArtists, 'featured');
                    }
                }

                // Aggiorna gli input nascosti per il form
                updateHiddenInputs();

                // Aggiorna l'anteprima degli artisti
                updatePreviewArtists();
            }

            function renderSelectedArtists(container, artists, type) {
                container.innerHTML = '';

                artists.forEach(artist => {
                    const chip = document.createElement('div');
                    chip.classList.add('selected-artist-chip');
                    chip.innerHTML = `
                        <div class="selected-artist-image">
                            <img src="${artist.image}" alt="${artist.name}">
                        </div>
                        <div class="selected-artist-name">${artist.name}</div>
                        <div class="remove-artist" data-id="${artist.id}" data-type="${type}">
                            <i class="fas fa-times"></i>
                        </div>
                    `;

                    container.appendChild(chip);
                });

                // Aggiungi event listener per i pulsanti di rimozione
                container.querySelectorAll('.remove-artist').forEach(button => {
                    button.addEventListener('click', function() {
                        const artistId = this.getAttribute('data-id');
                        const artistType = this.getAttribute('data-type');
                        removeArtist(artistId, artistType);
                    });
                });
            }

            function updateHiddenInputs() {
                // Aggiorna gli input nascosti per gli artisti principali
                mainArtistInputs.innerHTML = '';
                selectedMainArtists.forEach(artist => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'main_artists[]';
                    input.value = artist.id;
                    mainArtistInputs.appendChild(input);
                });

                // Aggiorna gli input nascosti per gli artisti featuring
                featuredArtistInputs.innerHTML = '';
                selectedFeaturedArtists.forEach(artist => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'featured_artists[]';
                    input.value = artist.id;
                    featuredArtistInputs.appendChild(input);
                });
            }

            // Funzioni per aggiornare l'anteprima
            function updatePreviewImage(imageUrl) {
                previewImage.innerHTML = `<img src="${imageUrl}" alt="Anteprima Copertina">`;
            }

            function updatePreviewTitle(title) {
                previewTitle.textContent = title;
            }

            function updatePreviewArtists() {
                const mainArtistNames = selectedMainArtists.map(a => a.name);
                const featArtistNames = selectedFeaturedArtists.map(a => a.name);

                let artistsText = mainArtistNames.join(', ');

                if (featArtistNames.length > 0) {
                    artistsText += ' (feat. ' + featArtistNames.join(', ') + ')';
                }

                previewArtists.textContent = artistsText || 'Seleziona gli artisti';
            }

            // Aggiungi animazioni all'entrata della pagina
            const formSections = document.querySelectorAll('.form-section');
            formSections.forEach((section, index) => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    section.style.transition = 'all 0.5s ease';
                    section.style.opacity = '1';
                    section.style.transform = 'translateY(0)';
                }, 100 + (index * 100));
            });

            const headerSection = document.querySelector('.song-header');
            headerSection.style.opacity = '0';
            headerSection.style.transform = 'translateY(-20px)';

            setTimeout(() => {
                headerSection.style.transition = 'all 0.5s ease';
                headerSection.style.opacity = '1';
                headerSection.style.transform = 'translateY(0)';
            }, 100);

            // Aggiungi effetti hover ai pulsanti
            const actionButtons = document.querySelectorAll('.action-buttons .btn');
            actionButtons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>

</html>