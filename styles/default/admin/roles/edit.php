<a href="<?php echo get_url("admin/roles"); ?>">Manage Roles</a>

<div class="perms_container">
<form method="post" action="<?php echo get_url('admin/roles/edit/' . $role['id'] . '?submit'); ?>">
	
	<?php if(isset($message)): ?>
	<div class="message">
		<?php echo $message; ?>
	</div>
	<?php endif; ?>

	<div class="formrow">
		<label>Role name</label>
		<input type="text" name="rolename" value="<?php echo $role['rolename']; ?>" />
	</div><!-- end .formrow -->
	<div class="formrow">
		<input type="submit" name="submit" value="Update" />
	</div><!-- end .formrow -->
</form>
</div><!-- end .perms_container -->