$(document).ready(function(){
	/*
	 * Innitialise toutes les variables
	 */
	$("#_ajaxLoad_Content").dialog({ bgiframe: true, autoOpen: false, modal: true, draggable: false, resizable: false, title: '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Chargement ...' });
	iPageType = parseInt('1') ; // iPageType = 1 pour galerie et 2 pour sous galerie
	iPageNum = parseInt('1') ; // iPageNum = page à afficher
	iOldPageNum = parseInt('1') ;
	iDisplayW = parseInt( $('#_GalerieContent').css('width') ); // récupére la longueur de la zone d'affichage des vignettes
	iDisplayH = parseInt( $('#_GalerieContent').css('height') ); // récupére la hauteur de la zone d'affichage des vignettes
	iVignetteW = parseInt( $('._GalerieVignette').css('width') ); // longueur en pixel d'une vignette
	iVignetteH = parseInt( $('._GalerieVignette').css('height') ); // hauteur en pixel d'une vignette
	iSvignetteW = parseInt('216'); // longueur en pixel d'une sous-vignette
	iSvignetteH = parseInt('177'); // hauteur en pixel d'une sous-vignette
	sGalerieId = '0'; // Id de la galerie actuellement affiché
	iAjax = 0 ; // Statu de requette Ajax
	/*
	 * Calcule les positions des vignette Galerie pour un affichage de 3 et pour un affichage de 6
	 */
	iVignetteStartLeft = parseInt( ( iDisplayW - (iVignetteW*3) ) / 4 ); // Définie la marge de gauche
	iVignetteStartTop3 = parseInt( ( iDisplayH - iVignetteH ) / 2 ); // Définie la marge top pour le cas de 3 vignettes
	iVignetteStartTop6 = parseInt( ( iDisplayH - (iVignetteH*2) ) / 3 ); // Définie la marge top pour le cas de 6 vignettes
	/*
	 * Calcule les positions des vignette de Sous Galerie pour un affichage de 3 et pour un affichage de 6
	 */
	iSvignetteStartLeft = parseInt( ( iDisplayW - (iSvignetteW*4) ) / 5 ); // Définie la marge de gauche
	iSvignetteStartTop = parseInt( ( iDisplayH - (iSvignetteH*3) ) / 4 ); // Définie la marge top pour le cas de 3 vignettes
	// innitialise les boutons
	ajaxLoading(0);
	initGalerieBtn();
	// demande l'affichag des vignette
	getVignettes();
});
function initGalerieBtn()
{	
	// Bouton pour entrer dans une galerie
	$('#_BackBtn A').click(function(){ backGalerie(); });
	$('._GalerieVignetteImg').click(function(){ openGalerie($(this).parent().parent().attr('id'), 1); });
	// Bouton suivant précédent
	$('#_ArrowL').click(function(){ prevPage();});
	$('#_ArrowR').click(function(){ nextPage();});
}

/*
 * Affiche / Cache l'icone de chargement ajax
 */
function ajaxLoading( opt )
{
	if (opt==0)
	{
		//$('#_ajaxLoad').css( 'display', 'none' );
		$('#_ajaxLoad_Content').dialog('close');
	}
	else
	{
		//$('#_ajaxLoad').css( 'display', 'block' );
		$('#_ajaxLoad_Content').dialog('open');
	}
}

function backGalerie()
{
	iPageType = 1 ;
	iPageNum = iOldPageNum ;
	getVignettes();
	$('#_BackBtn').fadeOut();
}

function openGalerie(id, iPage)
{
	iPageType = 2 ;
	iOldPageNum = iPageNum ;
	iPageNum = iPage ;
	sGalerieId = id ;
	getVignettes();
	$('#_BackBtn').fadeIn();
}

function hideGalerieContent()
{
	$('#_GalerieContent').html('');
}

function displayGalerieContent()
{
	$('#_GalerieContent').ready(function(){
		$('#_GalerieContent ._SgalerieVignette').each(function(){ $(this).fadeIn(); });
		$('#_GalerieContent ._GalerieVignette').each(function(){ $(this).fadeIn(); });
		$('._SgalerieVignetteImg').lightBox({
			overlayBgColor: '#000',
			overlayOpacity: 0.4,
			imageLoading: sBaseurl + 'public/img/lightbox/lightbox-ico-loading.gif',
			imageBtnClose: sBaseurl + 'public/img/lightbox/lightbox-btn-close.gif',
			imageBtnPrev: sBaseurl + 'public/img/lightbox/lightbox-btn-prev.gif',
			imageBtnNext: sBaseurl + 'public/img/lightbox/lightbox-btn-next.gif',
			imageBlank: sBaseurl + 'public/img/lightbox/lightbox-blank.gif',
			containerResizeSpeed: 350,
			txtImage: 'Photo',
			txtOf: 'sur'
		});
	});
}

function getVignettes()
{
	/*
	 * Demande au serveur la liste des vignettes à afficher,
	 * le serveur renvoie la liste des vignettes de la galerie
	 * ou de la sous galerie en fonction de la var iPageType
	 */
	hideGalerieContent();
	ajaxLoading(1);
	$.ajax({
		url: sBaseurl + 'galeries/getvignettes',
		async: false,
		type: "POST",
		data: ({ iPageType: iPageType, iPageNum: iPageNum, sGalerieId: sGalerieId }),
		success: function(data) { aVignette = eval( '(' + data + ')' ); makeHtml(aVignette); },
		error: function(){ ajaxError(); }
	});
}

function makeHtml(aVignette)
{
	/*
	 * redirige la requette vers la fonction dédié au galerie ou au sous galerie
	 * s'occupe également de masker l'icone de chargement ajax apres retour
	 * de la fonction dédié
	 */
	
	if (iPageType=='1')
		makeHtmlGalerie(aVignette['pics']);
	else
		makeHtmlSousGalerie(aVignette['pics']);
	initArrow(aVignette['prev'], aVignette['next']);
	ajaxLoading(0);
	displayGalerieContent();
}

function initArrow(iPrev, iNext)
{
	// Configure l'affichage des fléches
	if (iPrev==1)
	{
		$('#_ArrowL').fadeIn();
		
	}
	else $('#_ArrowL').fadeOut();
	if (iNext==1)
	{
		$('#_ArrowR').fadeIn();
		
	}
	else $('#_ArrowR').fadeOut();
}

function prevPage()
{
	iPageNum--;
	getVignettes();
}

function nextPage()
{
	iPageNum++;
	getVignettes();
}

function makeHtmlGalerie(aVignette)
{
	// Clone x fois la div _CloneGalerieVignette pour chaque entré du tableau aVignette
	iTotalVignette = 0 ;
	for( iKey in aVignette )
	{
		/*
		 * Construit les vignettes
		 */
		iTotalVignette++;
		aInfo = aVignette[iKey] ;
		$("#_CloneGalerieVignette").clone(true).attr("id", aInfo['id']).appendTo("#_GalerieContent");
		makeHtmlGalerie_setTitre('#' + aInfo['id'], aInfo['titre']);
		$('#'+aInfo['id']+' ._GalerieVignetteLink').css("background-image", "url("+aInfo['vignette']+")").css("background-repeat", "no-repeat").css("background-position", "18px 5px");
	}
	/*
	 * Positionne et fais apparaitre chaque clone 
	 */
	if (iTotalVignette<4)
	{
		// Pour 1 à 3 Vignettes
		$('#_GalerieContent ._GalerieVignette').each(function(iCount){
			i = iCount + 1 ;
			$(this).css('left', ( iVignetteStartLeft * i ) + ( iCount * iVignetteW ) );
			$(this).css('top', iVignetteStartTop3 );
		});
	}
	else
	{
		// Pour 4 à 6 Vignettes
		$('#_GalerieContent ._GalerieVignette').each(function(iCount){
			i = iCount + 1 ;
			if (iCount<3) // (iCount démare à 0 donc 2 = 3eme vignette)
			{
				// Les 3 première vignette
				$(this).css('left', ( iVignetteStartLeft * i ) + ( iCount * iVignetteW ) );
				$(this).css('top', iVignetteStartTop6 );
			}
			else
			{
				// Les 3 dernière vignette
				$(this).css('left', ( iVignetteStartLeft * ( i - 3 ) ) + ( ( iCount - 3 ) * iVignetteW ) );
				$(this).css('top', ( iVignetteStartTop6 * 2 ) + iVignetteH );
			}
		});
	}
}

function makeHtmlSousGalerie(aVignette)
{
	// Clone x fois la div _CloneGalerieVignette pour chaque entré du tableau aVignette
	for( iKey in aVignette )
	{
		/*
		 * Construit les vignettes
		 */
		aInfo = aVignette[iKey] ;
		$("#_CloneSgalerieVignette").clone(true).attr("id", aInfo['id']).appendTo("#_GalerieContent");
		$('#'+aInfo['id']+' ._SgalerieVignetteLink').css("background-image", "url("+aInfo['thumbPath']+")").css("background-repeat", "no-repeat").css("background-position", "10px 10px");
		$('#'+aInfo['id']+' ._SgalerieVignetteImg').attr('href', aInfo['imgPath']);
	}
	/*
	 * Positionne et fais apparaitre chaque clone 
	 */
	iX = 1 ;
	iY = 0 ;
	$('#_GalerieContent ._SgalerieVignette').each(function(iCount){
		$(this).css('left', (iSvignetteStartLeft * iX) + ((iX-1)*iSvignetteW) );
		$(this).css('top', (iSvignetteStartTop+(iY*iSvignetteStartTop)) + (iSvignetteH*iY) );
		// Détermine la position de la vignette suivante
		if (iX==4) { iX = 1 ; iY++ ; }
		else iX++;
	});
}

function makeHtmlGalerie_setTitre(id, titre)
{
	// Régle le titre de la vignette de galerie indiqué par la var id
	if (titre.lastIndexOf('.png')>0)
		$(id + ' ._GalerieVignetteTitre IMG').attr('src', titre);
	else
		$(id + ' ._GalerieVignetteTitre').html('<p>'+titre+'</p>');
}