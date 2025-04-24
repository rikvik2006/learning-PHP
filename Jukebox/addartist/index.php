<?php

declare(strict_types=1);

require_once __DIR__ . "/../models/ArtistModel.php";
require_once __DIR__ . "/../classes/Artist.php";
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

// Gestione del form di creazione artista
if (isset($_POST['create_artist'])) {
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

    // Se non ci sono errori, procedi con la creazione dell'artista
    if (empty($errors)) {
        try {
            // Genera un ID univoco per l'artista
            $artist_id = UUID::v4();

            // Gestione upload dell'immagine del profilo
            $profile_picture = $artist_id; // Default
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
                $image_extensions = ['jpg', 'jpeg', 'png', 'webp'];
                $image_file_info = pathinfo($_FILES['profile_picture']['name']);
                $image_extension = strtolower($image_file_info['extension']);

                if (in_array($image_extension, $image_extensions)) {
                    // Verifica/crea la directory
                    $target_dir = __DIR__ . '/../database/data/profile_pictures/';
                    if (!is_dir($target_dir)) {
                        mkdir($target_dir, 0777, true);
                    }

                    $profile_picture_file = $artist_id . '.' . $image_extension;
                    $target_path = $target_dir . $profile_picture_file;

                    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_path)) {
                        // File caricato con successo
                    } else {
                        $errors[] = "Errore durante il caricamento dell'immagine. Verifica i permessi della directory.";
                    }
                } else {
                    $errors[] = "Formato immagine profilo non supportato. Formati accettati: " . implode(', ', $image_extensions);
                }
            } else {
                $errors[] = "È necessario caricare un'immagine del profilo";
            }

            if (empty($errors)) {
                // Crea l'oggetto artista
                $artist = Artist::builder()
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

                // Salva l'artista nel database
                $artistModel = new ArtistModel();
                $artistModel->createArtist($artist);

                $success_message = "Artista creato con successo!";

                // Reindirizza alla pagina principale dopo 2 secondi
                header("refresh:2;url=../jukeboxmanager/");
            }
        } catch (Exception $e) {
            $errors[] = "Errore durante la creazione dell'artista: " . $e->getMessage();
        }
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
    <link rel="stylesheet" href="../styles/global/artist.css">
    <link rel="stylesheet" href="../styles/manager/manager.css">
    <link rel="stylesheet" href="../styles/addsong/addsong.css">
    <link rel="stylesheet" href="../styles/addArtist/addArtist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Aggiungi Nuovo Artista | Jukebox Manager</title>
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
                            <script>
                                console.error("<?= $error ?>")
                            </script>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($success_message)): ?>
                <div class="success-message">
                    <?= $success_message ?>
                </div>
            <?php endif; ?>

            <div class="add-song-container">
                <form method="post" enctype="multipart/form-data" id="addArtistForm">
                    <div class="song-header">
                        <label for="profilePictureInput" class="song-cover-placeholder artist-cover-placeholder" id="profilePictureLabel">
                            <img src="" alt="" id="profilePreview" style="display: none;">
                            <div class="icon" id="profileIcon"><i class="fas fa-user-plus"></i></div>
                            <div class="overlay"><i class="fas fa-camera"></i></div>
                            <input type="file" name="profile_picture" id="profilePictureInput" accept=".jpg,.jpeg,.png,.webp" style="display: none;">
                        </label>
                        <div class="song-header-content">
                            <h1 class="song-title">Nuovo artista</h1>
                            <p class="song-description">Aggiungi un nuovo artista al catalogo</p>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2>Informazioni artista</h2>
                        <div class="form-group">
                            <label for="stage_name">Nome d'arte *</label>
                            <input type="text" name="stage_name" id="stage_name" class="form-control" required placeholder="Inserisci il nome d'arte">
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="name">Nome *</label>
                                    <input type="text" name="name" id="name" class="form-control" required placeholder="Inserisci il nome">
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="surname">Cognome *</label>
                                    <input type="text" name="surname" id="surname" class="form-control" required placeholder="Inserisci il cognome">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="birth_date">Data di nascita *</label>
                                    <input type="date" name="birth_date" id="birth_date" class="form-control" required max="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label>Genere *</label>
                                    <div class="gender-selector">
                                        <div class="gender-option">
                                            <input type="radio" id="male" name="gender" value="male" required>
                                            <label for="male">Uomo</label>
                                        </div>
                                        <div class="gender-option">
                                            <input type="radio" id="female" name="gender" value="female">
                                            <label for="female">Donna</label>
                                        </div>
                                        <div class="gender-option">
                                            <input type="radio" id="other" name="gender" value="other">
                                            <label for="other">Altro</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="biography">Biografia *</label>
                            <textarea name="biography" id="biography" class="form-control" rows="5" required placeholder="Inserisci la biografia dell'artista"></textarea>
                        </div>

                        <div class="form-group">
                            <div class="visible-checkbox">
                                <input type="checkbox" name="visible" id="visible" checked>
                                <label for="visible">Artista visibile nella piattaforma</label>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <a href="../jukeboxmanager/" class="btn btn-cancel">Annulla</a>
                        <button type="submit" name="create_artist" class="btn btn-create">Crea nuovo artista</button>
                    </div>
                </form>
            </div>
        </section>

        <section class="player_section">
            <div class="preview-panel">
                <div class="preview-title">Il tuo nuovo artista</div>
                <div class="song-preview-container">
                    <div class="song-preview-image artist-preview-image" id="previewImage">
                        <!-- L'immagine viene inserita dinamicamente -->
                    </div>
                </div>
                <div class="song-preview-details">
                    <div class="preview-song-title" id="previewName">Artista</div>
                    <div class="preview-song-artists" id="previewDetails">Dettagli artista</div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementi DOM
            const profilePictureInput = document.getElementById('profilePictureInput');
            const profilePreview = document.getElementById('profilePreview');
            const profileIcon = document.getElementById('profileIcon');
            const stageNameInput = document.getElementById('stage_name');
            const nameInput = document.getElementById('name');
            const surnameInput = document.getElementById('surname');
            const birthDateInput = document.getElementById('birth_date');
            const genderInputs = document.querySelectorAll('input[name="gender"]');
            const visibleCheckbox = document.getElementById('visible');

            // Preview elements
            const previewImage = document.getElementById('previewImage');
            const previewName = document.getElementById('previewName');
            const previewDetails = document.getElementById('previewDetails');

            // Gestione upload immagine di profilo
            profilePictureInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        profilePreview.src = e.target.result;
                        profilePreview.style.display = 'block';
                        profileIcon.style.display = 'none';

                        // Aggiorna l'anteprima
                        updatePreviewImage(e.target.result);
                    };

                    reader.readAsDataURL(file);
                }
            });

            // Aggiorna l'anteprima del nome d'arte
            stageNameInput.addEventListener('input', function() {
                updatePreviewName(this.value || 'Artista');
            });

            // Aggiorna i dettagli dell'anteprima quando cambiano i campi
            function updatePreviewDetails() {
                let details = '';

                // Nome e cognome
                const fullName = nameInput.value && surnameInput.value ?
                    `${nameInput.value} ${surnameInput.value}` :
                    '';

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
                }

                // Genere
                let gender = '';
                genderInputs.forEach(input => {
                    if (input.checked) {
                        switch (input.value) {
                            case 'male':
                                gender = 'Uomo';
                                break;
                            case 'female':
                                gender = 'Donna';
                                break;
                            case 'other':
                                gender = 'Altro';
                                break;
                        }
                    }
                });

                // Visibilità
                const visibility = visibleCheckbox.checked ? 'Pubblico' : 'Non pubblico';

                // Costruisci i dettagli
                if (fullName) details += fullName;
                if (age) details += (details ? ', ' : '') + age + ' anni';
                if (gender) details += (details ? ', ' : '') + gender;
                details += (details ? ' • ' : '') + visibility;

                previewDetails.textContent = details || 'Dettagli artista';
            }

            // Aggiungi event listener per gli input
            nameInput.addEventListener('input', updatePreviewDetails);
            surnameInput.addEventListener('input', updatePreviewDetails);
            birthDateInput.addEventListener('input', updatePreviewDetails);
            genderInputs.forEach(input => {
                input.addEventListener('change', updatePreviewDetails);
            });
            visibleCheckbox.addEventListener('change', updatePreviewDetails);

            // Aggiungi animazioni ai radio button e checkbox
            const genderOptions = document.querySelectorAll('.gender-option');
            genderOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const radio = this.querySelector('input[type="radio"]');
                    radio.checked = true;

                    // Simula animazione di selezione
                    this.style.transform = 'scale(1.05)';
                    setTimeout(() => {
                        this.style.transform = 'translateY(-2px)';
                    }, 150);

                    updatePreviewDetails();
                });
            });

            const visibleCheckboxContainer = document.querySelector('.visible-checkbox');
            visibleCheckboxContainer.addEventListener('click', function(e) {
                if (e.target !== visibleCheckbox) {
                    visibleCheckbox.checked = !visibleCheckbox.checked;
                    updatePreviewDetails();
                }
            });

            // Funzioni per aggiornare l'anteprima
            function updatePreviewImage(imageUrl) {
                previewImage.innerHTML = `<img src="${imageUrl}" alt="Anteprima Immagine Profilo" class="animate-in">`;

                // Aggiungi una classe per l'animazione
                const img = previewImage.querySelector('img');
                img.style.opacity = '0';
                img.style.transform = 'scale(0.8)';

                setTimeout(() => {
                    img.style.transition = 'all 0.3s ease';
                    img.style.opacity = '1';
                    img.style.transform = 'scale(1)';
                }, 50);
            }

            function updatePreviewName(name) {
                // Animazione di transizione del testo
                const oldText = previewName.textContent;
                if (oldText !== name) {
                    previewName.style.opacity = '0';
                    previewName.style.transform = 'translateY(10px)';

                    setTimeout(() => {
                        previewName.textContent = name;
                        previewName.style.transition = 'all 0.3s ease';
                        previewName.style.opacity = '1';
                        previewName.style.transform = 'translateY(0)';
                    }, 200);
                } else {
                    previewName.textContent = name;
                }
            }

            // Inizializza l'anteprima
            updatePreviewDetails();

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

            const previewPanel = document.querySelector('.preview-panel');
            previewPanel.style.opacity = '0';

            setTimeout(() => {
                previewPanel.style.transition = 'all 0.8s ease';
                previewPanel.style.opacity = '1';
            }, 300);

            const actionButtons = document.querySelector('.action-buttons');
            actionButtons.style.opacity = '0';
            actionButtons.style.transform = 'translateY(20px)';

            setTimeout(() => {
                actionButtons.style.transition = 'all 0.5s ease';
                actionButtons.style.opacity = '1';
                actionButtons.style.transform = 'translateY(0)';
            }, 500);
        });
    </script>
</body>

</html>