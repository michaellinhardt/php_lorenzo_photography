<?php
class GalerieModel
{
	public function justdoit()
	{
		set_time_limit(0);
		/*
		 * Créer le thumbs de toutes les photos
		 */
		// Liste tout les fichier/dossier dans thumbs pour le vider
/*		
		$aThumbsFolder = $this->listFile(THUMBS_PATH);
		foreach( $aThumbsFolder as $iKey => $sPath )
		{
			// dans ce cas c'est un dossier
			if (is_dir(realpath(THUMBS_PATH . '/' . $sPath )))
			{
				// On entre dedans et on vide tout
				$aThumbsFolderFile = $this->listFile(realpath(THUMBS_PATH . '/' . $sPath ));
				foreach ($aThumbsFolderFile as $sThumbsFolderFile)
				{
					unlink(realpath(THUMBS_PATH . '/' . $sPath . '/' . $sThumbsFolderFile ));
				}
				rmdir(realpath(THUMBS_PATH . '/' . $sPath ));
			}
			// dans ce cas c'est un fichier
			else unlink(realpath(THUMBS_PATH . '/' . $sPath )) ;
		}
*/
		// Liste les fichiers et dossier dans photos
		$aListFolder = $this->listFile(PICS_PATH);
		foreach( $aListFolder as $sPicsFolder )
		{
			// Si c'est un répertoire de photos
			if ((is_dir(realpath(PICS_PATH . '/' . $sPicsFolder))) && ($sPicsFolder[0]=='_'))
			{
				// liste le contenue du répertoire
				$aListPicsFolder = $this->listFile(PICS_PATH . '/' . $sPicsFolder) ;
				foreach( $aListPicsFolder as $sPicsFile )
				{
					// Si c'est une photo
					if ((preg_match('/.jpg/si', $sPicsFile)) || (preg_match('/.jpeg/si', $sPicsFile)))
					{
						// Controle le nom du fichier et créer le thumbs
						$sPicsFile = $this->controlPics($sPicsFolder, $sPicsFile) ;			
					}
				}
			}
		}
		return 1 ;
	}
	
	public function controlPics( $sPicsFolder, $sPicsFile )
	{
		$sPicsFile = $this->verifPicsName($sPicsFolder, $sPicsFile) ;
		$this->controlSize($sPicsFolder, $sPicsFile);
		$this->getThumb( $sPicsFolder, $sPicsFile ) ;
		
		return $sPicsFile ;
	}
	
	public function GetSlideshowPics()
	{
		/*
		 * Séléction au hasard une photo dans tout les album
		 */
		// Liste tout les dossier photo
		$aListFolder = $this->listFile(PICS_PATH);
		foreach( $aListFolder as $iKey => $sPath )
		{
			if ($sPath[0]!='_') unset($aListFolder[$iKey]);
		}
		// Liste toute les photo d'un dossié au hasard
		$sFolder = $aListFolder[array_rand($aListFolder)];
		$aListPics = $this->listFile(realpath(PICS_PATH . '/' . $sFolder));
		// Renvoie une photo au hasard ainsi que ses dimention
		$aPics = array();
		$sRandomPics = $aListPics[array_rand($aListPics)] ;
		//$this->controlPics($sFolder, $sRandomPics);
		$aPics['url'] = PICS_HTTP . $sFolder . '/' . $sRandomPics;
		$aDimention = getimagesize($aPics['url']);
		$aPics['width'] = $aDimention[0];
		$aPics['height'] = $aDimention[1];
		return json_encode($aPics);
	}
	public function getVignette()
	{
		/*
		 * Cette fonction donne la liste des vignette à afficher
		 * Récupération des variable transmis par ajax
		 */
		$iPageType = intval($_POST['iPageType']) ;
		$iPageNum = intval($_POST['iPageNum']) ;
		//$iPageType = 1 ;
		//$iPageNum = 1 ;
		$sGalerieId = $_POST['sGalerieId'] ;
		$iNbGalPerPage = 6 ;
		$iNbSgalPerPage = 12 ;
		/*
		 * Si la page demandé est la page des galerie
		 */
		if ($iPageType==1)
		{
			/*
			 * Calcule a partir de quelle vignette il faut afficher
			 * et a partir de quelle vignette il faut arreter l'affichage
			 */
			$iStart = ($iPageNum * $iNbGalPerPage) - ($iNbGalPerPage - 1) ;
			$iEnd = $iPageNum * $iNbGalPerPage ;
			$aListFile = $this->listFile(PICS_PATH);
			/*
			 * Démarre une boucle pour trouver les dossier concerné par iStart et iEnd
			 */
			$i = 1 ;
			$aDisplayVignette = array() ;
			foreach( $aListFile as $iKey => $sPath )
			{
				if ((is_dir(realpath(PICS_PATH . '/' . $sPath))) && ($sPath[0]=='_'))
				{
					if (($i>($iStart-1)) && ($i<($iEnd+1)))
					{
						if (!isset($iPrevKey)) $iPrevKey = $iKey - 1 ;
						$aDisplayVignette['pics'][$i]['id'] = $sPath ;
						$aDisplayVignette['pics'][$i]['titre'] = $this->getGalTitre($sPath) ;
						$sFirstPics = $this->getFirstPics(PICS_PATH . '/' . $sPath) ;
						//$sFirstPics = $this->controlPics($sPath, $sFirstPics);
						$aDisplayVignette['pics'][$i]['vignette'] = THUMBS_HTTP . $sPath . '/' . $sFirstPics ;
					}
					/*
					 * Incrémente le décompte et sort de la boucle si le total de fichier est attein
					 */
					$i++;
					if ($i>$iEnd)
					{
						$iNextKey = $iKey + 1 ;
						break;
					}
				}
				else unset($aListFile[$iKey]) ;
			}
			// Détermine si il y a des galerie avant et après celle affiché
			$aDisplayVignette['prev'] = (isset($aListFile[$iPrevKey])) ? 1 : 0 ;
			if (!isset($iNextKey)) $aDisplayVignette['next'] = 0 ; else $aDisplayVignette['next'] = (isset($aListFile[$iNextKey])) ? 1 : 0 ;
		}
		else
		{
			/*
			 * Calcule a partir de quelle vignette il faut afficher
			 * et a partir de quelle vignette il faut arreter l'affichage
			 */
			$iStart = ($iPageNum * $iNbSgalPerPage) - ($iNbSgalPerPage - 1) ;
			$iEnd = $iPageNum * $iNbSgalPerPage ;
			$aListFile = $this->listFile(realpath(PICS_PATH.'/'.$sGalerieId));
			/*
			 * Démarre une boucle pour récupèrer la liste des images concerné par le $iStart et $iEnd
			 */
			$i = 1 ;
			$aDisplayVignette = array() ;
			foreach( $aListFile as $iKey =>  $sFile )
			{
				if ((preg_match('/.jpg/si', $sFile)) || (preg_match('/.jpeg/si', $sFile)))
				{
					//$sFile = $this->controlPics($sGalerieId, $sFile) ;
					if (($i>($iStart-1)) && ($i<($iEnd+1)))
					{
						if (!isset($iPrevKey)) $iPrevKey = $iKey - 1 ;
						$iId = explode('.', $sFile); $iId = $iId[0];
						$aDisplayVignette['pics'][$i]['id'] = $iId ;
						$aDisplayVignette['pics'][$i]['imgPath'] = PICS_HTTP . $sGalerieId . '/' . $sFile ;
						$aDisplayVignette['pics'][$i]['thumbPath'] = THUMBS_HTTP . $sGalerieId . '/' . $sFile ;
						
					}
					/*
					 * Incrémente le décompte et sort de la boucle si le total de fichier est attein
					 */
					$i++;
					if ($i>$iEnd)
					{
						$iNextKey = $iKey + 1 ;
						break;
					}
				}
				else unset($aListFile[$iKey]) ;
			}
			// Détermine si il y a des galerie avant et après celle affiché
			$aDisplayVignette['prev'] = (isset($aListFile[$iPrevKey])) ? 1 : 0 ;
			if (!isset($iNextKey)) $aDisplayVignette['next'] = 0 ; else $aDisplayVignette['next'] = (isset($aListFile[$iNextKey])) ? 1 : 0 ;
		}
		return json_encode($aDisplayVignette) ;
	}
	
	private function verifPicsName($sGalerieId, $sFile)
	{
		// Formate le nom de fichier comme il convient
		$sNewFile = $sFile ;
		$aReplaceChar = array( ' ', '(', ')', 'é', 'à', 'è', 'ô', 'ù' );
		$sNewFile = str_ireplace($aReplaceChar, '_', $sNewFile) ;		
		if ($sFile != $sNewFile )
		{
			copy(PICS_PATH . '/' . $sGalerieId . '/' . $sFile, PICS_PATH . '/' . $sGalerieId . '/' . $sNewFile );
			unlink(PICS_PATH . '/' . $sGalerieId . '/' . $sFile);
		}
		$sFile = $sNewFile ;
		
		return $sFile ;
	}
	
	private function listFile($sPath)
	{
		/*
		 * Liste les fichiers d'un répertoire
		 */
		$aListFile = array() ;
		$oOpen = opendir($sPath);
		while ( $sFile = readdir($oOpen) )
		{
			// enleve les fichiers . et ..
			if ( ($sFile != '.') && ($sFile != '..') )
			$aListFile[] = $sFile ;
		}
		closedir($oOpen);
		return $aListFile ;
	}
	
	private function getGalTitre($sPath)
	{
		if (is_file(realpath(PICS_PATH.'/'.$sPath.'/nom.png')))
			return PICS_HTTP.$sPath.'/nom.png' ;
		else if (is_file(realpath(PICS_PATH.'/'.$sPath.'/nom.txt')))
			return trim(file_get_contents(realpath(PICS_PATH.'/'.$sPath.'/nom.txt'))) ;
		else
			return str_replace( '_', ' ', strstr( substr($sPath, 1), '_' ) ) ;
	}

	private function getFirstPics($sPath)
	{
		// Cherche la première image pour créer une miniature
		$oOpen = opendir($sPath);
		while ( $sFile = readdir($oOpen) )
		{
			if (preg_match('/\.jpg/si', $sFile)) { closedir($oOpen); return $sFile ; }
			if (preg_match('/\.jepg/si', $sFile)) { closedir($oOpen); return $sFile ; }
		}
	}
	
	private function getThumb($sGalerieId, $sFile)
	{
		$sDestinationPath = THUMBS_PATH . '/' . $sGalerieId . '/' . $sFile ;
		if (!is_file($sDestinationPath))
		{
			// Innitialise la fonction
			set_time_limit(0);
			$iDestW = 200 ;
			$iDestH = 150 ;
			if (!is_dir(THUMBS_PATH .  '/' . $sGalerieId . '/')) mkdir(THUMBS_PATH .  '/' . $sGalerieId . '/');
			
			// Récupére l'image et les dimentions
			$sSourcePath = PICS_PATH . '/' . $sGalerieId . '/' . $sFile ;
			list($iSourceW, $iSourceH) = getimagesize($sSourcePath);
			
			// Calcule des nouvelles dimentions
			$iRatioH = $iDestW/$iSourceW;
			$iRatioW = $iDestH/$iSourceH;
			if ($iRatioH>$iRatioW) $iRatio = $iRatioH ;
			else $iRatio = $iRatioW ;
			$iNewW = $iRatio*$iSourceW ;
			$iNewH = $iRatio*$iSourceH ;
			
			
			// Calcule la position de l'image pour le crop
			$iMiddleX = $iNewW / 2 ;
			$iMiddleY = $iNewH / 2 ;
			
			// Ouvre l'image source
			$oSource = imagecreatefromjpeg($sSourcePath);
			// Créer une image vide au dimention du thumbs numéro 1 et la sauvegarde
			$oDest = imagecreatetruecolor(round($iNewW), round($iNewH));
			imagecopyresampled($oDest, $oSource, 0, 0, 0, 0, $iNewW, $iNewH, $iSourceW, $iSourceH);
			// Créer une image vide au dimention du thumbs réel et la sauvegarde
		    $oThumb = imagecreatetruecolor($iDestW, $iDestH);
		    imagecopyresampled($oThumb, $oDest, 0, 0, ($iMiddleX-($iDestW/2)), ($iMiddleY-($iDestH/2)), $iDestW, $iDestH, $iDestW, $iDestH);
			
			// On enregistre la miniature sous le nom "mini_couchersoleil.jpg"
			imagejpeg($oThumb, $sDestinationPath);
			imagedestroy($oDest);
		    imagedestroy($oSource);
		    imagedestroy($oThumb);
		}
		return THUMBS_HTTP . $sGalerieId . '/' . $sFile ;
	}
	
	private function controlSize($sFolder, $sFile)
	{
		/*
		 * Redimentionne les photo supérieur à 1024*768
		 */
		$iMaxW = 1024 ;
		$iMaxH = 768 ;
		
		// récupére les taille de la photo
		$sSourcePath = PICS_PATH . '/' . $sFolder . '/' . $sFile ;
		list($iSourceW, $iSourceH) = getimagesize($sSourcePath);
		
		$iNewW = $iSourceW ;
		$iNewH = $iSourceH ;
		
		if (( $iSourceH > $iMaxH ) || ( $iSourceW > $iMaxW ))
		{
			$iRatioH = $iMaxH/$iSourceH;
			$iRatioW = $iMaxW/$iSourceW;
			if ($iRatioH<$iRatioW) $iRatio = $iRatioH ;
			else $iRatio = $iRatioW ;
			// Nouvelle dimention
			$iNewW = $iRatio*$iSourceW ;
			$iNewH = $iRatio*$iSourceH ;
			
			// Redimentionne
			$oSource = imagecreatefromjpeg($sSourcePath);
			$oNew = imagecreatetruecolor(round($iNewW), round($iNewH));
			imagecopyresampled($oNew, $oSource, 0, 0, 0, 0, $iNewW, $iNewH, $iSourceW, $iSourceH);
			
			// Créer la nouvelle image et supprime l'ancienne
			imagejpeg($oNew, PICS_PATH . '/' . $sFolder . '/_resize_' . $sFile);
			imagedestroy($oNew);
			unlink($sSourcePath);
			rename(PICS_PATH . '/' . $sFolder . '/_resize_' . $sFile, $sSourcePath);
		}
		
	}
}