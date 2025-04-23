CREATE DATABASE IF NOT EXISTS BUSSANORICCARDO;
USE BUSSANORICCARDO;
CREATE TABLE IF NOT EXISTS `artist` (
    `id` VARCHAR(36) PRIMARY KEY CHECK (`id` REGEXP '^[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}$'),
    `stage_name` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `surname` VARCHAR(255) NOT NULL,
    `birth_date` DATE NOT NULL,
    `biography` TEXT NOT NULL,
    `gender` VARCHAR(10) NOT NULL CHECK(`gender`='male' OR `gender`='female' OR `gender`='other'),
    `profile_picture` VARCHAR(255) NOT NULL,
    `visible` BOOLEAN NOT NULL DEFAULT TRUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `song` (
    `id` VARCHAR(36) PRIMARY KEY CHECK (`id` REGEXP '^[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}$'),
    `title` VARCHAR(255) NOT NULL,
    `duration` INT NOT NULL,
    `release_date` DATE NOT NULL,
    `lyrics` TEXT,
    `cover_image` VARCHAR(255) NOT NULL, -- Cover image filesystem references,
    `canvas_background_image` VARCHAR(255), -- Canvas background image filesystem references, 
    `audio_file` VARCHAR(255) NOT NULL, -- Audio file filesystem references,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `interpretation` (
    `artist_id` VARCHAR(36) NOT NULL,
    `song_id` VARCHAR(36) NOT NULL,
    `interpretation_type` VARCHAR(20) NOT NULL CHECK (`interpretation_type` IN ('main', 'featured')),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`artist_id`, `song_id`),
    FOREIGN KEY (`artist_id`) REFERENCES `artist`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`song_id`) REFERENCES `song`(`id`) ON DELETE CASCADE
);