<?php

// CREATE
function inserirCurtida($con, $dados) {
    $stmt = $con->prepare(
        "INSERT INTO curtidas (id_usuario, id_publicacao) VALUES (?, ?)"
    );
    $stmt->execute([$dados["id_usuario"], $dados["id_publicacao"]]);
    return $con->lastInsertId();
}

// READ
function listarCurtidas($con) {
    $stmt = $con->query(
        "SELECT curtidas.*, usuarios.nome
         FROM curtidas
         INNER JOIN usuarios ON usuarios.id_usuario = curtidas.id_usuario
         ORDER BY curtidas.id_curtida DESC"
    );
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// DELETE
function removerCurtida($con, $dados) {
    $stmt = $con->prepare(
        "DELETE FROM curtidas WHERE id_usuario = ? AND id_publicacao = ?"
    );
    $stmt->execute([$dados["id_usuario"], $dados["id_publicacao"]]);
}
