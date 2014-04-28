
function loadLicenses(table) {

	var tablePrefix = "";

	if ( table == "intrada" ) {
		tablePrefix = "intrada_";
	}

	var request = new XMLHttpRequest();
	
	// We need to get the html for the overlayed page. We'll then insert this html
	// directly into the html of the directory, so we're not leaving the page to go
	// to a new one, but are rather staying on the same page.
	request.open("GET", "/smartlots/" + tablePrefix + "licenses", true);
	request.send(null);

	request.onreadystatechange = function() {
		if ( request.readyState == 4 ) {

			var response = request.responseText;

			var licenses = JSON.parse(response);

			var licenseTable = document.getElementById(tablePrefix + "license_table");
			licenseTable.innerHTML =
				"<tr>" +
					"<th>Number</th>" +
					"<th>State</th>" +
					"<th>Time</th>" +
					"<th>Lot</th>" +
					"<th>Image URL</th>" +
					//"<th>Action</th>" +
				"</tr>"
			;
			
			for ( var i = 0; i < licenses.length; i++ ) {
				var license = licenses[i];
				licenseTable.innerHTML +=
					"<tr>" +
						"<td><b>" + license.number + "</b></td>" +
						"<td>" + license.state + "</td>" +
						"<td>" + license.timestamp + "</td>" +
						"<td>" + license.lotname + "</td>" +
						"<td><a class = \"licenseImageUrl\" href = \"#\" onclick = \"displayImage('" + license.url + "')\">" + license.url + "</a></td>" +
						//"<td><a class = \"deleteLink\" href = \"#\" onclick = \"deleteLicense(" + license.id + ")\">Delete</a></td>" +
					"</tr>"
				;
			}
		}
	}

}

function displayImage(url) {
	var imageDiv = document.getElementById("image_viewer");
	imageDiv.style.display = "inline";
	imageDiv.innerHTML = "<img src = \"" + url + "\">";
	imageDiv.onclick = function() {
		imageDiv.style.display = "none";
	}
}

function setBackground(color) {
	var html = document.getElementsByTagName('html')[0];
	html.style.background = color;
}

