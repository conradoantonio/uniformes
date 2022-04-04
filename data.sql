/*
SQLyog Ultimate v9.63 
MySQL - 5.5.5-10.4.18-MariaDB : Database - uniformes
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`uniformes` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `uniformes`;

/*Table structure for table `articulos` */

DROP TABLE IF EXISTS `articulos`;

CREATE TABLE `articulos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`nombre`),
  KEY `status_id` (`descripcion`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `articulos` */

insert  into `articulos`(`id`,`nombre`,`descripcion`,`created_at`,`updated_at`,`deleted_at`) values (1,'Camisas','Camisa de guardia','2022-04-01 13:16:02','2022-04-03 22:50:13',NULL),(2,'Pantalón','Pantalón de guardia','2022-04-01 13:16:02','2022-04-01 13:16:02',NULL),(3,'Cachucha','Cachucha de guardia','2022-04-01 13:16:02','2022-04-01 13:16:02',NULL),(4,'Botas','Botas con refuerzo metálico','2022-04-03 22:50:40','2022-04-03 22:50:40',NULL);

/*Table structure for table `empleados` */

DROP TABLE IF EXISTS `empleados`;

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `razon_social_id` int(11) NOT NULL,
  `status_empleado_id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL COMMENT 'Nombre completo',
  `numero_empleado` varchar(100) DEFAULT NULL,
  `ine` varchar(255) DEFAULT NULL,
  `domicilio` varchar(255) DEFAULT NULL COMMENT 'Domicilio completo',
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_baja` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `razon_social_id` (`razon_social_id`),
  KEY `status_cliente_id` (`status_empleado_id`),
  CONSTRAINT `empleados_ibfk_1` FOREIGN KEY (`razon_social_id`) REFERENCES `razones_sociales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `empleados_ibfk_2` FOREIGN KEY (`status_empleado_id`) REFERENCES `status_empleado` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

/*Data for the table `empleados` */

insert  into `empleados`(`id`,`razon_social_id`,`status_empleado_id`,`nombre`,`numero_empleado`,`ine`,`domicilio`,`fecha_ingreso`,`fecha_baja`,`observaciones`,`created_at`,`updated_at`,`deleted_at`) values (1,1,1,'ADOLFO LÓPEZ MATEOS','10004223','3317528811','AVENIDA SIEMPRE VIVA #4320','2022-03-30',NULL,NULL,'2021-08-30 12:33:50','2021-08-30 12:33:50',NULL),(2,1,1,'MANUEL ROSALES','10004329','3318890674/3337701057','FEDERALISMO GUADALAJARA, JALISCO','2022-03-30',NULL,NULL,'2021-08-30 12:33:50','2021-08-30 12:33:50',NULL),(3,1,1,'ALDEBARÁN VILLALOBOS','10001318','3334910477/3323841685','AV. LA CAÑA No. 3065, COL. LA NOGALERA, C.P. 44490, GUADALAJARA, JALISCO','2022-03-30',NULL,NULL,'2021-08-30 12:33:50','2022-04-03 22:09:05',NULL),(4,1,2,'MARCO ANTONIO SOLIS','10004544','4421770192','AV. CECYT No. 100, COL. NUEVO FUERTE, C.P. 47899, OCOTLAN, JALISCO','2022-03-30','2022-04-03','Hola','2021-08-30 12:33:50','2022-04-03 22:49:08',NULL),(5,1,1,'CERO RIESGO SA DE CV','10000421','3338111115','CALLE 8, COL. COLON INDUSTRIAL, C.P. 44940, GUADALAJARA, JALISCO','2022-03-30',NULL,NULL,'2021-08-30 12:33:50','2022-04-03 22:49:18',NULL),(6,1,3,'RICARDO LÓPEZ','10003121','3338129508','AV. ENRIQUE DIAZ DE LEON No. 783. COL. MODERNA, C.P. 44190, GUADALAJARA, JALISCO','2022-03-30',NULL,NULL,'2021-08-30 12:33:50','2022-04-03 22:49:48',NULL);

/*Table structure for table `historial` */

DROP TABLE IF EXISTS `historial`;

CREATE TABLE `historial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_historial_id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `articulo_id` int(11) NOT NULL COMMENT 'Nota de crédito o pago',
  `status_articulo_id` int(11) NOT NULL,
  `talla_id` int(11) NOT NULL COMMENT 'Número de factura',
  `color` varchar(255) DEFAULT NULL,
  `cantidad` int(30) DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tipo_recibo_id` (`articulo_id`),
  KEY `cliente_id` (`empleado_id`),
  KEY `factura_id` (`talla_id`),
  KEY `status_articulo_id` (`status_articulo_id`),
  KEY `tipo_historial_id` (`tipo_historial_id`),
  CONSTRAINT `historial_ibfk_1` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `historial_ibfk_2` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `historial_ibfk_3` FOREIGN KEY (`status_articulo_id`) REFERENCES `status_articulo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `historial_ibfk_4` FOREIGN KEY (`talla_id`) REFERENCES `tallas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `historial_ibfk_5` FOREIGN KEY (`tipo_historial_id`) REFERENCES `tipo_historial` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=828 DEFAULT CHARSET=utf8mb4;

/*Data for the table `historial` */

insert  into `historial`(`id`,`tipo_historial_id`,`empleado_id`,`articulo_id`,`status_articulo_id`,`talla_id`,`color`,`cantidad`,`fecha_entrega`,`notas`,`created_at`,`updated_at`) values (823,1,1,1,1,1,'Blanco',5,'2022-04-03','Lorem ipsum','2022-04-03 23:59:25','2022-04-03 23:59:25'),(824,1,1,1,1,1,'Blanco',5,'2022-04-03','Lorem ipsum','2022-04-03 23:59:25','2022-04-03 23:59:25'),(825,1,1,1,1,1,'Blanco',5,'2022-04-03','Lorem ipsum','2022-04-03 23:59:25','2022-04-03 23:59:25'),(826,1,1,1,1,1,'Blanco',5,'2022-04-03','Lorem ipsum','2022-04-03 23:59:25','2022-04-03 23:59:25'),(827,1,1,1,1,1,'Blanco',5,'2022-04-03','Lorem ipsum','2022-04-03 23:59:25','2022-04-03 23:59:25');

/*Table structure for table `modulos` */

DROP TABLE IF EXISTS `modulos`;

CREATE TABLE `modulos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

/*Data for the table `modulos` */

insert  into `modulos`(`id`,`nombre`,`created_at`,`updated_at`) values (1,'Empleados','2021-09-22 13:09:26',NULL),(2,'Uniformes','2021-09-22 13:09:26',NULL),(3,'Mi perfil','2021-09-22 13:09:26',NULL),(4,'Razones','2021-09-22 13:09:26',NULL),(5,'Usuarios','2021-09-22 13:09:26',NULL);

/*Table structure for table `permiso_user` */

DROP TABLE IF EXISTS `permiso_user`;

CREATE TABLE `permiso_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permiso_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permiso_id` (`permiso_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `permiso_user_ibfk_1` FOREIGN KEY (`permiso_id`) REFERENCES `permisos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permiso_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb4;

/*Data for the table `permiso_user` */

insert  into `permiso_user`(`id`,`permiso_id`,`user_id`,`created_at`,`updated_at`) values (1,1,1,'2021-09-30 16:00:54',NULL),(2,2,1,'2021-09-30 16:00:54',NULL),(3,3,1,'2021-09-30 16:00:54',NULL),(4,4,1,'2021-09-30 16:00:54',NULL),(13,13,1,'2021-09-30 16:00:54',NULL),(14,14,1,'2021-09-30 16:00:54',NULL),(15,15,1,'2021-09-30 16:00:54',NULL),(16,16,1,'2021-09-30 16:00:54',NULL),(17,17,1,'2021-09-30 16:00:54',NULL),(18,18,1,'2021-09-30 16:00:54',NULL);

/*Table structure for table `permisos` */

DROP TABLE IF EXISTS `permisos`;

CREATE TABLE `permisos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modulo_id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `alias` varchar(30) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `modulo_id` (`modulo_id`),
  CONSTRAINT `permisos_ibfk_1` FOREIGN KEY (`modulo_id`) REFERENCES `modulos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;

/*Data for the table `permisos` */

insert  into `permisos`(`id`,`modulo_id`,`nombre`,`alias`,`created_at`,`updated_at`) values (1,1,'Empleados (Ver)','empleados_ver','2021-09-22 03:26:07',NULL),(2,1,'Empleados (Editar)','empleados_editar','2021-09-22 03:26:07',NULL),(3,2,'Artículos (Ver)','articulos_ver','2021-09-22 03:26:07',NULL),(4,2,'Artículos (Editar)','articulos_editar','2021-09-22 03:26:07',NULL),(13,3,'Mi perfil (Ver)','mi_perfil_ver','2021-09-22 03:26:07',NULL),(14,3,'Mi perfil (Editar)','mi_perfil_editar','2021-09-22 03:26:07',NULL),(15,4,'Razones sociales (Ver)','razones_ver','2021-09-22 03:26:07',NULL),(16,4,'Razones sociales (Editar)','razones_editar','2021-09-22 03:26:07',NULL),(17,5,'Usuarios (Ver)','usuarios_ver','2021-09-22 03:26:07',NULL),(18,5,'Usuarios (Editar)','usuarios_editar','2021-09-22 03:26:07',NULL);

/*Table structure for table `razones_sociales` */

DROP TABLE IF EXISTS `razones_sociales`;

CREATE TABLE `razones_sociales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `razones_sociales` */

insert  into `razones_sociales`(`id`,`nombre`,`created_at`,`updated_at`) values (1,'AVANCE','2021-06-03 18:17:10','2021-06-03 18:17:10'),(2,'AZULIM LEON ','2021-06-03 18:17:10','2021-06-03 18:17:10'),(3,'AZULIM GDL','2021-06-03 18:17:10','2021-06-03 18:17:10'),(4,'COLOSAL','2021-06-28 15:37:45','2021-06-28 15:37:45');

/*Table structure for table `razones_user` */

DROP TABLE IF EXISTS `razones_user`;

CREATE TABLE `razones_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `razon_social_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `razon_social_id` (`razon_social_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `razones_user_ibfk_1` FOREIGN KEY (`razon_social_id`) REFERENCES `razones_sociales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `razones_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

/*Data for the table `razones_user` */

insert  into `razones_user`(`id`,`razon_social_id`,`user_id`,`created_at`,`updated_at`) values (1,1,1,NULL,NULL),(2,2,1,NULL,NULL),(3,3,1,NULL,NULL),(4,4,1,NULL,NULL);

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

/*Data for the table `roles` */

insert  into `roles`(`id`,`descripcion`,`created_at`,`updated_at`) values (1,'Administrador','2021-03-08 00:40:22','2021-03-08 00:40:22');

/*Table structure for table `status_articulo` */

DROP TABLE IF EXISTS `status_articulo`;

CREATE TABLE `status_articulo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `clase` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

/*Data for the table `status_articulo` */

insert  into `status_articulo`(`id`,`nombre`,`descripcion`,`clase`,`created_at`,`updated_at`) values (1,'Nueva','Artículo nuevo','success','2021-06-09 00:26:10','2021-06-09 00:26:10'),(2,'Semiusado','Artículo semiusado','warning','2021-06-09 00:26:10','2021-06-09 00:26:10'),(3,'Desgastado','Artículo desgastado','danger','2021-06-09 00:26:10','2021-06-09 00:26:10');

/*Table structure for table `status_empleado` */

DROP TABLE IF EXISTS `status_empleado`;

CREATE TABLE `status_empleado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `clase` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `status_empleado` */

insert  into `status_empleado`(`id`,`nombre`,`url`,`descripcion`,`clase`,`created_at`,`updated_at`) values (1,'activo','activos','Empleado vigente','success','2021-12-23 11:06:08','2021-12-23 11:06:08'),(2,'inactivo','inactivos','Empleado dado de baja','info','2021-12-23 11:06:08','2021-12-23 11:06:08'),(3,'pendiente','pendientes','Empleado pendiente de validar','warning','2021-12-23 11:06:08','2021-12-23 11:06:08');

/*Table structure for table `tallas` */

DROP TABLE IF EXISTS `tallas`;

CREATE TABLE `tallas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `tallas` */

insert  into `tallas`(`id`,`nombre`,`created_at`,`updated_at`) values (1,'Teléfono','2022-03-31 00:08:28','2022-03-31 00:08:28'),(2,'Whatsapp','2022-03-31 00:08:28','2022-03-31 00:08:28'),(3,'Correo','2022-03-31 00:08:28','2022-03-31 00:08:28');

/*Table structure for table `tipo_historial` */

DROP TABLE IF EXISTS `tipo_historial`;

CREATE TABLE `tipo_historial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `clase` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*Data for the table `tipo_historial` */

insert  into `tipo_historial`(`id`,`nombre`,`clase`,`created_at`,`updated_at`) values (1,'Entrega','info','2021-06-03 18:03:59','2021-06-03 18:03:59'),(2,'Recibo','success','2021-06-03 18:03:59','2021-06-03 18:03:59');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

/*Data for the table `users` */

insert  into `users`(`id`,`role_id`,`fullname`,`email`,`password`,`photo`,`telefono`,`remember_token`,`status`,`created_at`,`updated_at`,`deleted_at`) values (1,1,'Administrador','admin@hotmail.com','$2y$10$KzMlpj5StUgWAZ1FGTgX.eGC7vu7alcdt16lqQtMun2gpUzvoVi2y','img/users/user-4.jpg','91801010','pRMAGpTgdPcAlAQ45EzB7NEoXVQ7DL9zvMfikzb9NSrfMwX7R8iIkcXYNnOH',1,NULL,'2021-08-26 17:41:01',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
