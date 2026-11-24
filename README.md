# Conecta

Uma rede social simples feita com HTML, CSS, JavaScript, PHP e MySQL.

## O que tem no projeto

- Cadastro de usuário
- Login com e-mail ou nome de usuário
- Feed com as publicações mais recentes
- Criação de publicação com texto e link de imagem
- Curtir e remover curtida
- Senhas salvas com hash no banco de dados

## Como executar

1. Coloque a pasta do projeto dentro de `www` do Laragon ou `htdocs` do XAMPP.
2. Abra o phpMyAdmin.
3. Importe o arquivo `database.sql`.
4. Confira os dados de conexão no arquivo `config.php`.
5. Inicie o Apache e o MySQL.
6. Acesse `http://localhost/redesocial/`.

## Rotas da API

| Ação | Método | Endereço |
| --- | --- | --- |
| Cadastrar usuário | POST | `api.php?rota=usuario` |
| Fazer login | POST | `api.php?rota=login` |
| Listar publicações | GET | `api.php?rota=publicacao` |
| Criar publicação | POST | `api.php?rota=publicacao` |
| Curtir publicação | POST | `api.php?rota=curtida` |
| Remover curtida | DELETE | `api.php?rota=curtida` |

Os dados das requisições são enviados em JSON.

## Observação

Este projeto é uma atividade de estudo. O login guarda os dados básicos da conta no navegador para identificar as ações no feed. Para uso real, seria necessário implementar sessões ou tokens e validar as permissões no servidor.
