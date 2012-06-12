<?php if(isset($permissions) && !empty($permissions)): ?>
<form method="post" action="?submit">
	<?php foreach($permissions as $group => $permArray): ?>
	<div class="permgroup">
		<div class="permgrouptitle">
			<strong><?php echo $permGroupText[$group]; ?></strong>
		</div>

		<!-- permissions -->
		<?php foreach($permArray as $perm): ?>
		<div class="permwrap">
			<div class="permcheck">
				<input type="checkbox" name="perms[]" value="<?php echo $perm['permname']; ?>" <?php echo $perm['checked'] == true ? 'checked="checked"' : null; ?> />
				<?php echo $perm['description']; ?>
			</div>
		</div><!-- end .permwrap -->
		<?php endforeach; ?>	
		<!-- end permissions -->
	</div>
	<?php endforeach; ?>

	<div class="formsubmit">
		<input type="submit" name="submit" value="Update Permissions" />
	</div>
</form>
<?php endif; ?>