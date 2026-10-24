<?php

$host = "127.0.0.1";
$porta = 3306;
$usuario = "root";
$senha = "";
$banco = "ferroramaDB";

$conexao = new mysqli($host, $usuario, $senha, $banco, $porta);

if ($conexao->connect_errno) {
    die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
}

$conexao->set_charset("utf8mb4");