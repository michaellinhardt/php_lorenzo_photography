<?php
/*
 * Cette class est le "processeur" ou "driver" de l'app, elle commande 
 * les différente class successivement utilisé dans l'app
 * comme le ferais le controller avec le model et la view
 */
class Bootstrap
{
	private $oRoutage ;
	private $oController ;
	private $oView ;
	private $aView ;
	private $mSetView ;
	private $mSetLayout ;
	private $bRequestDispatch ;

	public function __construct()
	{
		session_start() ;
		/*
		 * Si $_SESSION['bRequestDispatch'] = true, alors le Controller à déjà était innitialisé
		 * Dans se cas on ne peut plus rediriger l'utilisateur que par la method
		 * header(); dans le cas contraire, on peu le rediriger en changeant le contenu
		 * de $_SESSION['class'] et $_SESSION['method']
		 */
		$_SESSION['bRequestDispatch'] = false ;
	}

	public function run()
	{
		/*
		 * Lance et pilote l'application
		 */
		$this->getConfig() ;
		$this->resetDebugMode() ;
		DebugAppModel::logDetail() ;
		$this->setRoutage() ;
		$this->initController() ;
		$this->initView() ;
	}

	public function resetDebugMode()
	{
		/*
		 * Réinitialise la variable $_SESSION['debug'] qui stock les
		 * événement pour les afficher en cas de crash
		 */
		$_SESSION['debug'] = '' ;
	}

	private function getConfig()
	{
		/*
		 * Charge et applique la configuration de l'application
		 */
		require_once COREAPP_PATH . '/configs/CoreConfigs.php' ;
		ini_set( 'display_errors', DISPLAY_ERRORS ) ;
	}

	private function setRoutage()
	{
		/*
		 * Pilote le model de routage, il permet de lire la requette HTTP de l'utilisateur
		 * et de savoir quel couple class/method dois être lancé
		 */
		$this->oRoutage = new RoutageAppModel( ) ;
		$this->oRoutage->getRequest() ;
		/*
		 * Intervention du model SecurityAppModel avant de valider le routage
		 * La config de SecurityAppModel se trouve dans /private/coreapp/configs/SecurityConfigs.php
		 */
		/* (J'ai viré cette class car le site de lorenzo n'en a pas l'utilité)
		if ( SecurityAppModel::needAuth() )
		{
			// L'utilisateur n'a pas l'autorisation, on le redirige
			DebugAppModel::logThis( __FILE__, __LINE__, 'Acces refusé, controller: [ ' . $_SESSION['class'] . ' ], method: [ ' . $_SESSION['method'] . ' ]' ) ;
			$sConnectPage = SecurityAppModel::getConnectPage() ;
			$this->oRoutage->redirect($sConnectPage);
		}
		*/
		$this->oRoutage->formatRequest() ;
		$this->oRoutage->verifRequest() ;
		$this->oRoutage->logThis() ;
	}

	private function initController()
	{
		/*
		 * Instancie la class gérant les controller et la pilote
		 */
		$this->oController = new CoreAppController( ) ;
		$this->oController->setConfig() ;
		/*
		 * $_SESSION['bRequestDispatch'] = true car à partir d'ici on ne peut plus
		 * rediriger l'utilisateur, sauf avec la method header();
		 */
		$_SESSION['bRequestDispatch'] = true ;
		$this->oController->setResponse() ;
		$this->oController->initResponse() ;
		$this->oController->startMethod() ;
		$this->oController->urlMethod() ;
		$this->oController->endMethod() ;
		$this->oController->getControllerParam() ;
		$this->oController->logThis() ;
		/*
		 * Récupére les variables à transmettre à la view
		 */
		if ( isset( $this->oController->aView ) ) $this->aView = $this->oController->aView ;
		$this->mSetView = $this->oController->mSetView ;
		$this->mSetLayout = $this->oController->mSetLayout ;
	}

	private function initView()
	{
		/*
		 * Instancie la class gérant la view et la pilote
		 */
		$this->oView = new CoreAppView( ) ;
		/*
		 * Injecte les variables du controller
		 * NB: A propos de setLayout et setView (transmis par lecontroller)
		 * si setLayout/setView = true -> la class va utiliser le layout/view associé
		 * si setLayout/setView = false -> la classe ne va pas les utiliser
		 * si setLayout/setView = string -> la class va chercher le fichier en question
		 */
		$this->oView->mSetLayout = $this->mSetLayout ;
		$this->oView->mSetView = $this->mSetView ;
		$this->oView->aView = $this->aView ;
		/*
		 * Pilote la class
		 */
		$this->oView->getLang() ;
		$this->oView->display() ;
	}
}