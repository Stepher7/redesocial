<?php

function inserirCurtida($con, $dados) {
    $stmt = $con->prepare(
        "INSERT IGNORE INTO curtidas (id_usuario, id_publicacao) VALUES (?, ?)"
    );
    $stmt->execute([$dados["id_usuario"], $dados["id_publicacao"]]);
}

function listarCurtidas($con, $idPublicacao) {
    $stmt = $con->prepare(
        "SELECT u.nome, u.username
         FROM curtidas c
         INNER JOIN usuarios u ON u.id_usuario = c.id_usuario
         WHERE c.id_publicacao = ?
         ORDER BY c.criado_em DESC"
    );
    $stmt->execute([$idPublicacao]);
    return $stmt->fetchAll();
}

function removerCurtida($con, $dados) {
    $stmt = $con->prepare(
        "DELETE FROM curtidas WHERE id_usuario = ? AND id_publicacao = ?"
    );
    $stmt->execute([$dados["id_usuario"], $dados["id_publicacao"]]);
}
