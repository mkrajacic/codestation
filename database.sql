CREATE DATABASE IF NOT EXISTS coding;

CREATE TABLE language (
	id INT AUTO_INCREMENT NOT NULL,
	name VARCHAR(25) NOT NULL,
	image VARCHAR(255),
	description TEXT NOT NULL,
	compiler_mode VARCHAR(15) NOT NULL,
	language_version TINYINT(1) NOT NULL,
	editor_mode VARCHAR(15) NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE lesson (
	id INT AUTO_INCREMENT NOT NULL,
	name VARCHAR(100) NOT NULL,
	description TEXT NOT NULL,
	language_id INT NOT NULL,
	precondition INT(11) NOT NULL DEFAULT 0,
	PRIMARY KEY (id), 
	FOREIGN KEY (language_id) REFERENCES language(id) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE question_type (
	id INT AUTO_INCREMENT NOT NULL,
	type VARCHAR(20) NOT NULL,
	PRIMARY KEY (id)
);
 
CREATE TABLE question (
	id INT AUTO_INCREMENT NOT NULL,
	question TEXT NOT NULL,
	lesson_id INT NOT NULL,
	question_type INT NOT NULL,
	PRIMARY KEY (id), 
	FOREIGN KEY (lesson_id) REFERENCES lesson(id) ON UPDATE CASCADE ON DELETE RESTRICT,
	FOREIGN KEY (question_type) REFERENCES question_type(id) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE answer (
	id INT AUTO_INCREMENT NOT NULL,
	answer TEXT NOT NULL,
	question_id INT NOT NULL,
	correct TINYINT(1) NOT NULL DEFAULT 0,
	PRIMARY KEY (id), 
	FOREIGN KEY (question_id) REFERENCES question(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE coding_answer (
	id INT AUTO_INCREMENT NOT NULL,
	code TEXT NOT NULL,
	display TEXT NOT NULL,
	question_id INT NOT NULL,
	PRIMARY KEY (id), 
	FOREIGN KEY (question_id) REFERENCES question(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE user_role (
  code VARCHAR(3) NOT NULL,
  name VARCHAR(15) NOT NULL,
  PRIMARY KEY (code)
);

CREATE TABLE user_profile (
	id INT AUTO_INCREMENT NOT NULL,
	username VARCHAR(15) NOT NULL,
	password VARCHAR(60) NOT NULL,
	image VARCHAR(255),
	role_code VARCHAR(3) NOT NULL DEFAULT 'USR',
	PRIMARY KEY(id),
	FOREIGN KEY (role_code) REFERENCES user_role(code) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE user_progress_language (
	user_id INT NOT NULL,
	language_id INT NOT NULL,
	FOREIGN KEY (user_id) REFERENCES user_profile(id) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (language_id) REFERENCES language(id) ON UPDATE CASCADE ON DELETE CASCADE,
	UNIQUE KEY user_lang (user_id,language_id)
);

CREATE TABLE user_progress_lesson (
	user_id INT NOT NULL,
	lesson_id INT NOT NULL,
	FOREIGN KEY (user_id) REFERENCES user_profile(id) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (lesson_id) REFERENCES lesson(id) ON UPDATE CASCADE ON DELETE CASCADE,
	UNIQUE KEY user_less (user_id,lesson_id)
);

CREATE TABLE user_progress_question (
	user_id INT NOT NULL,
	question_id INT NOT NULL,
	FOREIGN KEY (user_id) REFERENCES user_profile(id) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (question_id) REFERENCES question(id) ON UPDATE CASCADE ON DELETE CASCADE,
	UNIQUE KEY user_quest (user_id,question_id)
);
