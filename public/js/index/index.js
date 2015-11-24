$(document).ready(function(){
	iPicsMaxWidth = parseInt( $('#_DefaultContent').css('width') ) - 50 ;
	iPicsMaxHeight = parseInt( $('#_DefaultContent').css('height') ) - 100 ;
	aPics = {} ;
	iInterval = 8000 ;
	displayNextPics();
});

function displayNextPics()
{
	// Récupére la photo et les dimention
	getNextPic();
	// Préload l'image
	preloadPic();
	// Redéfini la taille si besoin
	$('#_Slideshow_Preload').ready(function(){
		calcPicSize();
		displayPics();
		setTimeout("displayNextPics();", iInterval);
	});
}

function getNextPic()
{
	// Récupére l'url d'une photo au hasard
	$.ajax({
		url: sBaseurl + 'index/getslideshowpics',
		async: false,
		success: function(data) { aPics = eval( '(' + data + ')' ); },
		error: function(data){ ajaxError(); }
	});
}

function preloadPic()
{
	// Envoie l'image dans la div preload
	$('#_Slideshow_Preload').attr('src', aPics['url'] );
	
}

function calcPicSize()
{
	// Recalcul les dimention de l'image si besoin
	if ((aPics['height']>iPicsMaxHeight) || (aPics['width']>iPicsMaxWidth))
	{
		iRatioH = iPicsMaxHeight/aPics['height'];
		iRatioW = iPicsMaxWidth/aPics['width'];
		if (iRatioH<iRatioW) iRatio = iRatioH ;
		else iRatio = iRatioW ;
		// Nouvelle dimention
		aPics['width'] = parseInt(iRatio*aPics['width']);
		aPics['height'] = parseInt(iRatio*aPics['height']);
	}
	$('#_Slideshow_Preload').attr('width', aPics['width'] );
	$('#_Slideshow_Preload').attr('height', aPics['height'] );
}

function displayPics()
{
	/*
	 * Créer l'animation du diaporama
	 */
	// Récupére les paramétre
	iNewWidth = parseInt( $('#_Slideshow_Preload').attr('width') );
	iNewHeight = parseInt( $('#_Slideshow_Preload').attr('height') );
	iNewMarginTop = Math.round( ( (parseInt($('#_DefaultContent').css('height'))-30) - iNewHeight ) / 2 );
	// Masque la photo puis la remplace
	$('#_SlideShow').fadeOut(1000, function(){
		$('#_SlideShow').attr('src', $('#_Slideshow_Preload').attr('src'));
		$('#_SlideShow').attr('width', iNewWidth );
		$('#_SlideShow').attr('height', iNewHeight );
	});
	// Change la taille du background et régle les taille de la nouvelle photo
	$('#_SlideShow_MarginTop').animate({ height: iNewMarginTop }, 2000 );
	$('#_SlideShow_Background').animate({ width: iNewWidth, height: iNewHeight }, 2000 );
	// Fait réaparaitre la photo
	setTimeout( function(){ $('#_SlideShow').fadeIn(1000); }, 1000 );
}