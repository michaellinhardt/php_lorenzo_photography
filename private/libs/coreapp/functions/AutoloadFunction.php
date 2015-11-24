<?php
/*
 * Fonction autoload pour inclure automatiquement les class appell�
 */
function __autoload( $sClass )
{
	/*
	 * Liste dans un tableau les diff�rent emplacements de fichiers possible
	 */
	$aTryPath[] = PRIVATE_PATH . '/libs/coreapp/' . $sClass . '.php' ;
	$aTryPath[] = PRIVATE_PATH . '/libs/coreapp/controllers/' . $sClass . '.php' ;
	$aTryPath[] = PRIVATE_PATH . '/libs/coreapp/models/' . $sClass . '.php' ;
	$aTryPath[] = PRIVATE_PATH . '/libs/coreapp/views/' . $sClass . '.php' ;
	$aTryPath[] = PRIVATE_PATH . '/controllers/' . $sClass . '.php' ;
	$aTryPath[] = PRIVATE_PATH . '/models/' . $sClass . '.php' ;
	$aTryPath[] = PRIVATE_PATH . '/views/' . $sClass . '.php' ;
	/*
	 * Tente d'inclure chaque entr�e du tableau
	 */
	foreach ( $aTryPath as $sValue )
	{
		if ( is_file( $sValue ) ) require_once $sValue ;
	}
}