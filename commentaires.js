var afficher=true;
var rows=0;

$(document).ready(function() {
	// actualisation des votes
	window.setInterval(afficherQuest, 20000);
});


function suprimerQuest(questID) {
	var r = confirm("Souhaitez vous vraiment supprimer cette question ?")
	if (r==true) {
		$.getJSON('session.php', {ctrl : 'question' , action : 'supprimer', data : questID.value}, function(data) {
					document.getElementById('q'+questID.value).innerHTML = '';
		});
		afficher=true;
	}
}

function suprimerRep(questID) {
	var r = confirm("Souhaitez vous vraiment supprimer cette reponse ?")
	if (r==true)
	{
		$.getJSON('session.php', {ctrl : 'reponse', action : 'supprimer', data : questID.value}, function(data) {
					document.getElementById('R'+questID.value).innerHTML = '';
		});
		afficher=true;
	}
}


function addQuest()
{
	$.getJSON('session.php', {ctrl : 'question' , action : 'ajouter', data : $("#contenu").val()}, function(data) {
				afficherQuest();
	});
	$("#contenu").val('');
				$("#contenu").focus();
				afficher=true;

				
}

function afficherQuest()
{
	if(afficher) {
	$.getJSON('controllers/afficher.php', function(data) {
		$("#questions").html(data['messages']);
				
	});
	}
}

function repondre(reponse){

	var elements = document.getElementsByClassName('reponse');
    afficher=false;
	elementsLength = elements.length; 
	for (var i = 0 ; i < elementsLength ; i++) {
 		$("#"+elements[i].id).slideUp("slow"); 
 		document.getElementById('q'+elements[i].id.substring(1,elements[i].id.length)).className="questionBlock";           
	}
	if($("#r"+reponse.value).is( ":hidden" )){
		document.getElementById('q'+reponse.value).className="questionBlock2";
		$("#r"+reponse.value).slideDown("slow");
		afficher=false;
	}
	else {
		$("#r"+reponse.value).slideUp("slow");
		document.getElementById('q'+reponse.value).className="questionBlock";
		afficher=true;
	}
}

function posterRep(e,rep) {
	if($("#"+rep.id).val().length>rep.cols*rep.rows)
	{
		rep.rows=rep.rows+1;
	}

	if($("#"+rep.id).val().length+70<rep.cols*rep.rows && rows<rep.rows)
	{
		rep.rows=rep.rows-1;
	}
	if (e.keyCode == 13) {
		if(!e.shiftKey && !e.ctrlKey && !e.altKey){
			e.preventDefault();
			$.getJSON('session.php', {ctrl : 'reponse', action : 'ajouter', data : $("#"+rep.id).val(), idQ : rep.id.substring(2,rep.id.length)}, function(data) {
			$("#"+rep.id).val('');
				rows=0;
				afficher=true;
				afficherQuest();	
		});
			
	}
		else{
			rep.rows=rep.rows+1;
			rows++;
		}
	}
}

function voteQuest(quest)
{
	$.getJSON('session.php', {ctrl : 'question', action : 'voteQuest', data : quest.value, val : 1}, function(data) {
		afficher=true;
				afficherQuest();
				
	});
	
}

function voteRep(rep)
{
	$.getJSON('session.php', {ctrl : 'reponse', action : 'voteRep', data : rep.value, val : 1}, function(data) {
				afficher=true;
				afficherQuest();
	});
	
}

function enleveVoteQuest(quest)
{
	$.getJSON('session.php', {ctrl : 'question', action : 'voteQuest', data : quest.value, val : -1}, function(data) {
			afficher=true;
				afficherQuest();	
	});
	
}

function enleveVoteRep(quest)
{
	$.getJSON('session.php', {ctrl : 'reponse', action : 'voteRep', data : quest.value, val : -1}, function(data) {
			afficher=true;
				afficherQuest();	
	});
	
}