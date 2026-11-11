<?php
require_once "../conexao.php";
header("Content-Type: application/json");

session_destroy();
echo json_encode(["mensagem" => "Logout realizado."]);
