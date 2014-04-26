<!DOCTYPE html>

<?php
    require 'authorization.php'
?>

<html>
	<head profile="http://www.w3.org/2005/10/profile">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="icon" type="image/png" href="images/favicon.png">
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,100' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="/smartlots/css/style.css">
        <link rel="stylesheet" type="text/css" href="/smartlots/css/admin.css">
        <script src="/smartlots/js/json2.js"></script>
        <script src="/smartlots/js/controller.js"></script>
        <script src="js/templates.js"></script>
        <title>Image Viewer</title>
        <script>
			function getImage() {
				var url = "http://acmxlabs.org/smartlots/images/";
				var urlInput = document.getElementById("url_input");
				url += urlInput.value;
				var imageDiv = document.getElementById("image_div");
				imageDiv.innerHTML = "<img src = \"" + url + "\">";
			}
		</script>
	<body>
	    <label>http://acmxlabs.org/smartlots/images/</label>
	    <input id = "url_input">
	    <a href = "#" onclick = "getImage();">Go</a>
	    <br>
	    <div id = "image_div"></div>
	</form>
	</body>
</html>