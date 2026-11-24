<?php

require_once __DIR__ . "/../models/PublicacaoModel.php";

function receberDadosPublicacao() {
    $dados = json_decode(file_get_contents("php://input"), true);

    if (!is_array($dados)) {
        http_response_code(400);
        echo json_encode(["erro" => "Envie um objeto JSON com os dados da publicação!"]);
        return null;
    }

    foreach (["id_usuario", "conteudo"] as $campo) {
        if (!isset($dados[$campo]) || trim((string)$dados[$campo]) === "") {
            http_response_code(400);
            echo json_encode(["erro" => "O campo '$campo' é obrigatório!"]);
            return null;
        }
    }

    if (!filter_var($dados["id_usuario"], FILTER_VALIDATE_INT) || (int)$dados["id_usuario"] < 1) {
        http_response_code(400);
        echo json_encode(["erro" => "Informe um id_usuario válido!"]);
        return null;
    }

    return $dados;
}

function receberIdPublicacao() {
    $id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    if ($id === false || $id === null) {
        http_response_code(400);
        echo json_encode(["erro" => "Informe um ID de publicação válido!"]);
        return null;
    }
    return $id;
}

function createPublicacao($con) {
    $dados = receberDadosPublicacao();
    if ($dados === null) return;

    $id = inserirPublicacao($con, $dados);
    http_response_code(201);
    echo json_encode(["mensagem" => "Publicação criada com sucesso!", "id" => $id]);
}

function readPublicacoes($con) {
    echo json_encode(listarPublicacoes($con));
}

function readPublicacao($con) {
    $id = receberIdPublicacao();
    if ($id === null) return;

    $publicacao = buscarPublicacao($con, $id);
    if (!$publicacao) {
        http_response_code(404);
        echo json_encode(["erro" => "Publicação não encontrada!"]);
        return;
    }
    echo json_encode($publicacao);
}

function updatePublicacao($con) {
    $id = receberIdPublicacao();
    if ($id === null) return;

    $dados = receberDadosPublicacao();
    if ($dados === null) return;

    atualizarPublicacao($con, $id, $dados);
    echo json_encode(["mensagem" => "Publicação atualizada com sucesso!"]);
}

function deletePublicacao($con) {
    $id = receberIdPublicacao();
    if ($id === null) return;

    removerPublicacao($con, $id);
    echo json_encode(["mensagem" => "Publicação removida com sucesso!"]);
}
