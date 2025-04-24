<?php

declare(strict_types=1);

/**
 * Artist class represents an artist in the jukebox system.
 */
class Artist
{
    public string $id;
    public string $stage_name;
    public string $name;
    public string $surname;
    public string $birth_date;
    public string $biography;
    public string $gender;
    public string $profile_picture;
    public bool $visible;
    public string $created_at;
    public string $updated_at;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->stage_name = $data['stage_name'];
        $this->name = $data['name'];
        $this->surname = $data['surname'];
        $this->birth_date = $data['birth_date'];
        $this->biography = $data['biography'];
        $this->gender = $data['gender'];
        $this->profile_picture = $data['profile_picture'];
        $this->visible = (bool) $data['visible'];
        $this->created_at = $data['created_at'];
        $this->updated_at = $data['updated_at'];
    }

    public static function builder(): ArtistBuilder
    {
        return new ArtistBuilder();
    }

    /**
     * Get the file name of the profile picture of the artist, that is of this type UUID.[jpg|png|webp]
     * @return string The file name of the profile picture, or the default image if not found.
     */
    public function getProfilePictureFileName(): string
    {
        $cover_dir = __DIR__ . "/../database/data/profile_pictures/";
        $coverName = glob($cover_dir . $this->profile_picture . ".*");
        return !empty($coverName) ? basename($coverName[0]) : 'default_pfp.jpg';
    }
}

/**
 * ArtistBuilder is a builder class for creating Artist objects.
 * It provides methods to set the properties of the Artist object.
 */
class ArtistBuilder
{
    private array $data = [];

    public function setId(string $id): self
    {
        $this->data['id'] = $id;
        return $this;
    }

    public function setStageName(string $stage_name): self
    {
        $this->data['stage_name'] = $stage_name;
        return $this;
    }

    public function setName(string $name): self
    {
        $this->data['name'] = $name;
        return $this;
    }

    public function setSurname(string $surname): self
    {
        $this->data['surname'] = $surname;
        return $this;
    }

    public function setBirthDate(string $birth_date): self
    {
        $this->data['birth_date'] = $birth_date;
        return $this;
    }

    public function setBiography(string $biography): self
    {
        $this->data['biography'] = $biography;
        return $this;
    }

    public function setGender(string $gender): self
    {
        $this->data['gender'] = $gender;
        return $this;
    }

    public function setProfilePicture(string $profile_picture): self
    {
        $this->data['profile_picture'] = $profile_picture;
        return $this;
    }

    public function setVisible(bool $visible): self
    {
        $this->data['visible'] = $visible;
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

    public function build(): Artist
    {
        return new Artist($this->data);
    }
}
