<?php
class DefautModel
{
	// Contiens la configuration
	public $aConfig = array();
	
	public function __construct()
	{
		$this->loadConfig();
		$this->pdo = new PDO( 'mysql:host=localhost;dbname=aproject', 'root', 'root' );
	}
	
	public function loadConfig()
	{
		/*
		 * Inclue la config par defaut ET la config lié à la classe appellé dans l'URL
		 * Exemple pour http://monsite.com/class/method
		 * Va chercher le fichier /private/configs/ClassConfig.php
		 */
		include( PRIVATE_PATH . '/configs/CoreConfig.php' );
		if (is_file(realpath(PRIVATE_PATH . '/configs/'.$_SESSION['class'].'Config.php')))
		{
			include( PRIVATE_PATH . '/configs/'.$_SESSION['class'].'Config.php' );
			$this->aConfig = array_merge( $DefautConfig, ${$_SESSION['class'].'Config'} );
		}
		else $this->config = $DefautConfig;
	}
}