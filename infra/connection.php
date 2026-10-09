<?php 

$host = "localhost:3306";
$usuario = "root";
$senha = "";
$banco = "sa_ferrorama_d_s_i_n";

$conexao = new mysqli($host, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    die("Erro na conexão com o banco:" . $conexao->connect_error);
};

$conexao->set_charset("utf8mb4");

?>