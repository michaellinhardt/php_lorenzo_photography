<?php
class CoreAppController
{
	private $sClass ;
	private $sMethod ;
	private $oController ;
	private $bAjaxMethod ;
	public $aView ;
	public $mSetLayout ;
	public $mSetView ;

	public function setConfig()
	{
		/*
		 * Configuration requise pour la class
		 */
		$this->aView = array() ;
		$this->mSetLayout = USE_LAYOUT ;
		$this->mSetView = USE_VIEW ;
		$this->bAjaxMethod = false ;
	}

	public function setResponse()
	{
		/*
		 * R�cup�re la class et methode stoqu� dans les var de session
		 * et les passe au format ad�quat pour instancier la class et appeller la methode.
		 */
		$this->sClass = $_SESSION['class'] . 'Controller' ;
		$this->sMethod = $_SESSION['method'] . 'Method' ;
	}

	public function initResponse()
	{
		/*
		 * Instancie le controller
		 */
		$this->oController = new $this->sClass( ) ;
	}

	public function startMethod()
	{
		/*
		 * Si cette methode existe dans le controller elle est execut� en premi�re
		 */
		if ( method_exists( $this->sClass, '_start' ) )
		{
			$this->oController->_start() ;
			DebugAppModel::logThis( __FILE__, __LINE__, 'Appelle de la method [ _start ] du controller [ ' . $this->sClass . ' ]' ) ;
		}
	}

	public function endMethod()
	{
		/*
		 * Si cette methode existe dans le controller elle est execut� en derni�re
		 */
		if ( method_exists( $this->sClass, '_end' ) )
		{
			$this->oController->_end() ;
			DebugAppModel::logThis( __FILE__, __LINE__, 'Appelle de la method [ _end ] du controller [ ' . $this->sClass . ' ]' ) ;
		}
	}

	public function urlMethod()
	{
		/*
		 * Lance la fonction demand� par l'utilisateur
		 */
		$this->oController->{$this->sMethod}() ;
		DebugAppModel::logThis( __FILE__, __LINE__, 'Appelle de la method [ ' . $this->sMethod . ' ] du controller [ ' . $this->sClass . ' ]' ) ;
	}
	
	public function getControllerParam()
	{
		/*
		 * R�cup�re les parametre aView, mSetLayout et mSetView du controller instanci�
		 * uniquement si $this->bAjaxMethod = false (sinon on r�gle mSetView et mSetLayout sur false automatiquement)
		 */
		if ( (isset($this->oController->bAjaxMethod)) && ($this->oController->bAjaxMethod===true) )
		{
			DebugAppModel::logThis( __FILE__, __LINE__, 'bAjaxMethod = true (Ajax Method)' ) ;
			$this->mSetView = false ;
			$this->mSetLayout = false ;
		}
		else
		{
			if (isset($this->oController->mSetView)) $this->mSetView = $this->oController->mSetView ;
			if (isset($this->oController->mSetLayout)) $this->mSetLayout = $this->oController->mSetLayout ;
		}
		if (isset($this->oController->aView)) $this->aView = $this->oController->aView ;
	}

	public function logThis()
	{
		/*
		 * Converti les variables $this->mSetLayout et $this->mSetView
		 * afin de les inscrire dans le fichier de log
		 */
		if ( is_bool( $this->mSetLayout ) ) $sSetLayout = ($this->mSetLayout) ? 'true' : 'false' ;
		else $sSetLayout = $this->mSetLayout ;
		if ( is_bool( $this->mSetView ) ) $sSetView = ($this->mSetView) ? 'true' : 'false' ;
		else $sSetView = $this->mSetView ;
		$mSetView = $this->mSetView ;
		$sAView = (empty( $this->aView )) ? 'empty' : 'not empty' ;
		DebugAppModel::logThis( __FILE__, __LINE__, 'mSetLayout: ' . $sSetLayout . ' - mSetView: ' . $sSetView . ' - aView: ' . $sAView ) ;
	}
}