<?php
class IndexController
{
	public function IndexMethod()
	{
	}
	
	public function getslideshowpicsMethod()
	{
		$this->bAjaxMethod = true ;
		$oGalerie = new GalerieModel();
		echo $oGalerie->GetSlideshowPics();
	}
}