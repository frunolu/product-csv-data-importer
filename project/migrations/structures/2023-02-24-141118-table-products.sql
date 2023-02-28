CREATE TABLE `products`
(
    `id`           int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `sku`          varchar(64)    NOT NULL,
    `ean`          char(13)       NOT NULL,
    `name`         varchar(256)   NOT NULL,
    `shortDesc`    varchar(512)   NOT NULL,
    `manufacturer` varchar(64)    NOT NULL,
    `price`        decimal(12, 5) NOT NULL,
    `stock`        int(11) NOT NULL
);
