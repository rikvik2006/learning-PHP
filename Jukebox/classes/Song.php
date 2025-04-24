<?php

/**
 * Song class represents a song in the jukebox system.
 */
class Song
{
    public string $id;
    public string $title;
    public string $duration;
    public string $release_date;
    public string $lyrics;

    public string $audio_file;
    public string $cover_image;
    public string $canvas_background_image;
    public string $created_at;
    public string $updated_at;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->duration = $data['duration'];
        $this->release_date = $data['release_date'];
        $this->lyrics = $data['lyrics'];
        $this->audio_file = $data['audio_file'];
        $this->cover_image = $data['cover_image'];
        $this->canvas_background_image = $data['canvas_background_image'];
        $this->created_at = $data['created_at'];
        $this->updated_at = $data['updated_at'];
    }

    public static function builder(): SongBuilder
    {
        return new SongBuilder();
    }
}

/**
 * SongBuilder is a builder class for creating Song objects.
 * It provides methods to set the properties of the Song object.
 */
class SongBuilder
{
    private array $data = [];

    public function setId(string $id): self
    {
        $this->data['id'] = $id;
        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->data['title'] = $title;
        return $this;
    }

    public function setDuration(string $duration): self
    {
        $this->data['duration'] = $duration;
        return $this;
    }

    public function setReleaseDate(string $release_date): self
    {
        $this->data['release_date'] = $release_date;
        return $this;
    }

    public function setLyrics(string $lyrics): self
    {
        $this->data['lyrics'] = $lyrics;
        return $this;
    }

    public function setAudioFile(string $audio_file): self
    {
        $this->data['audio_file'] = $audio_file;
        return $this;
    }

    public function setCoverImage(string $cover_image): self
    {
        $this->data['cover_image'] = $cover_image;
        return $this;
    }

    public function setCanvasBackgroundImage(string $canvas_background_image): self
    {
        $this->data['canvas_background_image'] = $canvas_background_image;
        return $this;
    }

    public function setCreatedAt(string $created_at): self
    {
        $this->data['created_at'] = $created_at;
        return $this;
    }

    public function setUpdatedAt(string $updated_at): self
    {
        $this->data['updated_at'] = $updated_at;
        return $this;
    }

    public function build(): Song
    {
        return new Song($this->data);
    }
}
