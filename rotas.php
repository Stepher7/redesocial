<?php

require_once __DIR__ . "/UsuarioController.php";
require_once __DIR__ . "/controller/PublicacaoController.php";
require_once __DIR__ . "/controller/CurtidaController.php";

$rota = $_GET["rota"] ?? "";
$metodo = $_SERVER["REQUEST_METHOD"];

if ($rota === "usuario") {
    switch ($metodo) {
        case "POST":
            createUsuario($con);
            break;
        case "GET":
            readUsuario($con);
            break;
        case "PUT":
            updateUsuario($con);
            break;
        case "DELETE":
            deleteUsuario($con);
            break;
        default:
            http_response_code(405);
            echo json_encode(["erro" => "Método não permitido para usuário!"]);
    }
} elseif ($rota === "publicacao") {
    switch ($metodo) {
        case "POST":
            createPublicacao($con);
            break;
        case "GET":
            if (isset($_GET["id"])) {
                readPublicacao($con);
            } else {
                readPublicacoes($con);
            }
            break;
        case "PUT":
            updatePublicacao($con);
            break;
        case "DELETE":
            deletePublicacao($con);
            break;
        default:
            http_response_code(405);
            echo json_encode(["erro" => "Método não permitido para publicação!"]);
    }
} elseif ($rota === "curtida") {
    switch ($metodo) {
        case "POST":
            createCurtida($con);
            break;
        case "GET":
            readCurtidas($con);
            break;
        case "DELETE":
            deleteCurtida($con);
            break;
        default:
            http_response_code(405);
            echo json_encode(["erro" => "Método não permitido para curtida!"]);
    }
} else {
    http_response_code(404);
    echo json_encode(["erro" => "Rota não encontrada!"]);
}
