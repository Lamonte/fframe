<form method="post" action="?submit">
	<div id="appform">
		
		<?php if(isset($message)): ?>
		<div class="message"><?php echo $message; ?></div>
		<?php endif; ?>	

		<div class="formrow">
			<label>Forum Name</label>
			<input type="text" name="name" maxlength="100" />
		</div><!-- end .formrow -->

		<div class="formrow">
			<label>Forum User Permissions</label>
			<table cellspacing="2" cellpadding="2">
				<?php if(count($rolesArray) > 0): ?>

				<tr>
					<td>&nbsp;</td>
					<?php foreach($rolesArray as $id => $role): ?>

					<td style="text-align: center; width: 150px;"><?php echo ucfirst($role); ?></td>
					<?php endforeach; ?>

				</tr>

				<?php foreach($permInputs as $perm => $roles): ?>

				<tr>
					<td><?php echo ucfirst($perm); ?></td>
					<?php foreach($roles as $role): ?>

					<td style="text-align: center"><input type="checkbox" name="<?php echo $perm; ?>[]" value="<?php echo $role[0]; ?>" /></td>
					<?php endforeach; ?>

				</tr>
				<?php endforeach; ?>

				<?php endif; ?>

			</table>

		</div><!-- end .formrow -->
	
		<div class="formrow">
			<input type="submit" name="submit" value="Add Forum" />
		</div><!-- end .formrow -->
	</div><!-- end #appform -->
</form>