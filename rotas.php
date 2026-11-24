<?php

require_once __DIR__ . "/UsuarioController.php";
require_once __DIR__ . "/controller/PublicacaoController.php";
require_once __DIR__ . "/controller/CurtidaController.php";

$rota = $_GET["rota"] ?? "";
$metodo = $_SERVER["REQUEST_METHOD"];

if ($rota === "usuario") {
    if ($metodo === "POST") {
        createUsuario($con);
    } elseif ($metodo === "GET") {
        readUsuario($con);
    } elseif ($metodo === "DELETE") {
        deleteUsuario($con);
    } else {
        http_response_code(405);
        echo json_encode(["erro" => "Método não permitido."]);
    }
} elseif ($rota === "login" && $metodo === "POST") {
    loginUsuario($con);
} elseif ($rota === "publicacao") {
    if ($metodo === "POST") {
        createPublicacao($con);
    } elseif ($metodo === "GET") {
        if (isset($_GET["id"])) {
            readPublicacao($con);
        } else {
            readPublicacoes($con);
        }
    } elseif ($metodo === "DELETE") {
        deletePublicacao($con);
    } else {
        http_response_code(405);
        echo json_encode(["erro" => "Método não permitido."]);
    }
} elseif ($rota === "curtida") {
    if ($metodo === "POST") {
        createCurtida($con);
    } elseif ($metodo === "GET") {
        readCurtidas($con);
    } elseif ($metodo === "DELETE") {
        deleteCurtida($con);
    } else {
        http_response_code(405);
        echo json_encode(["erro" => "Método não permitido."]);
    }
} else {
    http_response_code(404);
    echo json_encode(["erro" => "Rota não encontrada."]);
}
