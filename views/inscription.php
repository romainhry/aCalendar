<div class="create">
	<h2 class="text-center">Sign up</h2>
	<form method="post" action="<?=BASEURL?>/index.php/user/signup">
		<div class="formline">
			<label for="login">Login</label>
			<input type="text" id="login" name="login">
		</div>
		<div class="formline">
			<label for="pw">Password</label>
			<input type="password" id="pw" name="pw">
		</div>
		<div class="formline">
			<label for="pwConfirm">Confirm password</label>
			<input type="password" id="pwConfirm" name="pwConfirm">
		</div>
		<div class="formline">
			<input type="submit" value="Validate">
		</div>
	</form>
</div>
