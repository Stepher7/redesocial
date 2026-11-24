<?php

function inserirPublicacao($con, $dados) {
    $stmt = $con->prepare(
        "INSERT INTO publicacoes (id_usuario, conteudo, imagem_url) VALUES (?, ?, ?)"
    );
    $stmt->execute([
        $dados["id_usuario"],
        $dados["conteudo"],
        $dados["imagem_url"] ?? null
    ]);

    return $con->lastInsertId();
}

function listarPublicacoes($con, $idUsuario = 0) {
    $stmt = $con->prepare(
        "SELECT p.id_publicacao, p.id_usuario, p.conteudo, p.imagem_url, p.criado_em,
                u.nome, u.username,
                (SELECT COUNT(*) FROM curtidas c WHERE c.id_publicacao = p.id_publicacao) AS total_curtidas,
                (SELECT COUNT(*) FROM curtidas c WHERE c.id_publicacao = p.id_publicacao
                 AND c.id_usuario = ?) AS curtiu
         FROM publicacoes p
         INNER JOIN usuarios u ON u.id_usuario = p.id_usuario
         ORDER BY p.criado_em DESC, p.id_publicacao DESC"
    );
    $stmt->execute([$idUsuario]);
    return $stmt->fetchAll();
}

function buscarPublicacao($con, $id) {
    $stmt = $con->prepare("SELECT * FROM publicacoes WHERE id_publicacao = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function removerPublicacao($con, $id) {
    $stmt = $con->prepare("DELETE FROM publicacoes WHERE id_publicacao = ?");
    $stmt->execute([$id]);
}
