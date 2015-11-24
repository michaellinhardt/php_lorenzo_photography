<?php
$aSecurity = array() ;
/*
 * Parametre du mode PROTECT
 * 'white' = Autorise l'acces uniquement � certainne page
 * 'black' = Refuse l'acces uniquement � certainne page
 * La liste des page se trouve ci-dessous dans $SecurityConfig
 */
$aSecurity['PROTECT_MODE'] = 'white' ; // ( 'white' ou 'black' )
/*
 * Dans le cas d'une tentative d'acces � une page non autoris�, 
 * l'application renvera l'utilisateur vers ce couple 'controller/method'
 */
$aSecurity['CONNECT_URL'] = 'index/index' ; // controller/method permetant de se connecter, example: 'index/connection'
/*
 * Nom de la variable de session qui indique que l'user est connect�
 * Peut importe le systeme de connection utilis�, il faut mettre la variable indiqu�
 * sur true pour que l'application d�bloque l'acces � une page prot�g�
 * Exemple: si $aSecurity['IS_CONNECT_VAR_NAME'] = 'bConnected' ;
 * alors $_SESSION['bConnected'] dois �tre = � true, sinon l'applicatio
 * consid�re que vous n'�tes pas connect� et vous redirige vers 
 * le couple class/method contenu dans $aSecurity['CONNECT_URL']
 */
$aSecurity['IS_CONNECT_VAR_NAME'] = 'bConnected' ;
// Liste des controller (blanche ou noir en fonction du parametre $ProtectMode)
$aSecurity['PROTECT_LIST'] = array(
/*
 * Pour marquer un controller entier mettre 'ControllerName' => true
 * pour marquer certainne page d'un controller mettre 'ControllerName' => array('page1' => true, 'page2' => true, 'page3' => true);
 * NB: Cette liste fonctionne avec le parametre $aSecurity['PROTECT_MODE'] (liste blanche ou liste noir)
 * /!\ Ecrire exclusivement en minuscule !!!
 */
'index' => true ) ;