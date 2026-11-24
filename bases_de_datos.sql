SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `gestor_financiero`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `gestor_financiero`;

DROP TABLE IF EXISTS `transacciones`;
DROP TABLE IF EXISTS `cuentas`;
DROP TABLE IF EXISTS `categorias`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `migrations`;


CREATE TABLE `categorias` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre`      VARCHAR(150) NOT NULL,
  `tipo`        ENUM('ingreso','gasto') NOT NULL,
  `descripcion` VARCHAR(255) NULL DEFAULT NULL,
  `created_at`  TIMESTAMP NULL DEFAULT NULL,
  `updated_at`  TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cuentas` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre`      VARCHAR(150) NOT NULL,
  `descripcion` VARCHAR(255) NULL DEFAULT NULL,
  `saldo`       DECIMAL(18,2) NOT NULL DEFAULT 0.00,
  `moneda`      VARCHAR(10) NOT NULL DEFAULT 'COP',
  `created_at`  TIMESTAMP NULL DEFAULT NULL,
  `updated_at`  TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `transacciones` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cuenta_id`    BIGINT UNSIGNED NOT NULL,
  `categoria_id` BIGINT UNSIGNED NOT NULL,
  `tipo`         ENUM('ingreso','gasto') NOT NULL,
  `monto`        DECIMAL(18,2) NOT NULL,
  `fecha`        DATE NOT NULL,
  `descripcion`  VARCHAR(255) NULL DEFAULT NULL,
  `creado_por`   VARCHAR(150) NULL DEFAULT NULL,
  `created_at`   TIMESTAMP NULL DEFAULT NULL,
  `updated_at`   TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transacciones_cuenta_id_foreign` (`cuenta_id`),
  KEY `transacciones_categoria_id_foreign` (`categoria_id`),
  -- RESTRICT: no deja eliminar una cuenta o categoría que tenga transacciones
  CONSTRAINT `transacciones_cuenta_id_foreign`
    FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `transacciones_categoria_id_foreign`
    FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`)
    ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `users` (
  `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`              VARCHAR(255) NOT NULL,
  `email`             VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password`          VARCHAR(255) NOT NULL,
  `remember_token`    VARCHAR(100) NULL DEFAULT NULL,
  `created_at`        TIMESTAMP NULL DEFAULT NULL,
  `updated_at`        TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email`      VARCHAR(255) NOT NULL,
  `token`      VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id`            VARCHAR(255) NOT NULL,
  `user_id`       BIGINT UNSIGNED NULL DEFAULT NULL,
  `ip_address`    VARCHAR(45) NULL DEFAULT NULL,
  `user_agent`    TEXT NULL,
  `payload`       LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache` (
  `key`        VARCHAR(255) NOT NULL,
  `value`      MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key`        VARCHAR(255) NOT NULL,
  `owner`      VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue`        VARCHAR(255) NOT NULL,
  `payload`      LONGTEXT NOT NULL,
  `attempts`     TINYINT UNSIGNED NOT NULL,
  `reserved_at`  INT UNSIGNED NULL DEFAULT NULL,
  `available_at` INT UNSIGNED NOT NULL,
  `created_at`   INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id`             VARCHAR(255) NOT NULL,
  `name`           VARCHAR(255) NOT NULL,
  `total_jobs`     INT NOT NULL,
  `pending_jobs`   INT NOT NULL,
  `failed_jobs`    INT NOT NULL,
  `failed_job_ids` LONGTEXT NOT NULL,
  `options`        MEDIUMTEXT NULL,
  `cancelled_at`   INT NULL DEFAULT NULL,
  `created_at`     INT NOT NULL,
  `finished_at`    INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid`       VARCHAR(255) NOT NULL,
  `connection` TEXT NOT NULL,
  `queue`      TEXT NOT NULL,
  `payload`    LONGTEXT NOT NULL,
  `exception`  LONGTEXT NOT NULL,
  `failed_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `migrations` (
  `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR(255) NOT NULL,
  `batch`     INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'admin@gestor.com', NULL, '$2y$12$yIjBKARGcEAa4T7zSMzwuOYuqaYZk8obMyjQdMj5QifkgybmcV9VC', NULL, '2026-11-23 08:00:00', '2026-11-23 08:00:00');

INSERT INTO `categorias` (`id`, `nombre`, `tipo`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Salario',            'ingreso', 'Pago mensual de nómina',                        '2026-11-23 08:00:00', '2026-11-23 08:00:00'),
(2, 'Ventas',             'ingreso', 'Ingresos por ventas o trabajos independientes', '2026-11-23 08:00:00', '2026-11-23 08:00:00'),
(3, 'Alimentación',       'gasto',   'Mercado y comidas',                             '2026-11-23 08:00:00', '2026-11-23 08:00:00'),
(4, 'Transporte',         'gasto',   'Pasajes, gasolina y taxis',                     '2026-11-23 08:00:00', '2026-11-23 08:00:00'),
(5, 'Servicios públicos', 'gasto',   'Agua, luz, gas e internet',                     '2026-11-23 08:00:00', '2026-11-23 08:00:00');


INSERT INTO `cuentas` (`id`, `nombre`, `descripcion`, `saldo`, `moneda`, `created_at`, `updated_at`) VALUES
(1, 'Efectivo',          'Dinero en efectivo',          285000.00,  'COP', '2026-11-23 08:00:00', '2026-11-23 08:00:00'),
(2, 'Cuenta de ahorros', 'Cuenta de ahorros principal', 2820000.00, 'COP', '2026-11-23 08:00:00', '2026-11-23 08:00:00'),
(3, 'Cuenta corriente',  'Cuenta para pagos',           200000.00,  'COP', '2026-11-23 08:00:00', '2026-11-23 08:00:00');

INSERT INTO `transacciones` (`id`, `cuenta_id`, `categoria_id`, `tipo`, `monto`, `fecha`, `descripcion`, `creado_por`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'ingreso', 2500000.00, '2026-11-01', 'Salario de noviembre',          'Administrador', '2026-11-23 08:00:00', '2026-11-23 08:00:00'),
(2, 1, 2, 'ingreso',  350000.00, '2026-11-05', 'Venta de artículos usados',     'Administrador', '2026-11-23 08:00:00', '2026-11-23 08:00:00'),
(3, 1, 3, 'gasto',    120000.00, '2026-11-08', 'Mercado de la semana',          'Administrador', '2026-11-23 08:00:00', '2026-11-23 08:00:00'),
(4, 1, 4, 'gasto',     45000.00, '2026-11-10', 'Recarga tarjeta de transporte', 'Administrador', '2026-11-23 08:00:00', '2026-11-23 08:00:00'),
(5, 2, 5, 'gasto',    180000.00, '2026-11-15', 'Pago de luz y agua',            'Administrador', '2026-11-23 08:00:00', '2026-11-23 08:00:00');

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_11_23_000001_create_categorias_table', 1),
(5, '2026_11_23_000002_create_cuentas_table', 1),
(6, '2026_11_23_000003_create_transacciones_table', 1);

SET FOREIGN_KEY_CHECKS = 1;