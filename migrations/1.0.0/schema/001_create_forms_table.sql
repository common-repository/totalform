CREATE TABLE IF NOT EXISTS `{{prefix}}totalform_forms`
(
    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uid`          TEXT         NOT NULL,
    `title`        TEXT         NOT NULL,
    `slug`         TEXT         NOT NULL,
    `sections`     TEXT         NULL DEFAULT NULL,
    `settings`     TEXT         NULL DEFAULT NULL,
    `meta`         TEXT         NULL DEFAULT NULL,
    `published_at` DATETIME     NULL,
    `updated_at`   DATETIME     NOT NULL,
    `deleted_at`   DATETIME     NULL,
    `user_id`      INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `uid` (`uid`(255)),
    INDEX `slug` (`slug`(255)),
    INDEX (`updated_at`),
    INDEX (`user_id`)
) ENGINE = InnoDB;
