<?php

declare(strict_types=1);

require_once __DIR__ . "/BaseModel.php";
require_once __DIR__ . "/../classes/Artist.php";

class ArtistModel extends BaseModel
{
    /**
     * Return an array of artist
     * @return Artist[]
     */
    public function getAllArtist(): array
    {
        $sql_query = "SELECT * FROM artist";
        $result = $this->connection->query($sql_query);

        if (!$result) {
            throw new Exception('Query failed: ' . $this->connection->error);
        }

        $artists = [];
        while ($data = $result->fetch_assoc()) {
            // append the new artist to the array
            $artists[] = new Artist($data);
        }

        return $artists;
    }

    public function getArtistById(string $uuid)
    {
        $sql_query = "SELECT * FROM artist WHERE id = $uuid";
        $result = $this->connection->query($sql_query);

        if (!$result) {
            throw new Exception('Query failed: ' . $this->connection->error);
        }

        $data = $result->fetch_assoc();
        $artist = new Artist($data);
        return $artist;
    }

    public function createArtist(Artist $artist)
    {
        $sql_query = "INSERT INTO artist INSERT INTO `artist` (`id`, `stage_name`, `name`, `surname`, `birth_date`, `biography`, `gender`, `profile_picture`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql_query);
        $stauts = $stmt->bind_param(
            "ssssssss",
            $artist->id,
            $artist->stage_name,
            $artist->name,
            $artist->surname,
            $artist->birth_date,
            $artist->biography,
            $artist->gender,
            $artist->profile_picture
        );

        if (!$stauts) {
            throw new Exception('Bind failed: ' . $this->connection->error);
        }

        $stmt->execute();
        if ($stmt->error) {
            throw new Exception('Execute failed: ' . $stmt->error);
        }
        $stmt->close();
    }

    public function updateArtist(Artist $artist)
    {
        $sql_query = "UPDATE artist SET stage_name = ?, name = ?, surname = ?, birth_date = ?, biography = ?, gender = ?, profile_picture = ?, visible = ? WHERE id = ?";
        $stmt = $this->connection->prepare($sql_query);
        $status = $stmt->bind_param(
            "sssssssss",
            $artist->stage_name,
            $artist->name,
            $artist->surname,
            $artist->birth_date,
            $artist->biography,
            $artist->gender,
            $artist->profile_picture,
            $artist->visible,
            $artist->id
        );
    }

    /**
     * Delete artist by id
     * @param string $uuid
     */
    public function deleteArtist(string $uuid)
    {
        $sql_query = "DELETE FROM artist WHERE id = ?";
        $stmt = $this->connection->prepare($sql_query);
        $status = $stmt->bind_param("s", $uuid);

        if (!$status) {
            throw new Exception('Bind failed: ' . $this->connection->error);
        }

        $stmt->execute();
        if ($stmt->error) {
            throw new Exception('Execute failed: ' . $stmt->error);
        }
        $stmt->close();

        // Delete the files associated with the artist from the filesystem
        FilesystemManager::deleteArtistFiles($uuid);
    }
}
