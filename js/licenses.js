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

			// var users = JSON.parse(response);

			// var usersDiv = document.getElementById("users");
			// usersDiv.innerHTML =
			// 	"<table>" +
			// 		"<tr>" +
			// 			"<th>Id</th>" +
			// 			"<th>Username</th>" +
			// 			"<th>Action</th>" +
			// 		"</tr>"
			// ;
			
			// for ( var i = 0; i < users.length; i++ ) {
			// 	var user = users[i];
			// 	usersDiv.innerHTML +=
			// 		"<tr>" +
			// 			"<td>" + user.id + "</td>" +
			// 			"<td>" + user.username + "</td>" +
			// 			"<td><a href = \"#\" onclick = \"removeUser(" + user.id ")\">Remove</a></td>" +
			// 		"</tr>"
			// 	;
			// }

			// usersDiv.innerHTML += "</table>";


		}
	}

}
