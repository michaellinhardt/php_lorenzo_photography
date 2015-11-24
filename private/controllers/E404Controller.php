<?php
class E404Controller
{
	public function IndexMethod()
	{
		$this->bAjaxMethod = true ;
		echo 'Erreur 404' ; // en attendant une page 404 personnalisé ...
	}
}