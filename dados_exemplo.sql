INSERT IGNORE INTO usuarios (nome, username, email, senha) VALUES
("Marina Rocha", "marina.rocha", "marina@conecta.com", "$2y$12$FcYE41TIbO36J1guitrWUOPDcaV1VKin/K8W.40a7LHeOiZrtf1c2"),
("Lucas Martins", "lucas.martins", "lucas@conecta.com", "$2y$12$FcYE41TIbO36J1guitrWUOPDcaV1VKin/K8W.40a7LHeOiZrtf1c2"),
("Ana Clara", "anaclara", "ana@conecta.com", "$2y$12$FcYE41TIbO36J1guitrWUOPDcaV1VKin/K8W.40a7LHeOiZrtf1c2");

INSERT INTO publicacoes (id_usuario, conteudo)
SELECT id_usuario, "Começando a semana com energia boa. Que seja uma semana leve!"
FROM usuarios WHERE username = "marina.rocha"
AND NOT EXISTS (SELECT 1 FROM publicacoes WHERE conteudo = "Começando a semana com energia boa. Que seja uma semana leve!");

INSERT INTO publicacoes (id_usuario, conteudo)
SELECT id_usuario, "Alguém mais gosta de conhecer lugares novos no fim de semana?"
FROM usuarios WHERE username = "lucas.martins"
AND NOT EXISTS (SELECT 1 FROM publicacoes WHERE conteudo = "Alguém mais gosta de conhecer lugares novos no fim de semana?");

INSERT INTO publicacoes (id_usuario, conteudo)
SELECT id_usuario, "Hoje tirei um tempinho para cuidar de mim. Faz diferença!"
FROM usuarios WHERE username = "anaclara"
AND NOT EXISTS (SELECT 1 FROM publicacoes WHERE conteudo = "Hoje tirei um tempinho para cuidar de mim. Faz diferença!");

INSERT IGNORE INTO curtidas (id_usuario, id_publicacao)
SELECT u.id_usuario, p.id_publicacao
FROM usuarios u
JOIN publicacoes p ON p.conteudo = "Começando a semana com energia boa. Que seja uma semana leve!"
WHERE u.username = "lucas.martins";
