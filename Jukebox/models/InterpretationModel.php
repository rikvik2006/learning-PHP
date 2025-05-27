<?php

declare(strict_types=1);

require_once __DIR__ . "/BaseModel.php";
require_once __DIR__ . "/../classes/Artist.php";
require_once __DIR__ . "/../classes/Song.php";
require_once __DIR__ . "/../classes/Utils.php";

class InterpretationModel extends BaseModel
{
    /**
     * Associa un artista a una canzone.
     *
     * @param string $artistId
     * @param string $songId
     * @param string $interpretationType
     * @throws Exception
     */
    public function associateArtistToSong(string $artistId, string $songId, string $interpretationType): void
    {
        // Check if the artistId and songId are valid UUIDs
        if (!UUID::checkV4($artistId)) {
            throw new Exception('Invalid UUID format for artistId');
        }
        if (!UUID::checkV4($songId)) {
            throw new Exception('Invalid UUID format for songId');
        }

        $sql_query = "INSERT INTO interpretation (artist_id, song_id, interpretation_type) VALUES (?, ?, ?)";
        $stmt = $this->connection->prepare($sql_query);
        $status = $stmt->bind_param("sss", $artistId, $songId, $interpretationType);

        if (!$status) {
            throw new Exception('Bind failed: ' . $this->connection->error);
        }

        $stmt->execute();
        if ($stmt->error) {
            throw new Exception('Execute failed: ' . $stmt->error);
        }

        $stmt->close();
    }

    /**
     * Recupera tutti gli artisti associati a una canzone.
     *
     * @param string $songId
     * @return Artist[]
     * @throws Exception
     */
    public function getArtistsBySong(string $songId): array
    {
        // Check if the songId is a valid UUID
        if (!UUID::checkV4($songId)) {
            throw new Exception('Invalid UUID format for songId');
        }

        $sql_query = "
            SELECT a.* 
            FROM artist a
            INNER JOIN interpretation i ON a.id = i.artist_id
            WHERE i.song_id = ?
        ";
        $stmt = $this->connection->prepare($sql_query);
        $stmt->bind_param("s", $songId);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            throw new Exception('Query failed: ' . $this->connection->error);
        }

        $artists = [];
        while ($row = $result->fetch_assoc()) {
            $artists[] = new Artist($row);
        }

        $stmt->close();
        return $artists;
    }

    /**
     * Recupera tutte le canzoni associate a un artista.
     *
     * @param string $artistId
     * @return Song[]
     * @throws Exception
     */
    public function getSongsByArtist(string $artistId): array
    {
        // Check if the artistId is a valid UUID
        if (!UUID::checkV4($artistId)) {
            throw new Exception('Invalid UUID format for artistId');
        }

        $sql_query = "
            SELECT s.* 
            FROM song s
            INNER JOIN interpretation i ON s.id = i.song_id
            WHERE i.artist_id = ?
        ";
        $stmt = $this->connection->prepare($sql_query);
        $stmt->bind_param("s", $artistId);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            throw new Exception('Query failed: ' . $this->connection->error);
        }

        $songs = [];
        while ($row = $result->fetch_assoc()) {
            $songs[] = new Song($row);
        }

        $stmt->close();
        return $songs;
    }

    /**
     * Rimuove un'associazione tra un artista e una canzone.
     *
     * @param string $artistId
     * @param string $songId
     * @throws Exception
     */
    public function removeAssociation(string $artistId, string $songId): void
    {
        // Check if the artistId and songId are valid UUIDs
        if (!UUID::checkV4($artistId)) {
            throw new Exception('Invalid UUID format for artistId');
        }
        if (!UUID::checkV4($songId)) {
            throw new Exception('Invalid UUID format for songId');
        }

        $sql_query = "DELETE FROM interpretation WHERE artist_id = ? AND song_id = ?";
        $stmt = $this->connection->prepare($sql_query);
        $stmt->bind_param("ss", $artistId, $songId);

        if (!$stmt->execute()) {
            throw new Exception('Query failed: ' . $stmt->error);
        }

        $stmt->close();
    }

    /**
     * Ottiene il tipo di interpretazione di un artista per una canzone specifica
     * 
     * @param string $artistId ID dell'artista
     * @param string $songId ID della canzone
     * @return string|null Il tipo di interpretazione ('main', 'featured') o null se non trovato
     * @throws Exception
     */
    public function getInterpretationType(string $artistId, string $songId): ?string
    {
        // Controllo validità UUID
        if (!UUID::checkV4($artistId)) {
            throw new Exception('Invalid UUID format for artistId');
        }
        if (!UUID::checkV4($songId)) {
            throw new Exception('Invalid UUID format for songId');
        }

        $sql_query = "SELECT interpretation_type FROM interpretation WHERE artist_id = ? AND song_id = ?";
        $stmt = $this->connection->prepare($sql_query);
        $stmt->bind_param("ss", $artistId, $songId);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            throw new Exception('Query failed: ' . $this->connection->error);
        }

        if ($row = $result->fetch_assoc()) {
            return $row['interpretation_type'];
        }

        return null;
    }

    /**
     * Elimina tutte le interpretazioni di un brano
     */
    public function deleteInterpretationsBySong(string $songId): void
    {
        if (!UUID::checkV4($songId)) {
            throw new Exception('Invalid UUID format for songId');
        }

        $sql_query = "DELETE FROM interpretation WHERE song_id = ?";
        $stmt = $this->connection->prepare($sql_query);
        $stmt->bind_param("s", $songId);

        if (!$stmt->execute()) {
            throw new Exception('Failed to delete interpretations: ' . $stmt->error);
        }
    }
}
