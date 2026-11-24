<?php

function insertUsuario($con, $dados) {
    $stmt = $con->prepare(
        "INSERT INTO usuarios (nome, username, email, senha) VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([
        $dados["nome"],
        $dados["username"],
        $dados["email"],
        $dados["senha"]
    ]);

    return $con->lastInsertId();
}

function listarUsuario($con) {
    $stmt = $con->query(
        "SELECT id_usuario, nome, username, email FROM usuarios ORDER BY nome"
    );
    return $stmt->fetchAll();
}

function buscarUsuarioLogin($con, $login) {
    $stmt = $con->prepare(
        "SELECT id_usuario, nome, username, email, senha
         FROM usuarios
         WHERE email = ? OR username = ?
         LIMIT 1"
    );
    $stmt->execute([$login, $login]);
    return $stmt->fetch();
}

function buscarUsuarioEmail($con, $email) {
    $stmt = $con->prepare("SELECT id_usuario FROM usuarios WHERE email = ? OR username = ?");
    $stmt->execute([$email, $email]);
    return $stmt->fetch();
}

function removerUsuario($con, $id) {
    $stmt = $con->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$id]);
}
