<?php

declare(strict_types=1);

require_once __DIR__ . "/BaseModel.php";
require_once __DIR__ . "/../classes/Song.php";
require_once __DIR__ . "/../classes/FilesystemManager.php";

class SongModel extends BaseModel
{
    /**
     * Return all songs
     * 
     * @return Song[]
     */
    public function getAllSongs(): array
    {
        $sql_query = "SELECT * FROM song";
        $result = $this->connection->query($sql_query);

        if (!$result) {
            throw new Exception('Query failed: ' . $this->connection->error);
        }

        $songs = [];
        while ($data = $result->fetch_assoc()) {
            // append the new song to the array
            $songs[] = new Song($data);
        }

        return $songs;
    }

    public function getSongById(string $uuid)
    {
        $sql_query = "SELECT * FROM song WHERE id = '$uuid'";
        $result = $this->connection->query($sql_query);

        if (!$result) {
            throw new Exception('Query failed: ' . $this->connection->error);
        }

        $data = $result->fetch_assoc();

        if (!$data) {
            return null;
        }

        $song = new Song($data);
        return $song;
    }

    public function createSong(Song $song)
    {
        $sql_query = "INSERT INTO song (id, title, duration, release_date, lyrics, audio_file, cover_image, canvas_background_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql_query);
        $status = $stmt->bind_param(
            "ssssssss",
            $song->id,
            $song->title,
            $song->duration,
            $song->release_date,
            $song->lyrics,
            $song->audio_file,
            $song->cover_image,
            $song->canvas_background_image
        );
        if (!$status) {
            throw new Exception('Binding parameters failed: ' . $stmt->error);
        }
        $status = $stmt->execute();
        if (!$status) {
            throw new Exception('Execution failed: ' . $stmt->error);
        }
        $stmt->close();
    }

    // public function updateSong(Song $song)
    // {
    //     $sql_query = "UPDATE song SET title = ?, duration = ?, release_date = ?, lyrics = ?, audio_file = ?, cover_image = ?, canvas_background_image = ? WHERE id = ?";
    //     $stmt = $this->connection->prepare($sql_query);
    //     $status = $stmt->bind_param(
    //         "ssssssss",
    //         $song->title,
    //         $song->duration,
    //         $song->release_date,
    //         $song->lyrics,
    //         $song->audio_file,
    //         $song->cover_image,
    //         $song->canvas_background_image,
    //         $song->id
    //     );
    //     if (!$status) {
    //         throw new Exception('Binding parameters failed: ' . $stmt->error);
    //     }
    //     $status = $stmt->execute();
    //     if (!$status) {
    //         throw new Exception('Execution failed: ' . $stmt->error);
    //     }
    //     $stmt->close();
    // }

    /**
     * Aggiorna un brano esistente
     */
    public function updateSong(string $id, string $title, int $duration, string $release_date, string $lyrics, string $audio_file, string $canvas_background_image = ''): void
    {
        if (!UUID::checkV4($id)) {
            throw new Exception('Invalid UUID format');
        }

        $sql_query = "UPDATE song SET title = ?, duration = ?, release_date = ?, lyrics = ?, audio_file = ?, canvas_background_image = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->connection->prepare($sql_query);
        $stmt->bind_param("sisssss", $title, $duration, $release_date, $lyrics, $audio_file, $canvas_background_image, $id);

        if (!$stmt->execute()) {
            throw new Exception('Failed to update song: ' . $stmt->error);
        }
    }

    /**
     * Delete song by id
     * @param string $uuid
     */
    public function deleteSong(string $uuid)
    {
        $sql_query = "DELETE FROM song WHERE id = ?";
        $stmt = $this->connection->prepare($sql_query);
        $status = $stmt->bind_param("s", $uuid);

        if (!$status) {
            throw new Exception('Binding parameters failed: ' . $this->connection->error);
        }

        $status = $stmt->execute();

        if (!$status) {
            throw new Exception('Execution failed: ' . $stmt->error);
        }
        $stmt->close();

        // Delete the files associated with the song from the filesystem
        FilesystemManager::deleteSongFiles($uuid);
    }
}
