<form method="post" action="?submit">
	<div id="appform">
		
		<?php if(isset($message)): ?>
		<div class="message"><?php echo $message; ?></div>
		<?php endif; ?>	

		<div class="formrow">
			<label>Permission Name (alphanumeric & underscores)</label>
			<input type="text" name="name" maxlength="40" />
		</div><!-- end .formrow -->

		<div class="formrow">
			<label>Permission Description</label>
			<input type="text" name="desc" maxlength="140" />
		</div><!-- end .formrow -->
	
		<div class="formrow">
			<input type="submit" name="submit" value="Add New Permission" />
		</div><!-- end .formrow -->
	</div><!-- end #appform -->
</form>