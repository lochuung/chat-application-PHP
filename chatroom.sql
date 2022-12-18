CREATE TABLE `chatroom`
(
    `id`         INT(250)      NOT NULL AUTO_INCREMENT,
    `user_id`    INT(250)      NOT NULL,
    `message`    VARCHAR(1000) NOT NULL COLLATE 'utf8mb4_general_ci',
    `created_on` INT(11)       NOT NULL,
    PRIMARY KEY (`id`) USING BTREE
)
    COLLATE = 'utf8mb4_general_ci'
    ENGINE = InnoDB
;