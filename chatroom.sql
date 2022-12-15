CREATE TABLE `chatroom` (
                            `id` INT(250) NOT NULL AUTO_INCREMENT,
                            `user_id` INT(250) NOT NULL,
                            `message` VARCHAR(1000) NOT NULL,
                            `created_on` DATETIME NOT NULL,
                            PRIMARY KEY (`id`)
)
    COLLATE='utf8mb4_general_ci'
;