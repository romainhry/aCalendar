<div class="create">
<h2 class="text-center">Création d'une nouvelle activité</h2>

<form method="post" action="<?=BASEURL?>/index.php/calendar/add_activite">

	<div class="formline">
		<label for="agenda">Calendrier</label>
		<select name="agenda" id="agenda">
		<?php for($j=0; $j<count($agendas); $j++) { ?>
			<option value="<?=$agendas[$j]->idAgenda()?>"><?=$agendas[$j]->nom()?></option>
		<?php } ?>
		</select>
	</div>

	<div class="formline">
		<label for="titre">Titre</label>
		<input type="text" id="titre" name="titre">
	</div>
	<div class="formline">
		<label for="description">Description</label>
		<textarea type="text" id="description" name="description" rows="8"></textarea>
	</div>
	<div class="formline">
	<label for="categorie">Catégorie</label>
	<select name="categorie" id="categorie">
		<option value="0"></option>
	<?php for($j=1; $j<=count($cat); $j++) { ?>
		<option value="<?=$cat[$j-1]->idCategorie()?>"><?=$cat[$j-1]->nom()?></option>
	<?php } ?>
	</select>
	</div>
	<div class="formline">
		<label for="location">Lieu</label>
		<input type="text" id="location" name="location">
	</div>
	<div class="formline">
	<label for="datedeb">Date de début</label>
	<input type="datetime-local" name="datedeb">
	</div>
	<div class="formline">
	<label for="datefin">Date de fin</label>
	<input type="datetime-local" name="datefin">
	</div>
	<div class="formline">
	<label for="periodicite">Périodicité</label>
    <select name="periodicite" id="periodicite">
    		<option value="N"></option>
           	<option value="J">Jour</option>
           	<option value="S">Semaine</option>
           	<option value="M">Mois</option>
           	<option value="A">Année</option>
	</select>
	</div>
	<div class="formline">
		<label for="occurences">Occurences</label>
		<input type="text" id="occurences" name="occurences">
	</div>
	<div class="formline">
	<label for="priorite">Priorité</label>
	<select name="priorite" id="priorite">
	<?php for($j=1; $j<=10; $j++) { ?>
		<option value="<?=$j?>"><?=$j?></option>
	<?php } ?>
	</select>
	</div>
	<div class="formline">
		<input type="submit" value="Create">
	</div>
</form>
</div>
