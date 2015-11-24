<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//FR"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-15" />
		<title>Lorenzo Bianchi | Photographe</title>
		<link rel="shortcut icon" href="<?php echo IMG_HTTP; ?>favicon.gif" type="image/x-icon" />
		<link rev="start" href="<?php echo ROOT_HTTP; ?>" title="Accueil" />
		<link rel="stylesheet" type="text/css" href="<?php echo CSS_HTTP; ?>defaut.css">
		<link rel="stylesheet" type="text/css" href="<?php echo CSS_HTTP; ?>jqueryui.css">
		<link rel="stylesheet" type="text/css" href="<?php echo CSS_HTTP; ?>lightbox.css">
		<script type="text/javascript" src="<?php echo JS_HTTP; ?>jquery/jquery.js"></script>
		<script type="text/javascript" src="<?php echo JS_HTTP; ?>jquery/jqueryui.js"></script>
		<script type="text/javascript" src="<?php echo JS_HTTP; ?>jquery/lightbox.js"></script>
		<?php $this->head(); ?>
	</head>
	<body>
		<!-- DIV DU CONTENU -->
		<div id="_content">
			<!--  BOUTTON ACCUEIL -->
			<a href="<?php echo ROOT_HTTP; ?>" id="_accueilBtn"></a>
			<!-- IMG DE CHARGEMENT AJAX -->
			<div id="_ajaxLoad_Content"><img id="_ajaxLoad" src="<?php echo IMG_HTTP ; ?>ico/loading.gif" alt="Chargement" title="chargement" /></div>
			<!-- DIV DU MENU PRINCIPALE -->
			<div id="_menu">
				<!-- GESTION DES STYLE DU MENU -->
				<style type="text/css">
				<?php
				if ($_SESSION['class']=='Galeries') echo '#_btnMenu1 { background: url(./public/img/site/btn1.jpg) no-repeat; }' ;
				if ($_SESSION['class']=='Tirages') echo '#_btnMenu2 { background: url(./public/img/site/btn2.jpg) no-repeat; }' ;
				if ($_SESSION['class']=='Contact') echo '#_btnMenu3 { background: url(./public/img/site/btn3.jpg) no-repeat; }' ;
				if ($_SESSION['class']=='Cv') echo '#_btnMenu4 { background: url(./public/img/site/btn4.jpg) no-repeat; }' ;
				?>
				</style>
				<!-- MENU -->
				<a href="<?php echo ROOT_HTTP; ?>galeries" class="_btnMenu" id="_btnMenu1" alt="Galeries" title="Galeries"></a>
				<a href="<?php echo ROOT_HTTP; ?>tirages" class="_btnMenu" id="_btnMenu2" alt="Tirages" title="Tirages"></a>
				<a href="<?php echo ROOT_HTTP; ?>contact" class="_btnMenu" id="_btnMenu3" alt="Contact" title="Contact"></a>
				<a href="#" class="_btnMenu" id="_btnMenu4" alt="CV" title="CV"></a>
			</div>
			<?php $this->incView(); ?>
		</div>
	</body>
</html>