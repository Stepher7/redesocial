<?php

// CREATE
function inserirPublicacao($con, $dados) {
    $stmt = $con->prepare(
        "INSERT INTO publicacoes (id_usuario, conteudo) VALUES (?, ?)"
    );
    $stmt->execute([$dados["id_usuario"], $dados["conteudo"]]);
    return $con->lastInsertId();
}

// READ
function listarPublicacoes($con) {
    $stmt = $con->query(
        "SELECT publicacoes.*, usuarios.nome
         FROM publicacoes
         INNER JOIN usuarios ON usuarios.id_usuario = publicacoes.id_usuario
         ORDER BY publicacoes.id_publicacao DESC"
    );
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// READ por ID
function buscarPublicacao($con, $id) {
    $stmt = $con->prepare("SELECT * FROM publicacoes WHERE id_publicacao = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// UPDATE
function atualizarPublicacao($con, $id, $dados) {
    $stmt = $con->prepare(
        "UPDATE publicacoes SET conteudo = ? WHERE id_publicacao = ?"
    );
    $stmt->execute([$dados["conteudo"], $id]);
}

// DELETE
function removerPublicacao($con, $id) {
    $stmt = $con->prepare("DELETE FROM publicacoes WHERE id_publicacao = ?");
    $stmt->execute([$id]);
}
