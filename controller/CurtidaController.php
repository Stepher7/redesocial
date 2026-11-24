<?php

require_once __DIR__ . "/../models/CurtidaModel.php";

function receberDadosCurtida() {
    $dados = json_decode(file_get_contents("php://input"), true);

    $idUsuario = filter_var($dados["id_usuario"] ?? null, FILTER_VALIDATE_INT);
    $idPublicacao = filter_var($dados["id_publicacao"] ?? null, FILTER_VALIDATE_INT);

    if (!$idUsuario || !$idPublicacao) {
        http_response_code(400);
        echo json_encode(["erro" => "Não foi possível identificar a curtida."]);
        return null;
    }

    return [
        "id_usuario" => $idUsuario,
        "id_publicacao" => $idPublicacao
    ];
}

function createCurtida($con) {
    $dados = receberDadosCurtida();
    if ($dados === null) return;

    inserirCurtida($con, $dados);
    echo json_encode(["mensagem" => "Você curtiu essa publicação."]);
}

function readCurtidas($con) {
    $id = filter_var($_GET["id_publicacao"] ?? null, FILTER_VALIDATE_INT);
    if (!$id) {
        http_response_code(400);
        echo json_encode(["erro" => "Informe uma publicação."]);
        return;
    }

    echo json_encode(listarCurtidas($con, $id));
}

function deleteCurtida($con) {
    $dados = receberDadosCurtida();
    if ($dados === null) return;

    removerCurtida($con, $dados);
    echo json_encode(["mensagem" => "Curtida removida."]);
}
