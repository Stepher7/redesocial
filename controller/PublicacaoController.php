<?php

require_once __DIR__ . "/../models/PublicacaoModel.php";

function createPublicacao($con) {
    $dados = json_decode(file_get_contents("php://input"), true);

    $idUsuario = filter_var($dados["id_usuario"] ?? null, FILTER_VALIDATE_INT);
    $conteudo = trim($dados["conteudo"] ?? "");
    $imagem = trim($dados["imagem_url"] ?? "");

    if (!$idUsuario || $conteudo === "") {
        http_response_code(400);
        echo json_encode(["erro" => "Escreva algo para publicar."]);
        return;
    }

    if ($imagem !== "" && !filter_var($imagem, FILTER_VALIDATE_URL)) {
        http_response_code(400);
        echo json_encode(["erro" => "O link da imagem não é válido."]);
        return;
    }

    $id = inserirPublicacao($con, [
        "id_usuario" => $idUsuario,
        "conteudo" => $conteudo,
        "imagem_url" => $imagem !== "" ? $imagem : null
    ]);

    http_response_code(201);
    echo json_encode(["mensagem" => "Publicação feita!", "id_publicacao" => $id]);
}

function readPublicacoes($con) {
    $idUsuario = filter_var($_GET["id_usuario"] ?? 0, FILTER_VALIDATE_INT);
    echo json_encode(listarPublicacoes($con, $idUsuario ?: 0));
}

function readPublicacao($con) {
    $id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);
    if (!$id) {
        http_response_code(400);
        echo json_encode(["erro" => "Informe uma publicação válida."]);
        return;
    }

    $publicacao = buscarPublicacao($con, $id);
    if (!$publicacao) {
        http_response_code(404);
        echo json_encode(["erro" => "Essa publicação não foi encontrada."]);
        return;
    }

    echo json_encode($publicacao);
}

function deletePublicacao($con) {
    $id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);
    if (!$id) {
        http_response_code(400);
        echo json_encode(["erro" => "Informe uma publicação válida."]);
        return;
    }

    removerPublicacao($con, $id);
    echo json_encode(["mensagem" => "Publicação removida."]);
}
