<?php require_once 'models/abonnement.php'; ?>
<div class="create">
<h2 class="text-center">Mes abonnements</h2>

<form method="post" action="<?=BASEURL?>/index.php/share/search">
	<label for="recherche">Rechercher les agendas d'un autre utilisateur</label>
	<input type="text" id="recherche" name="recherche">
	<input type="submit" value="Rechercher">
</form>

<form method="post" action="<?=BASEURL?>/index.php/share/search_by_name">
	<label for="nom">Rechercher les agendas par nom</label>
	<input type="text" id="nom" name="nom">
	<input type="submit" value="Rechercher">
</form>

<form method="post" action="<?=BASEURL?>/index.php/share/search_by_desc">
	<label for="desc">Rechercher les agendas par un mot clef du descriptif</label>
	<input type="text" id="desc" name="desc">
	<input type="submit" value="Rechercher">
</form>


<ul>
<?php for($i=0; $i<count($agendas); $i++) { ?>
	<li>
		<div style="display:flex; flex-direction: column; text-align:center; width: 50%">
		<h3><?=$agendas[$i]->nom()?></h3>
		<p>Descriptif: <?=$agendas[$i]->description()?></p>
	</div>
			<?php if(Abonnement::exist($moi->idUtilisateur(), $agendas[$i]->idAgenda())) { ?>
			<form method="post" style="width: 18.2rem;" action="<?=BASEURL?>/index.php/share/desabonnement/<?=$agendas[$i]->idAgenda()?>/<?=$agendas[$i]->nom()?>">
				<input type="submit" value="Je me désabonne">
			</form>
			<?php } else { ?>
			<form method="post" style="width: 15rem;" action="<?=BASEURL?>/index.php/share/abonnement/<?=$agendas[$i]->idAgenda()?>/<?=$agendas[$i]->nom()?>">
				<input type="submit" value="Je m'abonne">
			</form>
			<?php } ?>
	</li>
<?php } ?>
</ul>
</div>
