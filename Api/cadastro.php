<?php
header("Content-Type: application/json");
require "conexao.php";

$dados = json_decode(file_get_contents("php://input"), true);

$nome  = trim($dados["nome"] ?? "");
$email = trim($dados["email"] ?? "");
$senha = trim($dados["senha"] ?? "");

if (!$nome || !$email || !$senha) {
  echo json_encode(["erro" => "Preencha todos os campos."]);
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(["erro" => "E-mail inválido."]);
  exit;
}

if (strlen($senha) < 6) {
  echo json_encode(["erro" => "Senha deve ter pelo menos 6 caracteres."]);
  exit; 
}

$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->fetch()) {
  echo json_encode(["erro" => "E-mail já cadastrado."]);
  exit;
}

$hash = password_hash($senha, PASSWORD_BCRYPT);

$stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
$stmt->execute([$nome, $email, $hash]);

echo json_encode(["sucesso" => "Cadastro realizado."]);
