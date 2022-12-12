CREATE TABLE `chat_user_table`
(
    `user_id`                INT(11) NOT NULL AUTO_INCREMENT,
    `user_name`              VARCHAR(250) NOT NULL,
    `user_email`             VARCHAR(250) NOT NULL,
    `user_password`          VARCHAR(250) NOT NULL,
    `user_profile`           VARCHAR(250) NOT NULL,
    `user_status`            ENUM('Disable','Enable') NOT NULL,
    `user_created_on`        DATETIME     NOT NULL,
    `user_verification_code` VARCHAR(50)  NOT NULL,
    `user_login_status`      ENUM('Logout','Login') NOT NULL,
    PRIMARY KEY (`user_id`)
) COLLATE='utf8mb4_general_ci'
;
