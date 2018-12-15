-- Crear esquema de la base de datos
CREATE SCHEMA IF NOT EXISTS p7_news_app CHARACTER SET utf8mb4;

-- Seleccionar base de datos para su uso
USE p7_news_app;

-- Crear tablas de la base de datos
CREATE TABLE IF NOT EXISTS table_category (
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(25) NOT NULL,
	parent INT,
	CONSTRAINT pk_category PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS table_role (
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(25) NOT NULL,
	description VARCHAR(100) NOT NULL,
	CONSTRAINT pk_roles PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS table_permission (
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(25) NOT NULL,
	description VARCHAR(100) NOT NULL,
	CONSTRAINT pk_permission PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS table_role_permission (
	role_id INT NOT NULL,
	permission_id INT NOT NULL,
	CONSTRAINT pk_roles PRIMARY KEY (role_id,permission_id),
	CONSTRAINT fk_rp_role FOREIGN KEY (role_id) REFERENCES table_role(id),
	CONSTRAINT fk_rp_permission FOREIGN KEY (permission_id) REFERENCES table_permission(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS table_user (
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(50) NOT NULL,
	email VARCHAR(100) NOT NULL,
	password VARCHAR(255) NOT NULL,
	date_registered DATE NOT NULL,
	role_id INT NOT NULL,
	CONSTRAINT pk_user PRIMARY KEY (id),
	CONSTRAINT fk_user_roles FOREIGN KEY (role_id) REFERENCES table_role(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS table_news (
	id INT NOT NULL AUTO_INCREMENT,
	title VARCHAR(25) NOT NULL,
	subtitle VARCHAR(100) NOT NULL,
	date_created DATE NOT NULL,
	date_modified DATE,
	date_published DATE,
	content TEXT NOT NULL,
	img VARCHAR(100),
	autor INT NOT NULL,
	editor INT,
	category_id INT NOT NULL,
	CONSTRAINT pk_news PRIMARY KEY (id),
	CONSTRAINT fk_news_category FOREIGN KEY (category_id) REFERENCES table_category(id),
	CONSTRAINT fk_news_autor FOREIGN KEY (autor) REFERENCES table_user(id),
	CONSTRAINT fk_news_editor FOREIGN KEY (editor) REFERENCES table_user(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS table_keyword (
	id INT NOT NULL AUTO_INCREMENT,
	news_id INT NOT NULL,
	name VARCHAR(100) NOT NULL,
	CONSTRAINT pk_keyword PRIMARY KEY (id, news_id),
	CONSTRAINT fk_keyword_news FOREIGN KEY (news_id) REFERENCES table_news(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO table_role ( name, description) VALUES
('admin', 'Administrador'),
('editor', 'Editor'),
('autor', 'Autor');

INSERT INTO table_category (name, parent) VALUES
('Internacional', NULL),
('Local', NULL),
('Deportes', NULL),
('Tiempo', NULL);

INSERT INTO table_user(name, email, password, date_registered, role_id)
VALUES ('admin','admin@yaseal.com','$2y$10$qEJUheFyhATltcpYzQv.1.7hbb4rmX8uirRUDYvel4V/CV3GbJx7S','2018-11-15','1');
