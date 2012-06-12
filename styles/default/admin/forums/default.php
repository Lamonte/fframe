<a href="<?php echo get_url("admin/forums/create"); ?>">Create Forum</a>

<div class="forums_container">
<?php if(isset($forums) && !empty($forums)): ?>

	<table colspan="2">
	<?php foreach($forums as $forum): ?>
		<tr>
			<td><?php echo $forum['forumName']; ?></td>
			
			<td><a href="<?php echo get_url("admin/forums/edit/" . $forum['forumId']); ?>">Edit</a></td>
			<td><a href="<?php echo get_url("admin/forums/delete/" . $forum['forumId']); ?>">Delete</a></td>
			<td><a href="<?php echo get_url("admin/forums/create/" . $forum['forumId']); ?>">Add Subforum</a></td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php else: ?>
	<em>Sorry, no forums in database</em>
<?php endif; ?>

</div><!-- end .perms_container -->