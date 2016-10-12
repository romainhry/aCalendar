<div class="create">
	<h2 class="text-center">Tous les commentaires</h2>
	<form method="post" style="text-align: center;">
    <?
    for($i=0;$i<count($c);$i++)
    {?>
    <label for="title" style="font-size: 2rem;
    margin-right: 4rem;
    color:rgb(200,240,200);
    font-weight: bold;"><?php echo $c[$i]->commentaire().' ('.$users[$i]->pseudo().')' ?></label>

    <button type="submit" formaction="<?=BASEURL?>/index.php/activite/delete_comm/<?echo $c[$i]->idComm()?>">Supprimer ce commentaire</button>
    <br>
    <br>
    <?php } ?> 
  </form>
</div>