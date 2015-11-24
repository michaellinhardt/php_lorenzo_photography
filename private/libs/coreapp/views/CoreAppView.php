<?php
class CoreAppView
{
	public $aView ;
	public $mSetLayout ;
	public $mSetView ;
	private $bViewIsDisplayed ;
	private $bLayoutIsDisplayed ;
	private $bHeadIsCalled ;

	public function display()
	{
		/*
		 * Parametre permetant à la fonction controlDisplay de vérifier
		 * que les parametre stipuler dans la config de l'application
		 * sont respecté par le développeur
		 */
		$this->bViewIsDisplayed = false ;
		$this->bLayoutIsDisplayed = false ;
		$this->bHeadIsCalled = false ;
		/*
		 * Démarre l'affichage du layout et/ou de la view
		 */
		$this->getPath() ;
		if ( ($this->mSetLayout) && ($this->mSetView) )
			/*
			 * Affiche les deux
			 * NB: Pour afficher la view il faut insérer dans le layout la commande
			 * <?php $this->incView(); ?>
			 */
			$this->incLayout() ;
		elseif ( (!$this->mSetLayout) && ($this->mSetView) ) $this->incView() ;
		else $this->incLayout() ;
		// Si le layout est inclue, effectu des vérification entre les parametre de l'application et le codage du développeur
		if ( $this->mSetLayout ) $this->controlDisplay() ;
	}

	public function controlDisplay()
	{
		/*
		 * Cette fonction stop l'application SI ->
		 */
		// La method $this->incView() ne se trouve dans le layout, alors que mSetView = true
		if ( (!$this->bViewIsDisplayed) && ($this->mSetView) ) DebugAppModel::StopApp( __FILE__, __LINE__, 'La method incView() ne se trouve pas dans le layout alors que mSetView = true' ) ;
		// La method $this->head() ne se trouve dans le layout, alors que AUTO_INC_JS = true
		if ( (!$this->bHeadIsCalled) && (AUTO_INC_JS) ) DebugAppModel::StopApp( __FILE__, __LINE__, 'La method head() ne se trouve pas dans le layout alors que AUTO_INC_JS = true' ) ;
		// La method $this->head() ne se trouve dans le layout, alors que AUTO_INC_CSS = true
		if ( (!$this->bHeadIsCalled) && (AUTO_INC_CSS) ) DebugAppModel::StopApp( __FILE__, __LINE__, 'La method head() ne se trouve pas dans le layout alors que AUTO_INC_CSS = true' ) ;
		// La method $this->head() ne se trouve dans le layout, alors que USE_PHPLANGTOJS = true
		if ( (!$this->bHeadIsCalled) && (USE_PHPLANGTOJS) ) DebugAppModel::StopApp( __FILE__, __LINE__, 'La method head() ne se trouve pas dans le layout alors que USE_PHPLANGTOJS = true' ) ;
		// La config USE_PHPLANGTOJS = true alors que USE_LANG_MODEL = false
		if ( (!USE_LANG_MODEL) && (USE_PHPLANGTOJS) ) DebugAppModel::StopApp( __FILE__, __LINE__, 'USE_PHPLANGTOJS est sur true alors que USE_LANG_MODEL est sur false' ) ;
		// La method $this->head() ne se trouve dans le layout, alors que HTTP_PATH_TO_JS = true
		if ( (!$this->bHeadIsCalled) && (HTTP_PATH_TO_JS) ) DebugAppModel::StopApp( __FILE__, __LINE__, 'La method head() ne se trouve pas dans le layout alors que HTTP_PATH_TO_JS = true' ) ;
	}

	public function head()
	{
		/*
		 * Ajout du code automatiquement au header en fonction des parametre de l'application
		 */
		$this->bHeadIsCalled = true ;
		$sHead = '' ;
		if ( ( USE_PHPLANGTOJS ) && ( USE_LANG_MODEL ) )
		{
			/*
			 * Inclue la langue dans une objet javascript
			 */
			$sHead .= '
			<script type="text/javascript">
				oLang = "" ;
				sBaseurl = "' . ROOT_HTTP . '" ;
				$(document).ready(function(){
				oLang = ' . json_encode( $this->oLang->aLang ) . '
				});
			</script>' . "\n" ;
		}
		if ( HTTP_PATH_TO_JS )
		{
			$sHead .= '<script type="text/javascript">sBaseurl = "' . ROOT_HTTP . '" ;</script>';
		}
		if ( AUTO_INC_JS )
		{
			/*
			 * Inclue automatiquement les .JS en suivant le schema suivant:
			 * /public/js/class/method.js
			 * exemple pour http://monsite.com/index/connection
			 * inclue /public/js/index/connection.js
			 * (uniquement si le fichier existe)
			 */
			$sJsPath = strtolower( $_SESSION['class'] ) . '/' . strtolower( $_SESSION['method'] ) . '.js' ;
			$sJsPath2 = strtolower( $_SESSION['class'] ) . '/defaut.js' ;
			if ( is_file( PUBLIC_PATH . '/js/defaut.js' ) ) $sHead .= '<script type="text/javascript" src="' . JS_HTTP . 'defaut.js' . '"></script>' . "\n" ;
			if ( is_file( PUBLIC_PATH . '/js/' . $sJsPath ) ) $sHead .= '<script type="text/javascript" src="' . JS_HTTP . $sJsPath . '"></script>' . "\n" ;
			if ( is_file( PUBLIC_PATH . '/js/' . $sJsPath2 ) ) $sHead .= '<script type="text/javascript" src="' . JS_HTTP . $sJsPath2 . '"></script>' . "\n" ;
		}
		if ( AUTO_INC_CSS )
		{
			/*
			 * Inclue automatiquement les .CSS en suivant le schema suivant:
			 * /public/css/class.css
			 * exemple pour http://monsite.com/index/connection
			 * inclue /public/css/index.css
			 * (uniquement si le fichier existe)
			 */
			$sCssPath = strtolower( $_SESSION['class'] ) . '.css' ;
			if ( is_file( PUBLIC_PATH . '/css/' . $sCssPath ) ) $sHead .= '<LINK rel="stylesheet" type="text/css" href="' . CSS_HTTP . $sCssPath . '">' . "\n" ;
			if ( is_file( PUBLIC_PATH . '/css/defaut.css' ) ) $sHead .= '<LINK rel="stylesheet" type="text/css" href="' . CSS_HTTP . 'defaut.css' . '">' . "\n" ;
		}
		echo $sHead ;
	}

	public function incView()
	{
		/*
		 * Affiche la view uniquement si mSetView !== false
		 */
		$this->bViewIsDisplayed = true ;
		if ( is_string( $this->mSetView ) ) include ($this->mSetView) ;
	}

	public function incLayout()
	{
		/*
		 * Affiche le layout uniquement si mSetLayout !== false
		 */
		$this->bLayoutIsDisplayed = true ;
		if ( is_string( $this->mSetLayout ) ) include ($this->mSetLayout) ;
	}

	public function getLang()
	{
		if ( USE_LANG_MODEL )
		{
			$this->oLang = new LanguageAppModel( ) ;
			$this->oLang->setLang() ;
			$this->oLang->getLang() ;
		}
	}

	public function getPath()
	{
		/*
		 * Détermine les chemin d'acces de la view et du layout
		 */
		if ( is_string( $this->mSetLayout ) ) $this->setLayoutPath( PRIVATE_PATH . '/views/' . $this->mSetLayout ) ;
		elseif ( $this->mSetLayout === true ) $this->setLayoutPath( PRIVATE_PATH . '/views/layout.php' ) ;
		if ( is_string( $this->mSetView ) ) $this->setViewPath( PRIVATE_PATH . '/views/' . $this->mSetView ) ;
		elseif ( $this->mSetView === true ) $this->setViewPath( PRIVATE_PATH . '/views/' . strtolower( $_SESSION['class'] ) . '/' . strtolower( $_SESSION['method'] ) . '.php' ) ;
	}

	public function setLayoutPath( $sPath )
	{
		/*
		 * définie le chemin d'acces du Layout et controle que le fichier existe
		 */
		if ( !is_file( realpath( $sPath ) ) ) DebugAppModel::StopApp( __FILE__, __LINE__, 'Fichier LAYOUT manquant: ' . $sPath ) ;
		else $this->mSetLayout = $sPath ;
	}

	public function setViewPath( $sPath )
	{
		/*
		 * définie le chemin d'acces de la view et controle que le fichier existe
		 */
		if ( !is_file( realpath( $sPath ) ) ) DebugAppModel::StopApp( __FILE__, __LINE__, 'Fichier VIEW manquant: ' . $sPath ) ;
		else $this->mSetView = $sPath ;
	}
	
	public function lang()
	{
		/*
		 * Appelle la fonction lang() du modèle LanguageAppModel
		 * et retourne le résultat en echo
		 */
		if ( !USE_LANG_MODEL )
			DebugAppModel::StopApp( __FILE__, __LINE__, 'Impossible d\'utiliser la fonction lang() dans la view car USE_LANG_MODEL est false' ) ;
		else
			echo $this->oLang->lang(func_get_args()) ;
	}
}