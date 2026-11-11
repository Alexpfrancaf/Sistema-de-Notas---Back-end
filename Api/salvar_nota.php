<?php
require_once "../conexao.php";
header("Content-Type: application/json");

if (!isset($_SESSION["usuario_id"])) {
  http_response_code(401);
  echo json_encode(["erro" => "Não autorizado."]);
  exit;
}

$dados    = json_decode(file_get_contents("php://input"), true);
$titulo   = trim($dados["titulo"]   ?? "");
$conteudo = trim($dados["conteudo"] ?? "");

if (!$titulo || !$conteudo) {
  http_response_code(400);
  echo json_encode(["erro" => "Título e conteúdo são obrigatórios."]);
  exit;
}

$stmt = $pdo->prepare(
  "INSERT INTO notas (usuario_id, titulo, conteudo) VALUES (?, ?, ?)"
);
$stmt->execute([$_SESSION["usuario_id"], $titulo, $conteudo]);

echo json_encode(["mensagem" => "Nota salva com sucesso."]);
