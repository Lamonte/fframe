<a href="<?php echo get_url("admin/permissions/create"); ?>">Create Permission</a>

<div class="perms_container">
<?php if(isset($permissions) && !empty($permissions)): ?>

	<table colspan="2">
	<?php foreach($permissions as $perm): ?>
		<tr>
			<td><?php echo $perm['permname']; ?></td>
			<td><?php echo $perm['description']; ?></td>
			
			<?php if($perm['default'] != 1): ?>
			<td><a href="<?php echo get_url("admin/permissions/edit/" . $perm['id']); ?>">Edit</a></td>
			<td><a href="<?php echo get_url("admin/permissions/delete/" . $perm['id']); ?>">Delete</a></td>
			<?php endif; ?>
		</tr>
	<?php endforeach; ?>
	</table>
<?php else: ?>
	<em>Sorry, no permissions in database</em>
<?php endif; ?>

</div><!-- end .perms_container -->