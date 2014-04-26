function loadLicenses() {

	var request = new XMLHttpRequest();
	
	// We need to get the html for the overlayed page. We'll then insert this html
	// directly into the html of the directory, so we're not leaving the page to go
	// to a new one, but are rather staying on the same page.
	request.open("GET", "/smartlots/licenses", true);
	request.send(null);

	request.onreadystatechange = function() {
		if ( request.readyState == 4 ) {

			var response = request.responseText;

			alert(response);

			var licenses = JSON.parse(response);

			var licenseTable = document.getElementById("license_table");
			licenseTable.innerHTML =
				"<tr>" +
					"<th>Number</th>" +
					"<th>State</th>" +
					"<th>Image url</th>" +
				"</tr>"
			;
			
			for ( var i = 0; i < licenses.length; i++ ) {
				var license = licenses[i];
				licenseTable.innerHTML +=
					"<tr>" +
						"<td>" + license.number + "</td>" +
						"<td>" + license.state + "</td>" +
						"<td>" + license.url + "</td>" +
					"</tr>"
				;
			}
		}
	}

}
