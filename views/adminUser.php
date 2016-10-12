<div class="create">
	<h2 class="text-center">Tous les utilisateurs</h2>
	<form method="post" style="text-align: center;">
    <?
    for($i=0;$i<count($u);$i++)
    {?>
    <label for="title" style="font-size: 2rem;
    margin-right: 4rem;
    color:rgb(200,240,200);
    font-weight: bold;"><?php echo $u[$i]->pseudo()?></label>

    <?
    if($u[$i]->admin()==0)
    {
    ?>
    <button type="submit" formaction="<?=BASEURL?>/index.php/user/supprimer/<?echo $u[$i]->idUtilisateur()?>">Supprimer cet utilisateur</button>
    <button type="submit" formaction="<?=BASEURL?>/index.php/user/admin/<?echo $u[$i]->idUtilisateur()?>">Donner le droit d'administrer</button>
    <?}
    else {
      echo 'est administrateur';
    }?>
    <br>
    <br>
    <?}?>
  </form>
</div>
