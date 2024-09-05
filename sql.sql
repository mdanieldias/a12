-- Exemplo para criar um banco de dados no MySQL
CREATE IF NOT EXISTS DATABASE a12;

-- Criar tabela cities
CREATE TABLE IF NOT EXISTS cities (
   id bigint unsigned NOT NULL AUTO_INCREMENT,
   nome varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
   uf char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
   created_at timestamp NULL DEFAULT NULL,
   updated_at timestamp NULL DEFAULT NULL,
   PRIMARY KEY (id),
   UNIQUE KEY cities_nome_uf_unique (nome,uf)
 ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Criar tabela customers
CREATE TABLE IF NOT EXISTS customers (
   id bigint unsigned NOT NULL AUTO_INCREMENT,
   name varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
   email varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
   cpf varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
   cellphone varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
   city_id bigint unsigned NOT NULL,
   created_at timestamp NULL DEFAULT NULL,
   updated_at timestamp NULL DEFAULT NULL,
   PRIMARY KEY (id),
   UNIQUE KEY customers_email_unique (email),
   UNIQUE KEY customers_cpf_unique (cpf),
   KEY customers_city_id_foreign (city_id),
   CONSTRAINT customers_city_id_foreign FOREIGN KEY (city_id) REFERENCES cities (id) ON DELETE CASCADE
 ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Exemplo para popular a tabela cities
INSERT INTO cities (nome, uf) VALUES
('São Paulo', 'SP'),
('Rio de Janeiro', 'RJ'),
('Brasília', 'DF');

-- Exemplo para atualizar a tabela customers
UPDATE customers SET name = 'Leticia Giovana Santos Dias', city_id = '2' WHERE (id = '6');

-- Exemplo para excluir a tabela customers
DELETE FROM customers WHERE (id = '6');

-- Exemplo de consulta com join
SELECT 
    cu.*, ci.nome as cidade
FROM
    customers cu
        INNER JOIN
    cities ci ON cu.city_id = ci.id
WHERE
    ci.nome LIKE '%cunha%';