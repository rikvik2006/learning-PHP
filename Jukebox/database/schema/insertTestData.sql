USE BUSSANORICCARDO;

INSERT INTO `artist` (`id`, `stage_name`, `name`, `surname`, `birth_date`, `biography`, `gender`, `profile_picture`) VALUES
('0211306f-c841-4545-80f3-75e88cbc04bd', 'Kendrick Lamar', 'Kendrick', 'Lamar', '1987-06-17', 'Kendrick Lamar Duckworth (born June 17, 1987) is an American rapper, songwriter, and record producer. He is known for his intricate lyricism and storytelling ability, often addressing themes of social issues, personal struggles, and resilience.', 'male', '0211306f-c841-4545-80f3-75e88cbc04bd'),
('0fd3209f-511f-4d14-b05e-76b85a9c9a94', 'Drake', 'Aubrey', 'Graham', '1986-10-24', 'Aubrey Drake Graham (born October 24, 1986) is a Canadian rapper, singer, songwriter, and actor. He is known for his introspective lyrics and blending of various musical genres, including hip hop, R&B, and pop.', 'male', '0fd3209f-511f-4d14-b05e-76b85a9c9a94'),
('a443eb1d-79dc-403d-b7d1-c1765295c19a', 'Travis Scott', 'Jacques', 'Webster', '1991-04-30', 'Jacques Webster Jr. (born April 30, 1991), known professionally as Travis Scott, is an American rapper, singer, songwriter, and record producer. He is known for his unique sound that blends elements of hip hop, psychedelic music, and ambient music.', 'male', 'a443eb1d-79dc-403d-b7d1-c1765295c19a'),
('600ce055-abe3-4d20-85a9-6d0e8069ed61', 'Tyler, The Creator', 'Tyler', 'Okonma', '1991-03-06', 'Tyler Gregory Okonma (born March 6, 1991), known professionally as Tyler, The Creator, is an American rapper, singer, songwriter, record producer, and music video director. He is known for his eclectic musical style and controversial lyrics.', 'male', '600ce055-abe3-4d20-85a9-6d0e8069ed61');


UPDATE artist SET `visible` = 1 WHERE `id` = "0fd3209f-511f-4d14-b05e-76b85a9c9a94";