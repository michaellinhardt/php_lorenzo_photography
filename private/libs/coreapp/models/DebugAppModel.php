<?php
class DebugAppModel
{
	public function logThis( $sFile, $sLine, $sMessage )
	{
		if ( LOGGING )
		{
			/*
			 * Date et heure
		 	*/
			$sDate = date( 'Y-m-d' ) ;
			$sTime = date( 'H:i:s' ) ;
			/*
			 * Chemin d'acces du fichier
			 */
			$sPath = LOGS_PATH . '/' . $sDate . '.txt' ;
			/*
		 	* Formatage du log
		 	*/
			$sFile = str_replace( ROOT_PATH, '', $sFile ) ;
			$sLogMessage = '[' . $sTime . '] ' . $sFile . ' #' . $sLine . ' -> ' . $sMessage ;
			/*
		 	* Ecriture dans le fichier
		 	*/
			$oFile = fopen( $sPath, 'a+' ) ;
			fwrite( $oFile, $sLogMessage . "\n" ) ;
			fclose( $oFile ) ;
		}
		if ( (DEBUG_MODE) || (ALWAYS_DEBUG_MODE) ) $_SESSION['debug'][] = $sLogMessage ;
	}

	public function logSeparateur()
	{
		/*
		 * Termine la session de log pour la page affiché
		 * et affiche les log si ALWAYS_DEBUG_MODE = true ;
		 */
		self::purgeLogFile() ;
		self::logThis( '*', ' *', '****************************************************************************************************' ) ;
		if ( ALWAYS_DEBUG_MODE ) self::displayDebug() ;
	}

	public function logDetail()
	{
		/*
		 * Si la config le demande, log les info lié à l'user
		 */
		if ( LOGGING_USER_INFO )
		{
			/*
			 * Log les information détaillé de l'application
			 */
			self::logThis( __FILE__, __LINE__, 'Adresse IP : ' . $_SERVER['REMOTE_ADDR'] . ' : ' . $_SERVER['REMOTE_PORT'] ) ;
			self::logThis( __FILE__, __LINE__, 'Navigateur : ' . $_SERVER['HTTP_USER_AGENT'] ) ;
			self::logThis( __FILE__, __LINE__, 'HTTP Request : ' . REQUEST_HTTP ) ;

		}
		/*
		 * Si la config le demande, log le POST et GET
		 */
		if (LOGGING_POST_GET)
		{
			if ( !empty( $_POST ) ) self::logThis( __FILE__, __LINE__, '$_POST: ' . serialize($_POST) ) ;
			if ( !empty( $_GET ) ) self::logThis( __FILE__, __LINE__, '$_GET: ' . serialize($_GET) ) ;
		}
	}

	public function purgeLogFile()
	{
		/*
		 * Netoie le nombre de fichiers dans le dossier de log
		 */
		$sPath = LOGS_PATH ;
		$aListFile = array() ;
		$iTotal = 0 ;
		$oHandle = opendir( realpath( $sPath ) ) ;
		while ( $sFile = readdir( $oHandle ) )
		{
			/*
			 * Liste les fichiers contenue dans le dossier de log
			 */
			$sCurrentFile = realpath( $sPath . '/' . $sFile ) ;
			if ( is_file( $sCurrentFile ) )
			{
				$iTotal++ ;
				$aListFile[] = $sCurrentFile ;
			}
		}
		closedir( $oHandle ) ;
		/*
		 * Si le nombre de fichier exede le parametre LOGGING_MAX_FILE, on nettoi
		 */
		$iExedant = ($iTotal - LOGGING_MAX_FILE) - 1 ;
		if ( $iExedant >= 0 )
		{
			for ( $iCurrentDelete = 0 ; $iCurrentDelete <= $iExedant ; $iCurrentDelete++ )
			{
				unlink( realpath( $aListFile[$iCurrentDelete] ) ) ;
				self::logThis( __FILE__, __LINE__, 'Supression de: ' . $aListFile[$iCurrentDelete] ) ;
			}
		}
	}

	public function stopApp( $sFile, $sLine, $sMessage = '' )
	{
		$sMessageLog = $sMessage ; // protége le message d'origine pour le loguer
		if ( !DISPLAY_ERRORS ) $sMessage = 'Consultez l\'administrateur du site pour en savoir plus.' ;
		if ( is_file( COREAPP_PATH . '/views/ErrorAppView.php' ) )
		{
			include COREAPP_PATH . '/views/ErrorAppView.php' ;
		}
		else
		{
			echo '<h1>Erreur Fatal dans l\'application!</h1>' ;
			if ( !empty( $sMmessage ) ) echo '<p>Message: ' . $sMessage . '</p>' ;
		}
		self::logThis( $sFile, $sLine, 'ERREUR FATAL: ' . $sMessageLog ) ;
		self::logUptime() ;
		if ( DEBUG_MODE ) self::displayDebug() ;
		self::logSeparateur() ;
		exit() ;
	}

	public function displayDebug()
	{
		/*
		 * Affiche le contenu du log pour le chargement de la page en cours
		 */
		echo '<pre><fieldset><legend>Debug Mode</legend><ul>' ;
		foreach ( $_SESSION['debug'] as $sValue )
		{
			echo '<li>' . $sValue . '</li>' ;
		}
		echo '</ul></fieldset></pre>' ;
	}

	public function logUptime()
	{
		/*
		 * Enregistre le temps de chargement de la page dans les log
		 */
		if ( DISPLAY_UPTIME ) self::logThis( __FILE__, __LINE__, 'UPTIME: ' . round( microtime( true ) - START_UPTIME, 4 ) ) ;
	}

	public function dump( $mVar )
	{
		echo '<pre>' ;
		var_dump( $mVar ) ;
		echo '</pre>' ;
	}
}