<?php
require_once "../conexao.php";
header("Content-Type: application/json");

if (!isset($_SESSION["usuario_id"])) {
  http_response_code(401);
  echo json_encode(["erro" => "Não autorizado."]);
  exit;
}

$dados = json_decode(file_get_contents("php://input"), true);
$id    = (int)($dados["id"] ?? 0);

if (!$id) {
  http_response_code(400);
  echo json_encode(["erro" => "ID inválido."]);
  exit;
}

$stmt = $pdo->prepare(
  "DELETE FROM notas WHERE id = ? AND usuario_id = ?"
);
$stmt->execute([$id, $_SESSION["usuario_id"]]);

echo json_encode(["mensagem" => "Nota excluída."]);
