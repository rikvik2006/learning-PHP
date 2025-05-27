<?php

declare(strict_types=1);

require_once __DIR__ . "/../models/ArtistModel.php";
require_once __DIR__ . "/../classes/Artist.php";
require_once __DIR__ . "/../classes/Utils.php";
require_once __DIR__ . "/../classes/FilesystemManager.php";

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

// Controllo se è stato passato l'ID dell'artista
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $errors[] = "ID artista non specificato";
    header("Location: ../jukeboxmanager/");
    exit;
}

$artist_id = sanitize_input($_GET['id']);

try {
    // Carica i dati dell'artista
    $artistModel = new ArtistModel();
    $artist = $artistModel->getArtistById($artist_id);

    if (!$artist) {
        $errors[] = "Artista non trovato";
        header("Location: ../jukeboxmanager/");
        exit;
    }
} catch (Exception $e) {
    $errors[] = "Errore durante il caricamento dei dati: " . $e->getMessage();
}

// Gestione del form di aggiornamento
if (isset($_POST['update_artist'])) {
    // Validazione e sanitizzazione degli input
    $stage_name = !empty($_POST['stage_name']) ? sanitize_input($_POST['stage_name']) : '';
    $name = !empty($_POST['name']) ? sanitize_input($_POST['name']) : '';
    $surname = !empty($_POST['surname']) ? sanitize_input($_POST['surname']) : '';
    $birth_date = !empty($_POST['birth_date']) ? sanitize_input($_POST['birth_date']) : '';
    $gender = !empty($_POST['gender']) ? sanitize_input($_POST['gender']) : '';
    $biography = !empty($_POST['biography']) ? sanitize_input($_POST['biography']) : '';
    $visible = isset($_POST['visible']) ? true : false;

    // Validazione
    if (empty($stage_name)) {
        $errors[] = "Il nome d'arte è obbligatorio";
    }

    if (empty($name)) {
        $errors[] = "Il nome è obbligatorio";
    }

    if (empty($surname)) {
        $errors[] = "Il cognome è obbligatorio";
    }

    if (empty($birth_date)) {
        $errors[] = "La data di nascita è obbligatoria";
    }

    if (empty($gender)) {
        $errors[] = "Il genere è obbligatorio";
    } else if (!in_array($gender, ['male', 'female', 'other'])) {
        $errors[] = "Il genere selezionato non è valido";
    }

    if (empty($biography)) {
        $errors[] = "La biografia è obbligatoria";
    }

    // Se non ci sono errori, procedi con l'aggiornamento
    if (empty($errors)) {
        try {
            // Gestione upload dell'immagine del profilo (se caricata)
            $profile_picture = $artist->profile_picture; // Mantieni l'immagine esistente come default
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
                $image_extensions = ['jpg', 'jpeg', 'png', 'webp'];
                $image_file_info = pathinfo($_FILES['profile_picture']['name']);
                $image_extension = strtolower($image_file_info['extension']);

                if (in_array($image_extension, $image_extensions)) {
                    $profile_picture_file = $artist_id . '.' . $image_extension;
                    $target_dir = __DIR__ . '/../database/data/profile_pictures/';

                    FilesystemManager::deleteArtistFiles($artist_id); // Elimina l'immagine esistente

                    // Carica il nuovo file
                    if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_dir . $profile_picture_file)) {
                        $errors[] = "Errore durante il caricamento dell'immagine. Verifica i permessi della directory.";
                    }
                } else {
                    $errors[] = "Formato immagine profilo non supportato. Formati accettati: " . implode(', ', $image_extensions);
                }
            }

            if (empty($errors)) {
                // Aggiorna l'artista nel database
                $updatedArtist = Artist::builder()
                    ->setId($artist_id)
                    ->setStageName($stage_name)
                    ->setName($name)
                    ->setSurname($surname)
                    ->setBirthDate($birth_date)
                    ->setBiography($biography)
                    ->setGender($gender)
                    ->setProfilePicture($profile_picture)
                    ->setVisible($visible)
                    ->build();

                $artistModel->updateArtist($updatedArtist);

                $success_message = "Artista aggiornato con successo!";

                // Ricarica i dati dell'artista per mostrare i cambiamenti
                $artist = $artistModel->getArtistById($artist_id);

                // Reindirizza alla pagina dell'artista dopo 2 secondi
                header("refresh:2;url=../artist/?id=" . $artist_id);
            }
        } catch (Exception $e) {
            $errors[] = "Errore durante l'aggiornamento dell'artista: " . $e->getMessage();
        }
    }
}

// Gestione eliminazione artista
if (isset($_POST['delete_artist'])) {
    try {
        // Elimina l'artista dal database
        $artistModel->deleteArtist($artist_id);

        $success_message = "Artista eliminato con successo!";

        // Reindirizza alla pagina principale dopo 2 secondi
        header("refresh:2;url=../jukeboxmanager/");
        exit;
    } catch (Exception $e) {
        $errors[] = "Errore durante l'eliminazione dell'artista: " . $e->getMessage();
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
    <link rel="stylesheet" href="../styles/addArtist/addArtist.css">
    <link rel="stylesheet" href="../styles/modifyartist/modifyartist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Modifica Artista | Jukebox Manager</title>
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

            <?php if (isset($artist) && empty($errors)): ?>
                <div class="edit-artist-container">
                    <form method="post" enctype="multipart/form-data" id="editArtistForm">
                        <div class="artist-header">
                            <label for="profilePictureInput" class="artist-profile-upload" id="profilePictureLabel">
                                <img src="../database/data/profile_pictures/<?= htmlspecialchars($artist->getProfilePictureFileName()) ?>" alt="<?= htmlspecialchars($artist->stage_name) ?>" id="profilePreview">
                                <div class="overlay"><i class="fas fa-camera"></i></div>
                                <input type="file" name="profile_picture" id="profilePictureInput" accept=".jpg,.jpeg,.png,.webp" style="display: none;">
                            </label>
                            <div class="artist-header-content">
                                <h1 class="artist-title">Modifica artista</h1>
                                <p class="artist-description"><?= htmlspecialchars($artist->stage_name) ?></p>
                            </div>
                        </div>

                        <div class="form-section">
                            <h2>Informazioni artista</h2>
                            <div class="form-group">
                                <label for="stage_name">Nome d'arte *</label>
                                <input type="text" name="stage_name" id="stage_name" class="form-control" required placeholder="Inserisci il nome d'arte" value="<?= htmlspecialchars($artist->stage_name) ?>">
                            </div>

                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="name">Nome *</label>
                                        <input type="text" name="name" id="name" class="form-control" required placeholder="Inserisci il nome" value="<?= htmlspecialchars($artist->name) ?>">
                                    </div>
                                </div>
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="surname">Cognome *</label>
                                        <input type="text" name="surname" id="surname" class="form-control" required placeholder="Inserisci il cognome" value="<?= htmlspecialchars($artist->surname) ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="birth_date">Data di nascita *</label>
                                        <input type="date" name="birth_date" id="birth_date" class="form-control" required max="<?= date('Y-m-d') ?>" value="<?= htmlspecialchars($artist->birth_date) ?>">
                                    </div>
                                </div>
                                <div class="form-col">
                                    <div class="form-group">
                                        <label>Genere *</label>
                                        <div class="gender-selector">
                                            <div class="gender-option <?= $artist->gender === 'male' ? 'selected' : '' ?>">
                                                <input type="radio" id="male" name="gender" value="male" required <?= $artist->gender === 'male' ? 'checked' : '' ?>>
                                                <label for="male">Uomo</label>
                                            </div>
                                            <div class="gender-option <?= $artist->gender === 'female' ? 'selected' : '' ?>">
                                                <input type="radio" id="female" name="gender" value="female" <?= $artist->gender === 'female' ? 'checked' : '' ?>>
                                                <label for="female">Donna</label>
                                            </div>
                                            <div class="gender-option <?= $artist->gender === 'other' ? 'selected' : '' ?>">
                                                <input type="radio" id="other" name="gender" value="other" <?= $artist->gender === 'other' ? 'checked' : '' ?>>
                                                <label for="other">Altro</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="biography">Biografia *</label>
                                <textarea name="biography" id="biography" class="form-control" rows="5" required placeholder="Inserisci la biografia dell'artista"><?= htmlspecialchars($artist->biography) ?></textarea>
                            </div>

                            <div class="form-group">
                                <div class="visible-checkbox">
                                    <input type="checkbox" name="visible" id="visible" <?= $artist->visible ? 'checked' : '' ?>>
                                    <label for="visible">Artista visibile nella piattaforma</label>
                                </div>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <button type="button" id="deleteButton" class="btn btn-delete">Elimina artista</button>
                            <a href="../artist/?id=<?= htmlspecialchars($artist_id) ?>" class="btn btn-cancel">Annulla modifiche</a>
                            <button type="submit" name="update_artist" class="btn btn-create">Salva modifiche</button>
                        </div>
                    </form>

                    <!-- Form separato per l'eliminazione -->
                    <form method="post" id="deleteForm" style="display: none;">
                        <input type="hidden" name="delete_artist" value="1">
                    </form>
                </div>
            <?php endif; ?>
        </section>

        <section class="player_section">
            <div class="preview-panel">
                <div class="preview-title">Anteprima artista</div>
                <div class="song-preview-container">
                    <div class="song-preview-image artist-preview-image" id="previewImage">
                        <img src="../database/data/profile_pictures/<?= htmlspecialchars($artist->getProfilePictureFileName()) ?>" alt="<?= htmlspecialchars($artist->stage_name) ?>">
                    </div>
                </div>
                <div class="song-preview-details">
                    <div class="preview-song-title" id="previewName"><?= htmlspecialchars($artist->stage_name) ?></div>
                    <div class="preview-song-artists" id="previewDetails">
                        <?php
                        $details = [];
                        if (!empty($artist->name) && !empty($artist->surname)) {
                            $details[] = htmlspecialchars($artist->name . ' ' . $artist->surname);
                        }

                        $birthDate = new DateTime($artist->birth_date);
                        $today = new DateTime();
                        $age = $today->diff($birthDate)->y;
                        $details[] = $age . ' anni';

                        $genderTranslated = [
                            'male' => 'Uomo',
                            'female' => 'Donna',
                            'other' => 'Altro'
                        ][$artist->gender] ?? $artist->gender;
                        $details[] = $genderTranslated;

                        $visibilityStatus = $artist->visible ? 'Pubblico' : 'Non pubblico';
                        $details[] = $visibilityStatus;

                        echo implode(' • ', $details);
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementi DOM
            const profilePictureInput = document.getElementById('profilePictureInput');
            const profilePreview = document.getElementById('profilePreview');
            const stageNameInput = document.getElementById('stage_name');
            const nameInput = document.getElementById('name');
            const surnameInput = document.getElementById('surname');
            const birthDateInput = document.getElementById('birth_date');
            const genderInputs = document.querySelectorAll('input[name="gender"]');
            const visibleCheckbox = document.getElementById('visible');
            const biographyInput = document.getElementById('biography');
            const deleteButton = document.getElementById('deleteButton');
            const deleteForm = document.getElementById('deleteForm');

            // Preview elements
            const previewImage = document.getElementById('previewImage').querySelector('img');
            const previewName = document.getElementById('previewName');
            const previewDetails = document.getElementById('previewDetails');

            // Monitoraggio dei cambiamenti per evidenziare campi modificati
            const originalValues = {
                stage_name: stageNameInput.value,
                name: nameInput.value,
                surname: surnameInput.value,
                birth_date: birthDateInput.value,
                gender: Array.from(genderInputs).find(input => input.checked)?.value || '',
                biography: biographyInput.value,
                visible: visibleCheckbox.checked
            };

            function checkFieldModified(field, value) {
                const isModified = originalValues[field] != value;
                const element = document.getElementById(field);
                if (element) {
                    if (isModified) {
                        element.classList.add('field-modified');
                    } else {
                        element.classList.remove('field-modified');
                    }
                }
                return isModified;
            }

            // Gestione upload immagine di profilo
            profilePictureInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        profilePreview.src = e.target.result;
                        updatePreviewImage(e.target.result);

                        // Aggiungi classe per mostrare la modifica
                        profilePreview.classList.add('image-modified');
                    };

                    reader.readAsDataURL(file);
                }
            });

            // Aggiorna l'anteprima del nome d'arte
            stageNameInput.addEventListener('input', function() {
                updatePreviewName(this.value || 'Artista');
                checkFieldModified('stage_name', this.value);
            });

            // Monitoraggio dei cambiamenti nei campi dati
            nameInput.addEventListener('input', function() {
                checkFieldModified('name', this.value);
                updatePreviewDetails();
            });

            surnameInput.addEventListener('input', function() {
                checkFieldModified('surname', this.value);
                updatePreviewDetails();
            });

            birthDateInput.addEventListener('input', function() {
                checkFieldModified('birth_date', this.value);
                updatePreviewDetails();
            });

            biographyInput.addEventListener('input', function() {
                checkFieldModified('biography', this.value);
            });

            genderInputs.forEach(input => {
                input.addEventListener('change', function() {
                    checkFieldModified('gender', this.value);
                    updatePreviewDetails();

                    // Aggiunge la classe selected all'opzione selezionata
                    document.querySelectorAll('.gender-option').forEach(opt => {
                        opt.classList.remove('selected');
                    });
                    this.closest('.gender-option').classList.add('selected');
                });
            });

            visibleCheckbox.addEventListener('change', function() {
                checkFieldModified('visible', this.checked);
                updatePreviewDetails();
            });

            // Gestione eliminazione artista
            deleteButton.addEventListener('click', function() {
                if (confirm('Sei sicuro di voler eliminare questo artista? Questa azione non può essere annullata.')) {
                    deleteForm.submit();
                }
            });

            // Funzioni per aggiornare l'anteprima
            function updatePreviewImage(imageUrl) {
                previewImage.src = imageUrl;

                // Aggiungi un'animazione semplice
                previewImage.style.opacity = '0';
                previewImage.style.transform = 'scale(0.95)';

                setTimeout(() => {
                    previewImage.style.transition = 'all 0.3s ease';
                    previewImage.style.opacity = '1';
                    previewImage.style.transform = 'scale(1)';
                }, 50);
            }

            function updatePreviewName(name) {
                // Animazione per il cambio di testo
                previewName.style.opacity = '0';
                previewName.style.transform = 'translateY(5px)';

                setTimeout(() => {
                    previewName.textContent = name;
                    previewName.style.transition = 'all 0.3s ease';
                    previewName.style.opacity = '1';
                    previewName.style.transform = 'translateY(0)';
                }, 150);
            }

            function updatePreviewDetails() {
                // Costruisci i dettagli dell'artista
                let details = [];

                // Nome e cognome
                const fullName = nameInput.value && surnameInput.value ?
                    `${nameInput.value} ${surnameInput.value}` : '';
                if (fullName) details.push(fullName);

                // Età
                let age = '';
                if (birthDateInput.value) {
                    const birthDate = new Date(birthDateInput.value);
                    const today = new Date();
                    age = today.getFullYear() - birthDate.getFullYear();
                    const monthDiff = today.getMonth() - birthDate.getMonth();
                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                    details.push(age + ' anni');
                }

                // Genere
                const selectedGender = Array.from(genderInputs).find(input => input.checked)?.value;
                if (selectedGender) {
                    const genderText = {
                        'male': 'Uomo',
                        'female': 'Donna',
                        'other': 'Altro'
                    } [selectedGender];
                    details.push(genderText);
                }

                // Visibilità
                const visibilityStatus = visibleCheckbox.checked ? 'Pubblico' : 'Non pubblico';
                details.push(visibilityStatus);

                // Animazione per aggiornare l'anteprima dei dettagli
                previewDetails.style.opacity = '0';

                setTimeout(() => {
                    previewDetails.textContent = details.join(' • ');
                    previewDetails.style.transition = 'all 0.3s ease';
                    previewDetails.style.opacity = '1';
                }, 150);
            }

            // Animazioni all'entrata della pagina
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

            const headerSection = document.querySelector('.artist-header');
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

            // Controllo cambiamenti prima di uscire
            let formChanged = false;

            document.getElementById('editArtistForm').addEventListener('change', function() {
                formChanged = true;
            });

            window.addEventListener('beforeunload', function(e) {
                if (formChanged && !e.target.activeElement.classList.contains('btn')) {
                    e.preventDefault();
                    e.returnValue = 'Hai modifiche non salvate. Sei sicuro di voler uscire?';
                }
            });

            // Disabilita l'avviso sui pulsanti di azione
            document.querySelectorAll('.btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    formChanged = false;
                });
            });
        });
    </script>
</body>

</html>