<?php

require_once __DIR__ . "/UsuarioModel.php";

function receberDadosUsuario() {
    $dados = json_decode(file_get_contents("php://input"), true);

    if (!is_array($dados)) {
        http_response_code(400);
        echo json_encode(["erro" => "Confira os dados enviados."]);
        return null;
    }

    foreach (["nome", "username", "email", "senha"] as $campo) {
        if (empty(trim($dados[$campo] ?? ""))) {
            http_response_code(400);
            echo json_encode(["erro" => "Preencha todos os campos."]);
            return null;
        }
    }

    if (!filter_var($dados["email"], FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["erro" => "Digite um e-mail válido."]);
        return null;
    }

    return $dados;
}

function createUsuario($con) {
    $dados = receberDadosUsuario();
    if ($dados === null) return;

    if (buscarUsuarioEmail($con, $dados["email"])) {
        http_response_code(409);
        echo json_encode(["erro" => "Esse e-mail ou nome de usuário já está cadastrado."]);
        return;
    }

    $dados["senha"] = password_hash($dados["senha"], PASSWORD_DEFAULT);
    $id = insertUsuario($con, $dados);

    http_response_code(201);
    echo json_encode([
        "mensagem" => "Conta criada! Agora você já pode entrar.",
        "id_usuario" => $id
    ]);
}

function loginUsuario($con) {
    $dados = json_decode(file_get_contents("php://input"), true);

    $login = trim($dados["login"] ?? "");
    $senha = $dados["senha"] ?? "";

    if ($login === "" || $senha === "") {
        http_response_code(400);
        echo json_encode(["erro" => "Informe seu e-mail ou usuário e sua senha."]);
        return;
    }

    $usuario = buscarUsuarioLogin($con, $login);

    if (!$usuario || !password_verify($senha, $usuario["senha"])) {
        http_response_code(401);
        echo json_encode(["erro" => "Usuário ou senha não conferem."]);
        return;
    }

    unset($usuario["senha"]);
    echo json_encode(["mensagem" => "Login realizado!", "usuario" => $usuario]);
}

function readUsuario($con) {
    echo json_encode(listarUsuario($con));
}

function deleteUsuario($con) {
    $id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);
    if (!$id) {
        http_response_code(400);
        echo json_encode(["erro" => "Informe um usuário válido."]);
        return;
    }

    removerUsuario($con, $id);
    echo json_encode(["mensagem" => "Conta removida."]);
}
