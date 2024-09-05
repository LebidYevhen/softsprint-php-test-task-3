CREATE TABLE IF NOT EXISTS user_roles
(
    id   INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS users
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name  VARCHAR(255) NOT NULL,
    role_id    INT,
    status     BOOLEAN      NOT NULL DEFAULT FALSE,
    created_at datetime     NOT NULL DEFAULT NOW(),
    updated_at datetime     NOT NULL DEFAULT NOW() ON UPDATE NOW(),
    FOREIGN KEY (role_id) REFERENCES user_roles (id) ON DELETE SET NULL
);

INSERT INTO user_roles (name)
VALUES ('Admin'),
       ('User');