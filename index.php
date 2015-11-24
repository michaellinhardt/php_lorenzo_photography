<?php
/*
 * Définie les constante utile au fonctionnement
 */
define( 'START_UPTIME', microtime( true ) ) ;
define( 'ROOT_PATH', realpath( dirname( __FILE__ ) ) ) ;
define( 'PRIVATE_PATH', realpath( dirname( __FILE__ ) . '/private/' ) ) ;
define( 'LIBS_PATH', realpath( dirname( __FILE__ ) . '/private/libs/' ) ) ;
define( 'LOGS_PATH', realpath( dirname( __FILE__ ) . '/private/libs/coreapp/logs/' ) ) ;
define( 'COREAPP_PATH', realpath( dirname( __FILE__ ) . '/private/libs/coreapp/' ) ) ;
define( 'PUBLIC_PATH', realpath( dirname( __FILE__ ) . '/public/' ) ) ;
define( 'PICS_PATH', realpath( dirname( __FILE__ ) . '/public/photos/' ) ) ;
define( 'THUMBS_PATH', realpath( dirname( __FILE__ ) . '/public/thumbs/' ) ) ;

//define( 'ROOT_HTTP', str_replace( "index.php", "", 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["PHP_SELF"] ) ) ;
/*
 * Version spécial pour le site de lorenzo (OVH de merde!!)
 */

if (preg_match('/localhost/si', $_SERVER['SERVER_NAME']))
	define( 'ROOT_HTTP', str_replace( "index.php", "", 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["PHP_SELF"] ) ) ;
else
	define( 'ROOT_HTTP', 'http://' . $_SERVER['SERVER_NAME'] . '/' );
define( 'REQUEST_HTTP', 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] ) ;
define( 'IMG_HTTP', ROOT_HTTP . 'public/img/' ) ;
define( 'CSS_HTTP', ROOT_HTTP . 'public/css/' ) ;
define( 'JS_HTTP', ROOT_HTTP . 'public/js/' ) ;
define( 'PICS_HTTP', ROOT_HTTP . 'public/photos/' ) ;
define( 'PUBLIC_HTTP', ROOT_HTTP . 'public/' ) ;
define( 'THUMBS_HTTP', ROOT_HTTP . 'public/thumbs/' ) ;
/*
 * Inclue les fonctions de l'application
 */
include (LIBS_PATH . '/coreapp/functions/AutoloadFunction.php') ;
/*
 * Lancement de l'application
 */
$bootstrap = new Bootstrap( ) ;
$bootstrap->run() ;
/*
 * Fermeture du fichier de log et calcul du temps d'execution si DISPLAY_UPTIME = true
 */
DebugAppModel::logUptime() ;
DebugAppModel::logSeparateur() ;