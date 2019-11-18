<form method="POST" enctype="multipart/form-data">
	<label class="label">Name:  <?php echo $_SESSION['user_name'] ?></label>
	<div class="field has-addons">
		<p class="control has-icons-left">
			<input class="input is-medium" type="text" name="new_name" placeholder="Choose a username" required/>
			<span class="icon is-small is-left">
				<i class="fas fa-user"></i>
			</span>
		</p>
		<div class="control" >
			<input class="button is-success is-medium" type="submit" name="updt_name" value="Update">
		</div>
	</div>
</form>
<form method="POST" enctype="multipart/form-data">
	<label class="label">Email:  <?php echo $_SESSION['user_email'] ?></label>
	<div class="field has-addons">
		<p class="control has-icons-left">
			<input class="input is-medium" type="email" name="new_email" placeholder="Enter an email address" required/>
			<span class="icon is-small is-left">
				<i class="fas fa-envelope"></i>
			</span>
		</p>
		<div class="control" >
			<input class="button is-success is-medium" type="submit" name="updt_email" value="Update">
		</div>
	</div>
</form>
<form method="POST" enctype="multipart/form-data">
	<label class="label">Password: </label>
	<div class="field has-addons">
		<p class="control has-icons-left">
			<input class="input is-medium" type="password" name="new_passwd" placeholder="Password" required/>
			<span class="icon is-small is-left">
				<i class="fas fa-lock"></i>
			</span>
		</p>
		<div class="control" >
			<input class="button is-success is-medium" type="submit" name="updt_passwd" value="Update">
		</div>
	</div>
</form>
<form method="POST" enctype="multipart/form-data">
	<label class="label">Profile Picture: </label>
	<div class="field has-addons">
		<div class="file has-name">
			<label class="file-label">
				<input class="input is-medium" type="file" name="new_image" placeholder="" required/>
			</label>
		</div>
		<div class="control" >
			<input class="button is-success is-medium" type="submit" name="updt_image" value="Update">
		</div>
	</div>
</form>
<br/>
<form method="POST">
	<div class="level">
		<div class="level-left">
			<div class="level-item">
				<label class='label'>Notifications: </label>
			</div>
			<div class="level-item">
				<input type="checkbox" name="notif" <?php if($_SESSION['notif'] == 1){echo "checked";} ?>>
			</div>
			<div class="level-item">
				<input class="button is-success is-medium" type="submit" name="updt_notif" value="Update">
			</div>
		</div>
	</div>
</form>
