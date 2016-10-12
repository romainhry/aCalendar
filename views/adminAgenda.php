<div class="create">
	<h2 class="text-center">Tous les agendas</h2>
	<form method="post" style="text-align: center;">
    <?
    for($i=0;$i<count($a);$i++)
    {?>
    <label for="title" style="font-size: 2rem;
    margin-right: 4rem;
    color:rgb(200,240,200);
    font-weight: bold;"><?php echo $a[$i]->nom().' ('.$users[$i]->pseudo().')'?></label>
    <button type="submit" formaction="<?=BASEURL?>/index.php/calendar/supprimer/<?echo $a[$i]->idAgenda()?>">Supprimer cet agenda</button>
    <br>
    <br>
    <?}?>
  </form>
</div>
