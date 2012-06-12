<a href="<?php echo get_url("admin/roles/create"); ?>">Create Role</a>

<div class="perms_container">
<?php if(isset($roles) && !empty($roles)): ?>

	<table colspan="2">
	<?php foreach($roles as $role): ?>
		<tr>
			<td><?php echo $role['rolename']; ?></td>
			<td><a href="<?php echo get_url("admin/roles/edit/" . $role['id']); ?>">Edit</a></td>
			<td><a href="<?php echo get_url("admin/roles/assign/" . $role['id']); ?>">Manage Permissions</a></td>
			
			<?php if($role['default'] != 1): ?>
			<td><a href="<?php echo get_url("admin/roles/delete/" . $role['id']); ?>">Delete</a></td>
			<?php endif; ?>

		</tr>
	<?php endforeach; ?>
	</table>
<?php else: ?>
	<em>Sorry, no roles in database</em>
<?php endif; ?>

</div><!-- end .perms_container -->