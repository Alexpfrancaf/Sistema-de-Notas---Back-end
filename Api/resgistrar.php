<?php
require_once "../conexao.php";
header("Content-Type: application/json");

$dados = json_decode(file_get_contents("php://input"), true);

$nome  = trim($dados["nome"]  ?? "");
$email = trim($dados["email"] ?? "");
$senha =      $dados["senha"] ?? "";

if (!$nome || !$email || !$senha) {
  http_response_code(400);
  echo json_encode(["erro" => "Preencha todos os campos."]);
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  echo json_encode(["erro" => "E-mail inválido."]);
  exit;
}

// Verifica se e-mail já existe
$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->fetch()) {
  http_response_code(409);
  echo json_encode(["erro" => "E-mail já cadastrado."]);
  exit;
}

$senhaHash = password_hash($senha, PASSWORD_BCRYPT);

$stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
$stmt->execute([$nome, $email, $senhaHash]);

echo json_encode(["mensagem" => "Usuário criado com sucesso."]);
