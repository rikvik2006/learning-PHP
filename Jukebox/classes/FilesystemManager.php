<?php

declare(strict_types=1);

/**
 * FilesystemManager gestisce le operazioni sui file nel sistema Jukebox.
 * Permette di eliminare i file associati a brani e artisti quando questi vengono rimossi dal database.
 */
class FilesystemManager
{
    // Percorsi base delle directory di archiviazione
    private const BASE_PATH = __DIR__ . "/../database/data/";
    private const SONGS_COVERS_PATH = self::BASE_PATH . "songs_covers/";
    private const SONGS_AUDIO_PATH = self::BASE_PATH . "songs_audio/";
    private const SONGS_CANVAS_PATH = self::BASE_PATH . "songs_canvas/";
    private const PROFILE_PICTURES_PATH = self::BASE_PATH . "profile_pictures/";

    /**
     * Elimina tutti i file associati a un brano specifico
     * 
     * @param string $songId L'identificatore UUID del brano
     * @return bool True se tutti i file sono stati eliminati con successo
     */
    public static function deleteSongFiles(string $songId): bool
    {
        $success = true;

        // Elimina la copertina del brano
        if (!self::deleteFilesWithPattern(self::SONGS_COVERS_PATH, $songId)) {
            $success = false;
        }

        // Elimina il file audio del brano
        if (!self::deleteFilesWithPattern(self::SONGS_AUDIO_PATH, $songId)) {
            $success = false;
        }

        // Elimina lo sfondo canvas del brano (opzionale)
        if (!self::deleteFilesWithPattern(self::SONGS_CANVAS_PATH, $songId)) {
            $success = false;
        }

        return $success;
    }

    /**
     * Elimina tutti i file associati a un artista specifico
     * 
     * @param string $artistId L'identificatore UUID dell'artista
     * @return bool True se tutti i file sono stati eliminati con successo
     */
    public static function deleteArtistFiles(string $artistId): bool
    {
        // Elimina l'immagine del profilo dell'artista
        return self::deleteFilesWithPattern(self::PROFILE_PICTURES_PATH, $artistId);
    }

    /**
     * Elimina tutti i file che corrispondono a un determinato pattern in una directory
     * 
     * @param string $directory Il percorso della directory da cui eliminare i file
     * @param string $pattern Il pattern (identificatore) per trovare i file da eliminare
     * @return bool True se tutti i file sono stati eliminati con successo
     */
    private static function deleteFilesWithPattern(string $directory, string $pattern): bool
    {
        if (!is_dir($directory)) {
            // La directory non esiste, consideriamo l'operazione riuscita
            return true;
        }

        $success = true;
        $files = glob($directory . $pattern . ".*");

        if (empty($files)) {
            // Nessun file da eliminare, consideriamo l'operazione riuscita
            return true;
        }

        foreach ($files as $file) {
            if (file_exists($file)) {
                if (!unlink($file)) {
                    // Impossibile eliminare il file
                    error_log("Impossibile eliminare il file: $file");
                    $success = false;
                }
            }
        }

        return $success;
    }

    // /**
    //  * Ottiene il nome del file dell'immagine del profilo di un artista
    //  * 
    //  * @param string $artistId L'identificatore UUID dell'artista
    //  * @return string|null Il nome del file o null se non trovato
    //  */
    // public static function getArtistProfilePictureFileName(string $artistId): ?string
    // {
    //     $files = glob(self::PROFILE_PICTURES_PATH . $artistId . ".*");
    //     return !empty($files) ? basename($files[0]) : null;
    // }

    // /**
    //  * Ottiene il nome del file della copertina di un brano
    //  * 
    //  * @param string $songId L'identificatore UUID del brano
    //  * @return string|null Il nome del file o null se non trovato
    //  */
    // public static function getSongCoverFileName(string $songId): ?string
    // {
    //     $files = glob(self::SONGS_COVERS_PATH . $songId . ".*");
    //     return !empty($files) ? basename($files[0]) : null;
    // }

    // /**
    //  * Ottiene il nome del file audio di un brano
    //  * 
    //  * @param string $songId L'identificatore UUID del brano
    //  * @return string|null Il nome del file o null se non trovato
    //  */
    // public static function getSongAudioFileName(string $songId): ?string
    // {
    //     $files = glob(self::SONGS_AUDIO_PATH . $songId . ".*");
    //     return !empty($files) ? basename($files[0]) : null;
    // }

    // /**
    //  * Ottiene il nome del file canvas di un brano
    //  * 
    //  * @param string $songId L'identificatore UUID del brano
    //  * @return string|null Il nome del file o null se non trovato
    //  */
    // public static function getSongCanvasFileName(string $songId): ?string
    // {
    //     $files = glob(self::SONGS_CANVAS_PATH . $songId . ".*");
    //     return !empty($files) ? basename($files[0]) : null;
    // }

    // /**
    //  * Verifica se un file esiste in una specifica directory
    //  * 
    //  * @param string $directory La directory in cui cercare
    //  * @param string $fileId L'identificatore del file (senza estensione)
    //  * @return bool True se il file esiste, false altrimenti
    //  */
    // public static function fileExists(string $directory, string $fileId): bool
    // {
    //     $files = glob($directory . $fileId . ".*");
    //     return !empty($files);
    // }
}
