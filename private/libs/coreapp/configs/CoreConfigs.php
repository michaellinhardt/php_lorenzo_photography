<?php
/*
 * Configuration de l'application, stoqué dans des constante 
 * permetant à toutes les class de les consulter
 */
// Configuration de l'app
define( 'DEFAULT_CLASS', 'Index' ) ; // Class appellé en arrivant sur le site
define( 'DEFAULT_METHOD', 'Index' ) ; // Method appellé en arrivant sur le site
// Gestion des erreurs
define( 'DISPLAY_ERRORS', false ) ; // Affiche ou non les erreurs (bool)
define( 'DEBUG_MODE', false ) ; // Affiche les log quand l'application plante (bool)
define( 'ALWAYS_DEBUG_MODE', false ) ; // Affiche systematiquement les log de l'application (bool)
// Gestion des logs
define( 'LOGGING', false ) ; // Log ou non les événement de l'application (bool)
define( 'LOGGING_MAX_FILE', 1 ) ; // nombre de jours à conserver dans les logs (int)
define( 'LOGGING_USER_INFO', false ) ; // Log ou non les info sur l'utilisateur (bool)
define( 'LOGGING_POST_GET', false ) ; // Log le contenu des POST et GET, très dangereux, peu contenir les code user (bool)
define( 'DISPLAY_UPTIME', false ) ; // Affiche et log le temps de chargement de la page si erreur (bool)
// Gestion des views
define( 'USE_LAYOUT', true ) ; // Utiliser ou non un layout (bool)
define( 'USE_VIEW', true ) ; // Utiliser ou non les view (bool)
define( 'AUTO_INC_JS', true ) ; // Charge automatiquement les .js (bool)
define( 'AUTO_INC_CSS', true ) ; // Charge automatiquement les .css (bool)
define( 'USE_PHPLANGTOJS', false ) ; // Transmet le contenue de la variable lang dans une variable lang en JS (necessite jQuery) (bool)
define( 'HTTP_PATH_TO_JS', true ); // Transmet les chemin HTTP des constant dans des variable JS
// Geston de la lang
define( 'USE_LANG_MODEL', false );
define( 'DEFAUT_LANG', 'fr' ) ; // language par defaut (fr, en, sp, etc ...) (string)
define( 'AUTO_HTMLENTITIES_LANG', true ) ; // applique la fonction htmlentities au fichier de lang (bool)