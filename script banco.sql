CREATE DATABASE IF NOT EXISTS imobiliaria;
CREATE USER IF NOT EXISTS 'imo_admin'@'localhost' IDENTIFIED BY '12345';
GRANT ALL ON imobiliaria.* TO 'imo_admin'@'localhost';
FLUSH PRIVILEGES;
USE imobiliaria;

CREATE TABLE clientes(
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
nome varchar(255) NOT NULL,
email VARCHAR(255) NOT NULL UNIQUE, 
telefone VARCHAR(15) NOT NULL UNIQUE
)ENGINE=INNODB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE proprietarios(
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
nome varchar(255) NOT NULL,
email VARCHAR(255) NOT NULL UNIQUE,
telefone VARCHAR(25) NOT NULL UNIQUE,
dia_repasse CHAR(2)
) ENGINE=INNODB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE imoveis(
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
endereco VARCHAR(255) NOT NULL,
proprietario_id INT(6) UNSIGNED,
CONSTRAINT FK_ProprietarioImovel FOREIGN KEY (proprietario_id)
REFERENCES Proprietarios(id) ON DELETE CASCADE
)ENGINE=INNODB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE contratos(
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
imovel_id INT(6) UNSIGNED,
proprietario_id INT(6) UNSIGNED,
cliente_id INT(6) UNSIGNED,
data_inicio DATE NOT NULL,
data_fim DATE NOT NULL,
taxa_admin DECIMAL(7,2) NOT NULL,
valor_aluguel DECIMAL(7,2) NOT NULL,
valor_condominio DECIMAL(7,2) NOT NULL,
valor_iptu DECIMAL(7,2) NOT NULL,
FOREIGN KEY (imovel_id) REFERENCES imoveis(id) ON DELETE CASCADE,
FOREIGN KEY (proprietario_id) REFERENCES proprietarios(id) ON DELETE CASCADE,
FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
)ENGINE=INNODB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE mensalidades(
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
contrato_id INT(6) UNSIGNED,
valor DECIMAL(7, 2) NOT NULL,
data_vencimento DATE NOT NULL,
`status` VARCHAR(10) NOT NULL,
FOREIGN KEY (contrato_id) REFERENCES contratos(id) ON DELETE CASCADE
)ENGINE=INNODB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE repasses(
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
mensalidade_id INT(6) UNSIGNED,
valor DECIMAL(7, 2) NOT NULL,
data_vencimento DATE NOT NULL,
`status` VARCHAR(10) NOT NULL,
FOREIGN KEY (mensalidade_id) REFERENCES mensalidades(id) ON DELETE CASCADE
)ENGINE = INNODB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO clientes(email, telefone) VALUES ('daniel_2bueno@hotmail.com', '21973816757');
INSERT INTO clientes(email, telefone) VALUES ('beltrano@hotmail.com', '987654321123');





DROP DATABASE imobiliaria;