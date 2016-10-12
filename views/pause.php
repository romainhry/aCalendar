<div class="create">
	<h2 class="text-center">Mise en pause de l'activité</h2>

<form method="post" action="<?=BASEURL?>/index.php/activite/add_pause/<?=$idActivite?>/<?=$idUser?>">
	<div class="formline">
	<label for="datedebpause">Début de la pause</label>
	<input type="datetime-local" name="datedebpause">
	</div>
	<div class="formline">
	<label for="datefinpause">Fin de la pause</label>
	<input type="datetime-local" name="datefinpause">
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
		<input type="submit" value="Valider">
	</div>
</form>
</div>
