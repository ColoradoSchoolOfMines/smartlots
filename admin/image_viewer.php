<!DOCTYPE html>

<?php
    require 'templates/admin_header.php'
?>
        <script>
			function getImage() {
				var url = "http://acmxlabs.org/smartlots/images/";
				var urlInput = document.getElementById("url_input");
				url += urlInput.value;
				var imageDiv = document.getElementById("image_div");
				imageDiv.innerHTML = "<img src = \"" + url + "\">";
			}
		</script>
		<h1>Image Viewer</h1>
	    <label>http://acmxlabs.org/smartlots/images/</label>
	    <input id = "url_input">
	    <a href = "#" onclick = "getImage();">Go</a>
	    <br>
	    <div id = "image_div"></div>
	</form>
<?php
    require 'templates/admin_footer.php'
?>