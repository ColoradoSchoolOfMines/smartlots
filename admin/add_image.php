<?php
    require 'templates/admin_header.php'
?>
		<h1>Add a new Image</h1>
		<form action="/smartlots/pidata" method="post" accept-charset="utf-8" enctype="multipart/form-data">
			<table id = "add_image_table">
				<tr>
					<td>Sensor ID:</td>
					<td><input name = "id" size = 30></td>
				</tr>
				<tr>
					<td>Image:</td>
					<td><input name = "image" type = "file" id = "image"></td>
				</tr>
				<tr>
					<td colspan = 2 style = "text-align: center;"><input type = "submit"></td>
				</tr>
			</table>
		</form>
		<br><br>
		<a href = "/smartlots/admin/view_licenses.php">View Licenses</a>
<?php
    require 'templates/admin_footer.php'
?>