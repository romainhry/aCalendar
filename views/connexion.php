<div class="create">
	<h2 class="text-center">Sign in</h2>

<form method="post" action="<?=BASEURL?>/index.php/user/signin">
	<div class="formline">
		<label for="login">Login</label>
		<input type="text" id="login" name="login">
	</div>
	<div class="formline">
		<label for="pw">Password</label>
		<input type="password" id="pw" name="pw">
	</div>
	<div class="formline">
		<input type="submit" value="Validate">
	</div>
</form>
<div style="width: 70%; margin: 2rem auto;">
<p>No account yet ? -> <a href="<?=BASEURL?>/index.php/user/signup">Sign up !</a><p>
<p>Or connect with -> <a href="<?=BASEURL?>/Connexion/index.php?logon=">CAS</a><p>
</div>
</div>
