<?php

function insertUsuario($con, $dados){ 
    $stmt = $con->prepare("INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)"
    );
    $stmt -> execute([$dados['nome'], $dados['email'], $dados['senha']]);
    return $con->lastInsertId();

}
//READ
function listarUsuario($con){
    $stmt = $con->query("SELECT * FROM usuario");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
//UPDATE
function UpdateUsuario($con, $dados, $id){
    $stmt = $con->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
    $stmt->execute([$dados["senha"], 
    $id]);
}
//DELETE
function removerUsuario($con, $id){
    $stmt = $con->prepare("DELETE FROM usuario WHERE id_usuario = ?");
    $stmt->execute([$id]);
}