<h2 class="text-center">Edit note</h2>

<form method="post" action="<?=BASEURL?>/index.php/note/update_note/<?php echo $idNote; ?>">
	<div class="formline">
		<label for="title">Title</label>
		<input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>">
	</div>
	<div class="formline">
		<label for="content">Text</label>
		<textarea type="text" id="content" name="content" rows="8"><?php echo htmlspecialchars($text); ?></textarea>
	</div>
	<div class="formline">
		<input type="submit" value="Edit">
	</div>
</form>

<?php if($_SESSION['user'] == $autor) { ?>
<form method="post" action="<?=BASEURL?>/index.php/note/share_note/<?php echo $idNote; ?>">
	<div class="formline">
		<label for="sharedWith">Shared with</label>
		<input type="text" id="sharedWith" name="sharedWith" value="<?php echo htmlspecialchars($sharedWith); ?>">
	</div>
	<div style="margin-left:108px; margin-bottom:10px;">
		<span>Rights :</span>
		<input type="checkbox" id="updateChoice" name="updateChoice" value="1" checked> Update
		<input type="checkbox" id="deleteChoice" name="deleteChoice" value="2" checked> Delete
	</div>
	<div class="formline">
		<input type="submit" value="Update">
	</div>
</form>
<?php } ?>