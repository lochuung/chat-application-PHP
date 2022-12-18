CREATE TABLE `chat_message`
(
    `chat_message_id` INT(11) NOT NULL AUTO_INCREMENT,
    `to_user_id`      INT(11) NOT NULL,
    `from_user_id`    INT(11) NOT NULL,
    `chat_message`    VARCHAR(1000) NOT NULL COLLATE 'utf8mb4_general_ci',
    `timestamp`       INT(11) NOT NULL,
    `status`          ENUM('Y','N') NOT NULL COLLATE 'utf8mb4_general_ci',
    PRIMARY KEY (`chat_message_id`) USING BTREE
) COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;