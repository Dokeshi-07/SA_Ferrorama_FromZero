CREATE DATABASE IF NOT EXISTS ferroramaDB;
USE ferroramaDB;

CREATE TABLE IF NOT EXISTS adm(
id INT AUTO_INCREMENT PRIMARY KEY,
adm_nome VARCHAR(200) NOT NULL,
adm_email VARCHAR(75) NOT NULL,
adm_password VARCHAR(200) NOT NULL
);

CREATE TABLE IF NOT EXISTS user(
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_nome VARCHAR(200) NOT NULL,
  user_email VARCHAR(75) NOT NULL,
  user_password VARCHAR(200) NOT NULL
);

CREATE TABLE IF NOT EXISTS trem(
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_adm INT NOT NULL,
  nome_trem VARCHAR(200) NOT NULL,
  descricao TEXT NOT NULL,
  tipo VARCHAR(200) NOT NULL,
  prioridade ENUM('baixa', 'media', 'alta') NOT NULL,
  data_cadastro DATE NOT NULL,
  FOREIGN KEY (id_adm) REFERENCES adm(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS rotas(
  id int AUTO_INCREMENT PRIMARY KEY,
  nome_rota VARCHAR(50),
  ponto_partida VARCHAR(200),
  destino VARCHAR(200),
  horario_saida VARCHAR(50),
  horario_chegada VARCHAR(50),
  nome_trem VARCHAR(50),
  estado VARCHAR(50),
  FOREIGN KEY (nome_trem) REFERENCES trem(nome_trem)
);