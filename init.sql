CREATE TABLE IF NOT EXISTS contact (
  id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  contact_name varchar(50) NOT NULL,
  address varchar(50) NOT NULL,
  phone_number varchar(25) NULL,
  email varchar(100) NULL,
  birthday date NULL,
  credit_card int(20) NOT NULL,
  User_Password_Hash varchar(50) NULL,
  User_Password_Salt varchar(10) NULL,
  created_date datetime NOT NULL
);

CREATE TABLE IF NOT EXISTS users (
  id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  email varchar(100) NULL,
  password_hash varchar(50) NULL
);

INSERT INTO users (email, password_hash) VALUES ('test@test.com', MD5('test'));
