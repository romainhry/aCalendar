<div class="create">
  <h2 class="text-center">Mon compte</h2>

<form method="post" action="<?=BASEURL?>/index.php/user/change_user/?>">
	<div class="formline">
		<label for="title">Nom</label>
		<input type="text" id="nom" name="nom" value="<?php if(NULL !=$u->nom()) echo htmlspecialchars($u->nom()); ?>">
	</div>
  <div class="formline">
		<label for="title">Prenom</label>
		<input type="text" id="prenom" name="prenom" value="<?php if(NULL !=$u->prenom()) echo htmlspecialchars($u->prenom()); ?>">
	</div>
  <div class="formline">
		<label for="title">Adresse</label>
		<input type="text" id="adresse" name="adresse" value="<?php if(NULL != $u->adresse()) echo htmlspecialchars($u->adresse()); ?>">
	</div>
	<div class="formline">
		<label for="content">Email</label>
		<input type="text" id="email" name="email" rows="8" value="<?php if(NULL !=$u->email()) echo htmlspecialchars($u->email()); ?>">
	</div>
  <div class="formline">
    <label for="pw">Inserer votre mot de passe pour valider</label>
    <input type="password" id="mdp" name="mdp">
  </div>
	<div class="formline">
		<input type="submit" value="Edit">
	</div>
</form>
</div>
