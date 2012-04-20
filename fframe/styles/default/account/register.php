<form method="post" action="?submit">
	<div id="appform">
		
		<?php if(isset($errors)): ?>
		<div class="errors"><?php print_r($errors); ?></div>
		<?php endif; ?>	

		<div class="formrow">
			<label>Username</label>
			<input type="text" name="username" />
		</div><!-- end .formrow -->

		<div class="formrow">
			<label>Password</label>
			<input type="password" name="password" />
		</div><!-- end .formrow -->
		
		<div class="formrow">
			<label>Password (again)</label>
			<input type="password" name="password_again" />
		</div><!-- end .formrow -->
		
		<div class="formrow">
			<input type="submit" name="submit" value="Register" />
		</div><!-- end .formrow -->
	</div><!-- end #appform -->
</form>