<?php
// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";
require_once "general_path.php";

 
// Configuración de Doctrine para que use Anotaciones en el mapeo de las Entidades
$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$path = $path_bootstrap;

//Para trabajar con entities
$config = Setup::createAnnotationMetadataConfiguration($path_bootstrap, $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);

//Para Mapear desde la base de datos y generar las entities:: Descomentar.
// Si vamos a usar xml o yml para el mapeo, usamos una de las dos siguientes opciones
//$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."\yaml"), $isDevMode);
 
// Parámetros de configuración para MySQL
$conn = array(
'driver'   => 'pdo_mysql',
'dbname'   => 'billetera',
'user'     => 'root',
'password' => '',
'host'     => 'localhost'
);
 
// Obtención del EntityManager
$entityManager = EntityManager::create($conn, $config);

?>