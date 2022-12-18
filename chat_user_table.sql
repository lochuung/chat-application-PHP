CREATE TABLE `chat_user_table`
(
    `user_id`                INT(11)                   NOT NULL AUTO_INCREMENT,
    `user_name`              VARCHAR(250)              NOT NULL COLLATE 'utf8mb4_general_ci',
    `user_email`             VARCHAR(250)              NOT NULL COLLATE 'utf8mb4_general_ci',
    `user_password`          VARCHAR(250)              NOT NULL COLLATE 'utf8mb4_general_ci',
    `user_profile`           VARCHAR(250)              NOT NULL COLLATE 'utf8mb4_general_ci',
    `user_status`            ENUM ('Disable','Enable') NOT NULL COLLATE 'utf8mb4_general_ci',
    `user_created_on`        INT(11)                   NOT NULL,
    `user_verification_code` VARCHAR(50)               NOT NULL COLLATE 'utf8mb4_general_ci',
    `user_login_status`      ENUM ('Logout','Login')   NOT NULL COLLATE 'utf8mb4_general_ci',
    PRIMARY KEY (`user_id`) USING BTREE
)
    COLLATE = 'utf8mb4_general_ci'
    ENGINE = InnoDB
;