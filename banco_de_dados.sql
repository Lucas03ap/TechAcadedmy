CREATE DATABASE bd_techy;

USE bd_techy;

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(100),
    tipo_usuario ENUM('aluno', 'professor', 'admin'),
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cursos (
    id_curso INT AUTO_INCREMENT PRIMARY KEY,
    nome_curso VARCHAR(100),
    descricao TEXT,
    id_professor INT,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_professor) REFERENCES usuarios(id_usuario)
);

CREATE TABLE aulas (
    id_aula INT AUTO_INCREMENT PRIMARY KEY,
    titulo_aula VARCHAR(100),
    conteudo TEXT,
    id_curso INT,
    ordem INT,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso)
);

CREATE TABLE progresso (
    id_progresso INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_aula INT,
    status ENUM('incompleto', 'completo'),
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_aula) REFERENCES aulas(id_aula)
);
