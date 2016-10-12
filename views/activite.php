<li>
  <div class="activite" style="    width: 50%;
    border-right: dotted gray;
    text-align: center;">
    <h2>Aperçu de l'activité</h2>
    <p>Titre de l'activité : <? echo $activite->titre(); ?></p>
    <p>Descriptif : <? echo $activite->descriptif(); ?></p>
    <p>Aura lieu le : <?
      $format = 'Y-m-d H:i:s';
      $date = DateTime::createFromFormat($format, $activite->dateDeb());
      echo $date->format('Y-m-d');?></p>
    <p> A: <? $date = DateTime::createFromFormat('Y-m-d H:i:s', $activite->dateDeb());
      echo $date->format('H').' heures';?></p>
    <p>Jusqu'au : <?
      $date = DateTime::createFromFormat('Y-m-d H:i:s', $activite->dateFin());
      echo $date->format('Y-m-d');
    ?></p>
    <p> A: <?
      $date = DateTime::createFromFormat('Y-m-d H:i:s', $activite->dateFin());
      echo $date->format('H').' heures';?>
    </p>
    <? if($activite->occurences()>0)
      {
          if($activite->periodicite()=="S")
          {
            ?>
            <p>Toutes les semaines (sauf exception) pendant <? $activite->occurences(); ?> semaines</p>
            <?
          }
          else if($activite->periodicite()=="J")
          {
            ?>
            <p>Tous les jours (sauf exception)) pendant <? $activite->occurences(); ?> jours</p>
            <?
          }
          else if($activite->periodicite()=="M")
          {
            ?>
            <p>Tous les mois (sauf exception) pendant <? $activite->occurences(); ?> mois</p>
            <?
          }
      }
    ?>
    <p>Date de creation : <? echo date("d-m-y",$activite->dateCreation()); ?></p>
    <p>Dernière modification : <? echo date("d-m-y",$activite->dateUpdate()); ?></p>
    <form method="post" action="<?=BASEURL?>/index.php/activite/pause/<?=$activite->idActivite()?>/<?=$user->idUtilisateur()?>">
        <div class="formline">
          <input type="submit" value="Mettre l'activité en pause">
        </div>
    </form>
  </div>
  <div class="commentaires" style="width: 50%;">
    <h2 style="text-align: center;">Les commentaires</h2>
    <form method="post" action="<?=BASEURL?>/index.php/activite/doCommentaire/<? echo $activite->idActivite();?>/NULL">
					<textarea name="contenu" style="width: 100%" id="contenu" class="question" rows = "2" cols = "20" placeholder = "Commentez moi" ></textarea>
        <div class="formline">
    			<input type="submit" value="Valider">
    		</div>
		</form>
    <div style="    margin: 2rem auto;
    padding: 2rem;
    border: 1px solid gray;
    width: 70%;">
