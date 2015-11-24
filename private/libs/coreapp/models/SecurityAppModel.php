<?php
class SecurityAppModel
{

	public function needAuth()
	{
		/*
		 * Si le parametre USE_PROTECT_URL = true, on vérifie l'acces
		 * Return true si l'utilisateur dois se connecter
		 * ou false si il est déjà conneté ou qu'il n'en à pas besoin
		 */
		if (USE_PROTECT_URL)
		{
			/*
			 * Vérifie en fonction de la configuration si il est nécessaire ou non
			 * d'avoir une autorisation pour acceder au controller demandé
			 */
			// Inclue la configuration
			$aConfig = self::includeConfigs() ;
			/*
			 * Avant tout on vérifie si l'utilisateur est connecté,
			 * car dans ce cas on retourne true quoi qu'il arrive ...
			 */
			$bIsAuth = self::isAuth($aConfig['IS_CONNECT_VAR_NAME']) ;
			if ($bIsAuth===true) return false ;
			// Récupére les parametre de l'URL
			$sRequestController = strtolower( $_SESSION['class'] ) ;
			$sRequestMethod = strtolower( $_SESSION['method'] ) ;
			// Vérifie les autorisations
			if ( $aConfig['PROTECT_MODE'] == 'black' )
			{
				/*
				 * Vérifie via la liste noir
				 * (Si le controller est dans cette liste, une autorisation est nécessaire)
				 */
				// Par defaut, il ne faut pas d'autorisation ($bNeedAuth = false )
				$bNeedAuth = false ;
				$aListProtect = $aConfig['PROTECT_LIST'][$sRequestController] ;
				// Tout le controller necessite une autorisation ->
				if ($aListProtect===true) $bNeedAuth = true ;
				// Certainne method du controller necessite une autorisation ->
				if ($aListProtect[$sRequestMethod]===true) $bNeedAuth = true ;
			}
			else
			{
				/*
				 * Vérifie via la liste blanche
				 * (Si le controller est dans cette liste, pas besoin d'autorisation)
				 */
				// Par defaut, il faut une autorisation ($bNeedAuth = true )
				$bNeedAuth = true ;
				$aListProtect = $aConfig['PROTECT_LIST'][$sRequestController] ;
				// Tout le controller ne necessite pas d'autorisation ->
				if ($aListProtect===true) $bNeedAuth = false ;
				// Certainne method du controller ne necessite pas d'autorisation ->
				if ($aListProtect[$sRequestMethod]===true) $bNeedAuth = false ;
			}
			return $bNeedAuth ;
		}
		// Si le parametre USE_PROTECT_URL = false alors, pas besoin d'autorisation par defaut
		else return false ;
	}
	
	private function isAuth($sIsConnectVarName)
	{
		if ($_SESSION[$sIsConnectVarName]===true) return true ;
		else return false ;
	}

	private function includeConfigs()
	{
		/*
		 * Tente d'inclure le fichier de config et renvoi une erreur si innexistant
		 */
		$sPath = realpath( COREAPP_PATH . '/configs/SecurityConfigs.php' ) ;
		if ( !is_file( $sPath ) )
		{
			DebugAppModel::stopApp( __FILE__, __LINE__, 'Fichier innexistant: ' . COREAPP_PATH . '/configs/SecurityConfigs.php' ) ;
		}
		else
		{
			include $sPath ;
			$aConfig = self::verifConfigFile( $aSecurity ) ;
			return $aConfig ;
		}
	}
	
	public function getConnectPage()
	{
		$aConfig = self::includeConfigs() ;
		return $aConfig['CONNECT_URL'] ;
	}

	private function verifConfigFile( $aConfig )
	{
		/*
		 * Controle que les paramètre fourni dans COREAPP_PATH . '/configs/SecurityConfigs.php' sont au bon format
		 */
		if ( !is_array( $aConfig ) ) DebugAppModel::stopApp( __FILE__, __LINE__, 'Erreur dans: ' . COREAPP_PATH . '/configs/SecurityConfigs.php -> $aSecurity n\'est pas un tableau' ) ;
		else
		{
			/*
			 * Vérifie la structure des paramètre
			 */
			if ( ($aConfig['PROTECT_MODE'] != 'white') && ($aConfig['PROTECT_MODE'] != 'black') ) DebugAppModel::stopApp( __FILE__, __LINE__, 'Erreur dans: ' . COREAPP_PATH . '/configs/SecurityConfigs.php -> $aSecurity[\'PROTECT_MODE\'] n\'est pas valide' ) ;
			$aVerifConnectUrl = explode( '/', $aConfig['CONNECT_URL'] ) ;
			if ( (empty( $aVerifConnectUrl[0] )) || (empty( $aVerifConnectUrl[1] )) ) DebugAppModel::stopApp( __FILE__, __LINE__, 'Erreur dans: ' . COREAPP_PATH . '/configs/SecurityConfigs.php -> $aSecurity[\'CONNECT_URL\'] n\'est pas valide' ) ;
			if ( !is_array( $aConfig['PROTECT_LIST'] ) ) DebugAppModel::stopApp( __FILE__, __LINE__, 'Erreur dans: ' . COREAPP_PATH . '/configs/SecurityConfigs.php -> $aSecurity[\'PROTECT_LIST\'] n\'est pas valide' ) ;
		}
		return $aConfig ;
	}
}