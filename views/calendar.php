<li>
	<div class="myCal">
		<a href="<?=BASEURL?>/index.php/calendar/add_calendar/">+</a>
		<?php if(count($mes_agendas)>0)
		{?>
			<p> Mes Agendas </p>
		<?php
		}
		for($j=0; $j<count($mes_agendas); $j++) {
			if($j==$_SESSION['num'])
			{?>
					<a  class="but" style="color: #C8F0C8; font-size: 2rem; " id="<?=$j?>"><?php echo $mes_agendas[$j]->nom();?></a>
				<?php }else {?>
					<a  class="but" style="font-size: 2rem;" id="<?=$j?>"><?php echo $mes_agendas[$j]->nom();?></a>
			<?php } ?>
			<div class="propos" style="display: none;">
				<a style="font-size: 1.5rem;" href="<?=BASEURL?>/index.php/calendar/add_activite/<?=$j?>">Editer</a>
				<a style="font-size: 1.5rem;"href="<?=BASEURL?>/index.php/calendar/show_other_calendar/<?=$j?>">Voir</a>
			</div>
		<?php } if(count($abonnements)>0)
		{?>
			<p> Mes abonnements </p>
		<?php }
		for($j=0; $j<count($abonnements); $j++) {
			if($j+count($mes_agendas)==$_SESSION['num'])
			{?>
					<a  class="but" style="color: #C8F0C8; font-size: 2rem; " id="<?=$j+count($mes_agendas)?>"><?php echo $abonnements[$j]->nom();?></a>
				<?php }else {?>
					<a  class="but" style="font-size: 2rem;" id="<?=$j+count($mes_agendas)?>"><?php echo $abonnements[$j]->nom();?></a>
			<?php } ?>
			<div class="propos" style="display: none;">
				<a style="font-size: 1.5rem;"href="<?=BASEURL?>/index.php/calendar/show_other_calendar/<?=$j+count($mes_agendas)?>">Voir</a>
			</div>
		<?php } ?>
	</div>

	<script type="text/javascript">

		var hiddenBox = $( ".propos" );
		$( ".but" ).on( "click", function( event ) {
			hiddenBox.show("slow");
		});
	</script>

	<div class="actualCal">
		<h2>
			<?php echo $title; ?>
		</h2>
		<p>
			<?php echo $content; ?>
		</p>
		<p>
			<?php
			$jour = date("w");
			//echo 'jour courant : '.$jour;
			$heure = date("H");

			$jourTexte = array('',1=>'lun.', 'mar.', 'mer.', 'jeu.', 'ven.', 'sam.', 'dim.');
			$plageH = array(1=>'00:00','01:00','02:00','03:00','04:00','05:00','06:00',
				'07:00','08:00','09:00','10:00','11:00', '12:00', '13:00', '14:00', '15:00',
				'16:00', '17:00', '18:00', '19:00', '20:00','21:00','22:00','23:00'
		 	);

			$nom_mois = date('F', mktime(0,0,0,$_SESSION['mois'],$_SESSION['jour'],$_SESSION['annee']));

			switch($nom_mois)
			{
			    case 'January' : $nom_mois = 'Janvier'; break;
			    case 'February' : $nom_mois = 'Février'; break;
			    case 'March' : $nom_mois = 'Mars'; break;
			    case 'April' : $nom_mois = 'Avril'; break;
			    case 'May' : $nom_mois = 'Mai'; break;
			    case 'June' : $nom_mois = 'Juin'; break;
			    case 'July' : $nom_mois = 'Juillet'; break;
			    case 'August' : $nom_mois = 'Août'; break;
			    case 'September' : $nom_mois = 'Septembre'; break;
			    case 'October' : $nom_mois = 'Octobre'; break;
			    case 'November' : $nom_mois = 'Novembre'; break;
			    case 'December' : $nom_mois = 'Décembre'; break;
			}


			echo '<br/>
			<div id="titreMois">
			    <strong>'.$nom_mois.' '.date('Y', mktime(0,0,0,$_SESSION['mois'],$_SESSION['jour'],$_SESSION['annee'])).'</strong>
					<br/>
			</div>';

			?>
			<div class="text-right">
			    <a href="<?=BASEURL?>/index.php/calendar/actualise_date_moins"><<</a> <a href="<?=BASEURL?>/index.php/calendar/actualise_date_maintenant">Aujourd'hui</a> <a href="<?=BASEURL?>/index.php/calendar/actualise_date_plus">>></a>
			</div>
			<?php


			echo '<table border="1" class="cal">';

			    // en tête de colonne
			    echo '<tr>';
			    for($k = 0; $k < 8; $k++)
			    {
			        if($k==0)
			            echo '<th>'.$jourTexte[$k].'</th>';
			        else
								if($k==$jour && date("U", mktime(0,0,0,$_SESSION['mois'],$_SESSION['jour'],$_SESSION['annee'])) == date("U", mktime(0,0,0,date('n'),date('j'),date('y'))))
			            echo '<th><div style="color: #C8F0C8;font-size: small;">'.$jourTexte[$k].' '.date("d", mktime(0,0,0,$_SESSION['mois'],$_SESSION['jour']-$jour+$k,$_SESSION['annee'])).'</div></th>';
									else {
										if($k==7 || $k==6)
											echo '<th><div style="color: gray;font-size: small;">'.$jourTexte[$k].' '.date("d", mktime(0,0,0,$_SESSION['mois'],$_SESSION['jour']-$jour+$k,$_SESSION['annee'])).'</div></th>';
										else {
											echo '<th><div style="font-size: small;">'.$jourTexte[$k].' '.date("d", mktime(0,0,0,$_SESSION['mois'],$_SESSION['jour']-$jour+$k,$_SESSION['annee'])).'</div></th>';
										}
									}

			    }
			    echo '</tr>';

			    // les 2 plages horaires : matin - midi
			    for ($h = 1; $h < 25; $h++)
			    {
					if($h==$heure+1) {
						echo '<tr>
						<th>
								<div style="color: #C8F0C8;font-size: small;">'.$plageH[$h].'</div>
						</th>';
					} else {
						echo '<tr>
				        <th>
				            <div style="color: gray;font-size: small;">'.$plageH[$h].'</div>
				        </th>';
					}

			        // les infos pour chaque jour
		            for ($j = 0; $j < 7; $j++)
		            {
						if($j==$jour-1 && $h==$heure+1 && date("U", mktime(0,0,0,$_SESSION['mois'],$_SESSION['jour'],$_SESSION['annee'])) == date("U", mktime(0,0,0,date('n'),date('j'),date('y'))))
						{

							if(!is_null($heure_jour[$h-1][$j])) {
								if(!is_null($heure_jour[$h-2][$j]) && $heure_jour[$h-2][$j]->titre()==$heure_jour[$h-1][$j]->titre())
								{

								}
								else {
									$r = rand(1,4);
								}
								if($r == 1) { $color = '#0000FF'; }
								else if($r == 2) { $color = '#FF0033'; }
								else if($r == 3) { $color = '#FFFF33'; }
								else { $color = '#00CC00'; }

								echo '<td style="background-color: '.$color.';">';
								echo '<a href="'.BASEURL.'/index.php/activite/show/'.$heure_jour[$h-1][$j]->idActivite().'">'.$heure_jour[$h-1][$j]->titre().'</a>';
							} else {
								echo '<td style="background-color: #C8F0C8;">';
							}
							echo '</td>';
						}
						else {
							if($j==5 || $j==6){
								if(!is_null($heure_jour[$h-1][$j])) {

									if(!is_null($heure_jour[$h-2][$j]) && $heure_jour[$h-2][$j]->titre()==$heure_jour[$h-1][$j]->titre())
									{

									}
									else {
										$r = rand(1,4);
									}
									if($r == 1) { $color = '#0000FF'; }
									else if($r == 2) { $color = '#FF0033'; }
									else if($r == 3) { $color = '#FFFF33'; }
									else { $color = '#00CC00'; }

									echo '<td style="background-color: '.$color.';">';
									echo '<a href="'.BASEURL.'/index.php/activite/show/'.$heure_jour[$h-1][$j]->idActivite().'">'.$heure_jour[$h-1][$j]->titre().'</a>';
								} else {
									echo '<td style="background-color: rgb(228, 228, 228);">';
								}
								echo '</td>';
							}
							else {
								if(!is_null($heure_jour[$h-1][$j])) {

									if(!is_null($heure_jour[$h-2][$j]) && $heure_jour[$h-2][$j]->titre()==$heure_jour[$h-1][$j]->titre())
									{

									}
									else {
										$r = rand(1,4);
									}
									if($r == 1) { $color = '#0000FF'; }
									else if($r == 2) { $color = '#FF0033'; }
									else if($r == 3) { $color = '#FFFF33'; }
									else { $color = '#00CC00'; }

									echo '<td style="background-color: '.$color.';">';
									echo '<a href="'.BASEURL.'/index.php/activite/show/'.$heure_jour[$h-1][$j]->idActivite().'">'.$heure_jour[$h-1][$j]->titre().'</a>';
								} else {
									echo '<td>';
								}
								echo '</td>';
							}
      					}
					}
					echo '</td>
					</tr>';
			    }
			echo '</table>';
			?>
		</p>

	</div>
</li>
