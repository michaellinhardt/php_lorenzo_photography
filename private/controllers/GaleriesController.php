<?php
class GaleriesController
{
	public function IndexMethod()
	{
	}
	
	public function justdoitMethod()
	{
	}
	
	public function justdoitajaxMethod()
	{
		$this->bAjaxMethod = true ;
		$oGalerie = new GalerieModel();
		echo $oGalerie->justdoit();
	}
	
	public function getvignettesMethod()
	{
		$this->bAjaxMethod = true ;
		$oGalerie = new GalerieModel();
		echo $oGalerie->getVignette();
	}
}