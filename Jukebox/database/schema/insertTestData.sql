USE BUSSANORICCARDO;

INSERT INTO `artist` (`id`, `stage_name`, `name`, `surname`, `birth_date`, `biography`, `gender`, `profile_picture`) VALUES
('0211306f-c841-4545-80f3-75e88cbc04bd', 'Kendrick Lamar', 'Kendrick', 'Lamar', '1987-06-17', 'Kendrick Lamar Duckworth (born June 17, 1987) is an American rapper, songwriter, and record producer. He is known for his intricate lyricism and storytelling ability, often addressing themes of social issues, personal struggles, and resilience.', 'male', '0211306f-c841-4545-80f3-75e88cbc04bd'),
('0fd3209f-511f-4d14-b05e-76b85a9c9a94', 'Drake', 'Aubrey', 'Graham', '1986-10-24', 'Aubrey Drake Graham (born October 24, 1986) is a Canadian rapper, singer, songwriter, and actor. He is known for his introspective lyrics and blending of various musical genres, including hip hop, R&B, and pop.', 'male', '0fd3209f-511f-4d14-b05e-76b85a9c9a94'),
('a443eb1d-79dc-403d-b7d1-c1765295c19a', 'Travis Scott', 'Jacques', 'Webster', '1991-04-30', 'Jacques Webster Jr. (born April 30, 1991), known professionally as Travis Scott, is an American rapper, singer, songwriter, and record producer. He is known for his unique sound that blends elements of hip hop, psychedelic music, and ambient music.', 'male', 'a443eb1d-79dc-403d-b7d1-c1765295c19a'),
('600ce055-abe3-4d20-85a9-6d0e8069ed61', 'Tyler, The Creator', 'Tyler', 'Okonma', '1991-03-06', 'Tyler Gregory Okonma (born March 6, 1991), known professionally as Tyler, The Creator, is an American rapper, singer, songwriter, record producer, and music video director. He is known for his eclectic musical style and controversial lyrics.', 'male', '600ce055-abe3-4d20-85a9-6d0e8069ed61');

INSERT INTO `song` (`id`, `title`, `duration`, `release_date`, `lyrics`, `cover_image`, `canvas_background_image`, `audio_file`) VALUES
('b585fdc3-cc0f-4857-a3d1-93b73e5fa99f', 'HUMBLE.', 180, '2017-03-30', 'Get the f*** off my stage, I am the Sandman', 'b585fdc3-cc0f-4857-a3d1-93b73e5fa99f', 'b585fdc3-cc0f-4857-a3d1-93b73e5fa99f', 'b585fdc3-cc0f-4857-a3d1-93b73e5fa99f'),
('407ec8e6-1a15-4f85-91be-418bff13342d', 'God\'s Plan', 198, '2018-01-19', 'I only love you and your body, I don\'t want to be your friend', '407ec8e6-1a15-4f85-91be-418bff13342d', '407ec8e6-1a15-4f85-91be-418bff13342d', '407ec8e6-1a15-4f85-91be-418bff13342d'),
('508bc3a8-361e-43f7-acb8-150a484f3497', 'SICKO MODE', 320, '2018-08-21', 'Astro, yeah, Sun is down, freezin\' cold', '508bc3a8-361e-43f7-acb8-150a484f3497', '508bc3a8-361e-43f7-acb8-150a484f3497', '508bc3a8-361e-43f7-acb8-150a484f3497'),
('d9784d27-7e51-4b9c-ad34-e58ebb389904', 'EARFQUAKE', 210, '2019-05-17', 'For this life, I cannot change', 'd9784d27-7e51-4b9c-ad34-e58ebb389904', 'd9784d27-7e51-4b9c-ad34-e58ebb389904', 'd9784d27-7e51-4b9c-ad34-e58ebb389904');

INSERT INTO `interpretation` (`artist_id`, `song_id`, `interpretation_type`) VALUES
('0211306f-c841-4545-80f3-75e88cbc04bd', 'b585fdc3-cc0f-4857-a3d1-93b73e5fa99f', 'main'), -- Kendrick, Humble
('0fd3209f-511f-4d14-b05e-76b85a9c9a94', '407ec8e6-1a15-4f85-91be-418bff13342d', 'main'), -- Drake, God's Plan
('a443eb1d-79dc-403d-b7d1-c1765295c19a', '508bc3a8-361e-43f7-acb8-150a484f3497', 'main'), -- Travis, Sicko Mode
('600ce055-abe3-4d20-85a9-6d0e8069ed61', 'd9784d27-7e51-4b9c-ad34-e58ebb389904', 'main'); -- Tyler, EARFQUAKE


INSERT INTO `artist` (`id`, `stage_name`, `name`, `surname`, `birth_date`, `biography`, `gender`, `profile_picture`) VALUES
("23c37709-dea3-4ab0-86fa-46e9c736dda7", "Metro Boomin", "Leland", "Wayne", "1993-09-16", "Leland Tyler Wayne (born September 16, 1993), known professionally as Metro Boomin, is an American record producer, record executive, and DJ. He is known for his work in the hip hop genre and has produced numerous chart-topping hits.", "male",'23c37709-dea3-4ab0-86fa-46e9c736dda7');

INSERT INTO `song` (`id`, `title`, `duration`, `release_date`, `lyrics`, `cover_image`, `canvas_background_image`, `audio_file`) VALUES
("c5d07fb7-0ce2-41b9-a535-07699c07f5fc", "Like That", 180, "2023-09-29", "I like it when you call me big poppa", "c5d07fb7-0ce2-41b9-a535-07699c07f5fc", "c5d07fb7-0ce2-41b9-a535-07699c07f5fc", "c5d07fb7-0ce2-41b9-a535-07699c07f5fc");

INSERT INTO `interpretation` (`artist_id`, `song_id`, `interpretation_type`) VALUES
("23c37709-dea3-4ab0-86fa-46e9c736dda7", "c5d07fb7-0ce2-41b9-a535-07699c07f5fc", "main"),
("0211306f-c841-4545-80f3-75e88cbc04bd", "c5d07fb7-0ce2-41b9-a535-07699c07f5fc", "featured");

UPDATE artist SET `visible` = 1 WHERE `id` = "0fd3209f-511f-4d14-b05e-76b85a9c9a94";

INSERT INTO `artist` (`id`, `stage_name`, `name`, `surname`, `birth_date`, `biography`, `gender`, `profile_picture`) VALUES
('600ce055-abe3-4d20-85a9-6d0e8069ed61', 'Tyler, The Creator', 'Tyler', 'Okonma', '1991-03-06', 'Tyler Gregory Okonma (born March 6, 1991), known professionally as Tyler, The Creator, is an American rapper, singer, songwriter, record producer, and music video director. He is known for his eclectic musical style and controversial lyrics.', 'male', '600ce055-abe3-4d20-85a9-6d0e8069ed61');