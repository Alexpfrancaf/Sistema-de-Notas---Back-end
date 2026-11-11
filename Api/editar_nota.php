<?php
require_once "../conexao.php";
header("Content-Type: application/json");

if (!isset($_SESSION["usuario_id"])) {
  http_response_code(401);
  echo json_encode(["erro" => "Não autorizado."]);
  exit;
}

$dados    = json_decode(file_get_contents("php://input"), true);
$id       = (int)($dados["id"]       ?? 0);
$titulo   = trim($dados["titulo"]    ?? "");
$conteudo = trim($dados["conteudo"]  ?? "");

if (!$id || !$titulo || !$conteudo) {
  http_response_code(400);
  echo json_encode(["erro" => "Dados incompletos."]);
  exit;
}

// Garante que a nota pertence ao usuário logado
$stmt = $pdo->prepare(
  "UPDATE notas SET titulo = ?, conteudo = ? WHERE id = ? AND usuario_id = ?"
);
$stmt->execute([$titulo, $conteudo, $id, $_SESSION["usuario_id"]]);

echo json_encode(["mensagem" => "Nota atualizada."]);
