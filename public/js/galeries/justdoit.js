$(document).ready(function(){
	startJustdoit();
});

function startJustdoit()
{
	$.ajax({
		url: sBaseurl + 'galeries/justdoitajax',
		async: false,
		success: function(data) { verifJustdoit(data); },
		error: function(){ ajaxError(); }
	});
}

function verifJustdoit(data)
{
	if (data=="1") { alert('Travail terminé !'); window.location.href = sBaseurl ; }
	else setTimeout("startJustdoit();", 1000);
}