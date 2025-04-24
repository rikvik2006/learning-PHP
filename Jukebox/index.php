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

$songModel = new SongModel();
$songs = $songModel->getAllSongs();

$artistModel = new ArtistModel();
$artists = $artistModel->getAllArtist();

$interpretationModel = new InterpretationModel();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/main.css">
    <link rel="stylesheet" href="./styles/home/home.css">
    <link rel="stylesheet" href="./styles/global/navbar.css">
    <link rel="stylesheet" href="./styles/global/song.css">
    <link rel="stylesheet" href="./styles/global/artist.css">

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
                    <input type="text" placeholder="Cerca">
                </form>
            </div>
            <section class="song_selection_section">
                <div class="recently_listened">
                    <h1 class="section_title">Ascoltati di recente</h1>
                    <div class="item_container">
                        <?php while ($song = array_shift($songs)): ?>
                            <!-- Start Song Template -->
                            <a href="./song/?id=<?= htmlspecialchars($song->id) ?>" class="song_item">
                                <div class="song_image">
                                    <img src="./database/data/songs_covers/<?= htmlspecialchars($song->getCoverImageFileName()) ?>" alt="Album Cover">
                                </div>
                                <div class="song_details">
                                    <div class="song_type">Brano</div>
                                    <div class="title_artist_container">
                                        <div class="song_artist"><?= htmlspecialchars(implode(', ', array_map(fn($artist) => $artist->stage_name, $interpretationModel->getArtistsBySong($song->id)))) ?></div>
                                        <h2 class="song_title"><?= htmlspecialchars($song->title) ?></h2>
                                    </div>
                                </div>
                            </a>
                            <!-- End Song Template -->
                        <?php endwhile; ?>
                    </div>
                </div>
                <div class="artist_section">
                    <h1 class="section_title">Artisti aggiunti di recente</h1>
                    <div class="item_container">
                        <?php while ($artist = array_shift($artists)): ?>
                            <!-- Start Artist Template -->
                            <a href="./artist/?id=<?= htmlspecialchars($artist->id) ?>" class="artist_item">
                                <div class="artist_image">
                                    <img src="./database/data/profile_pictures/<?= htmlspecialchars($artist->getProfilePictureFileName()) ?>" alt="Artist Image">
                                </div>
                                <div class="artist_details">
                                    <div class="song_type">Artista</div>
                                    <h2 class="artist_name"><?= htmlspecialchars($artist->stage_name) ?></h2>
                                </div>
                            </a>
                            <!-- End Artist Template -->
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
        </section>
        <section class="player_section">
            <div>Player</div>
        </section>
    </div>
</body>

</html>