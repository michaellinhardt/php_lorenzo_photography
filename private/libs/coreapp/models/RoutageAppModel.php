<?php
class RoutageAppModel
{

	public function getRequest()
	{
		/*
		 * R�cup�re la partie indiquant la class et le mod�le dans l'URL
		 * appell� par l'utilisateur.
		 * Exemple pour: http://monsite.com/maclass/mamethod
		 * ressort $sClass = 'Maclass' ; et $sMethod = 'Mamethod' ;
		 */
		$sClassMethod = str_replace( ROOT_HTTP, '', REQUEST_HTTP ) ;
		$aRequest = explode( '?', $sClassMethod ) ;
		$sClassMethod = $aRequest[0] ;
		$aRequest = explode( '#', $sClassMethod ) ;
		$sClassMethod = $aRequest[0] ;
		/*
		 *  Ajoute un / final au string $sClassMethod(pour pallier � des probl�me d'URL
		 *  (pour pallier � des probl�mes d'URL dans certains cas particulier)
		 *  et ins�re le tout dans deux variable de session
		 */
		if ( substr( $sClassMethod, -1 ) != '/' ) $sClassMethod .= '/' ;
		$aRequest = explode( '/', $sClassMethod ) ;
		$_SESSION['class'] = (empty( $aRequest[0] )) ? DEFAULT_CLASS : $aRequest[0] ;
		$_SESSION['method'] = (empty( $aRequest[1] )) ? DEFAULT_METHOD : $aRequest[1] ;
	}

	public function redirect( $sClassMethod )
	{
		// Si le Dispatch est d�j� fais, on utilise la method header();
		if ( $_SESSION['bRequestDispatch'] )
		{
			DebugAppModel::logThis( __FILE__, __LINE__, 'Redir�ction -> [ header(Location: ' . ROOT_HTTP . $sClassMethod .'); ]') ;
			header( 'Location: ' . ROOT_HTTP . $sClassMethod ) ;
			exit() ;
		}
		// Si le Dispatch n'est pas fait on change juste le class/method dans l'application
		// On r�cup�re les param�tre (class/method) pass� par le dev'
		$aClassMethod = explode( '/', $sClassMethod ) ;
		$_SESSION['class'] = $aClassMethod[0] ;
		$_SESSION['method'] = $aClassMethod[1] ;
		DebugAppModel::logThis( __FILE__, __LINE__, 'Redir�ction -> Controller: [ '.$_SESSION['class'].' ] Method: [ ' . $_SESSION['method'] . ' ]' ) ;
		self::formatRequest() ;
		self::verifRequest() ;
	}

	public function formatRequest()
	{
		/*
		 * Formate le nom de class et method selon ce mod�le:
		 * Premi�re lettre en majuscule et le reste en minuscule
		 */
		$_SESSION['class'] = strtolower( $_SESSION['class'] ) ;
		$_SESSION['method'] = strtolower( $_SESSION['method'] ) ;
		$_SESSION['class'] = ucfirst( $_SESSION['class'] ) ;
		$_SESSION['method'] = ucfirst( $_SESSION['method'] ) ;
	}

	public function verifRequest()
	{
		/*
		 * V�rifie que le couple class/method existe
		 * Sinon appel la method $this->set_e404();
		 */
		if ( (!method_exists( $_SESSION['class'] . 'Controller', $_SESSION['method'] . 'Method' )) && ($_SESSION['class'] != 'E404') ) self::setE404() ;
	}

	public function setE404()
	{
		/*
		 * Fonction forcant la class E404 et method index pour afficher une erreur 404
		 * Appell� quand l'URL demand� n'est pas correct
		 */
		header( 'HTTP/1.0 404 Not Found' ) ;
		if ( (!method_exists( 'E404Controller', 'IndexMethod' )) )
		{
			if ( $_SESSION['class'] == 'Public' ) DebugAppModel::stopApp( __FILE__, __LINE__, 'Fichier innexistant: ' . REQUEST_HTTP ) ;
			else
			{
				DebugAppModel::logThis( __FILE__, __LINE__, 'Erreur Fatal: Les controllers [ E404Controller ] et [ ' . $_SESSION['class'] . ' ] sont introuvables.' ) ;
				DebugAppModel::stopApp( __FILE__, __LINE__, 'Impossible de charger la page demand�, ainsi que la page 404 !' ) ;
			}
		}
		$_SESSION['class'] = 'E404' ;
		$_SESSION['method'] = 'index' ;
		self::formatRequest() ;
	}

	public function logThis()
	{
		DebugAppModel::logThis( __FILE__, __LINE__, 'Class: [ ' . $_SESSION['class'] . ' ] - Method: [ ' . $_SESSION['method'] . ' ]' ) ;
	}
}