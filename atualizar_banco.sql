USE instagram_db;

ALTER TABLE usuarios
ADD COLUMN username VARCHAR(40) NULL AFTER nome;

UPDATE usuarios
SET username = CONCAT(LOWER(SUBSTRING_INDEX(email, "@", 1)), id_usuario)
WHERE username IS NULL OR username = "";

ALTER TABLE usuarios
MODIFY username VARCHAR(40) NOT NULL,
ADD UNIQUE KEY username_unico (username);

ALTER TABLE publicacoes
ADD COLUMN imagem_url VARCHAR(500) DEFAULT NULL,
ADD COLUMN criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE curtidas
ADD COLUMN criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
