<?php

require_once __DIR__ . "/../models/CurtidaModel.php";

function receberDadosCurtida() {
    $dados = json_decode(file_get_contents("php://input"), true);

    if (!is_array($dados)) {
        http_response_code(400);
        echo json_encode(["erro" => "Envie um objeto JSON com os dados da curtida!"]);
        return null;
    }

    foreach (["id_usuario", "id_publicacao"] as $campo) {
        if (!isset($dados[$campo]) || filter_var($dados[$campo], FILTER_VALIDATE_INT) === false || (int)$dados[$campo] < 1) {
            http_response_code(400);
            echo json_encode(["erro" => "Informe um '$campo' válido!"]);
            return null;
        }
    }

    return $dados;
}

function createCurtida($con) {
    $dados = receberDadosCurtida();
    if ($dados === null) return;

    inserirCurtida($con, $dados);
    http_response_code(201);
    echo json_encode(["mensagem" => "Curtida registrada com sucesso!"]);
}

function readCurtidas($con) {
    echo json_encode(listarCurtidas($con));
}

function deleteCurtida($con) {
    $dados = receberDadosCurtida();
    if ($dados === null) return;

    removerCurtida($con, $dados);
    echo json_encode(["mensagem" => "Curtida removida com sucesso!"]);
}
