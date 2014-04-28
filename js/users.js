function loadUsers() {

	var request = new XMLHttpRequest();

	request.open("GET", "/smartlots/users", true);
	request.send(null);

	request.onreadystatechange = function() {
		if ( request.readyState == 4 ) {

			var response = request.responseText;

			var users = JSON.parse(response);

			var userTable = document.getElementById("user_table");
			userTable.innerHTML =
				"<tr>" +
					"<th>Username</th>" +
					"<th>Action</th>" +
				"</tr>"
			;
			
			for ( var i = 0; i < users.length; i++ ) {
				var user = users[i];
				userTable.innerHTML +=
					"<tr>" +
						"<td>" + user.username + "</td>" +
						"<td><a class = \"deleteLink\" href = \"#\" onclick = \"deleteUser(" + user.id + ")\">Delete</a></td>" +
					"</tr>"
				;
			}
		}
	}

}

function deleteUser(id) {
	var request = new XMLHttpRequest();
	request.open("DELETE", "/smartlots/users/" + id, true);
	request.send(null);
	request.onreadystatechange = function() {
		location.reload();
	}
	
}




