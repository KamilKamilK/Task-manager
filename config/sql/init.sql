CREATE
DATABASE IF NOT EXISTS main;

USE
main;

CREATE TABLE `user`
(
    id          INT AUTO_INCREMENT NOT NULL,
    email       VARCHAR(180) NOT NULL,
    roles       JSON         NOT NULL,
    password    VARCHAR(255) NOT NULL,
    first_name  VARCHAR(255) DEFAULT NULL,
    last_name   VARCHAR(255) DEFAULT NULL,
    is_verified TINYINT(1) NOT NULL,
    created_at  DATETIME     NOT NULL,
    updated_at  DATETIME     NOT NULL,
    UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

CREATE TABLE `task`
(
    id         INT AUTO_INCREMENT NOT NULL,
    user_id    INT DEFAULT NULL,
    title      VARCHAR(100) NOT NULL,
    details    VARCHAR(255) NOT NULL,
    deadline   DATE         NOT NULL,
    completed  TINYINT(1) NOT NULL,
    created_at DATETIME     NOT NULL,
    updated_at DATETIME     NOT NULL,
    INDEX      IDX_527EDB25A76ED395 (user_id),
    PRIMARY KEY (id),
    CONSTRAINT FK_527EDB25A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;


CREATE TABLE tool
(
    id       INT AUTO_INCREMENT NOT NULL,
    task_id  INT          DEFAULT NULL,
    name     VARCHAR(255) NOT NULL,
    quantity VARCHAR(255) DEFAULT NULL,
    INDEX    IDX_EAFADE778DB60186 (task_id),
    PRIMARY KEY (id),
    CONSTRAINT FK_EAFADE778DB60186 FOREIGN KEY (task_id) REFERENCES task (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
