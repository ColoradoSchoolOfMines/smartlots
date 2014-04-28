<?php
	require 'templates/admin_header.php'
?>
	
		<h1>Create a User Account</h1>
		<form action = "/smartlots/users" method = "post">
			<table id = "create_user_table">
				<tr>
					<td>
						<label>Username: </label>
					</td>
					<td>
						<input name = "username" required>
					</td>
				</tr>
				<tr>
					<td>
						<label>Password: </label>
					</td>
					<td>
						<input name = "password" type = "password" required><br>
					</td>
				</tr>
				<tr>
					<td>
						<label>Confirm Password: </label>
					</td>
					<td>
						<input name = "confirm_password" type = "password" required><br>
					</td>
				</tr>
				<tr>
					<td colspan = 2 style = "text-align: center;">
						<input type = "submit">
					</td>
				</tr>
			</table>
		</form>
<?php
	require 'templates/admin_footer.php'
?>