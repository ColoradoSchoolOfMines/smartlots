function loadSensors() {
	var request = new XMLHttpRequest();

	request.open("GET", "/smartlots/sensors", true);
	request.send(null);

	request.onreadystatechange = function() {
		if ( request.readyState == 4 ) {

			var response = request.responseText;

			var sensors = JSON.parse(response);

			var sensorTable = document.getElementById("sensor_table");
			sensorTable.innerHTML =
				"<tr>" +
					"<th>ID</th>" +
					"<th>Lot</th>" +
					"<th>Current Count</th>" +
					"<th>Last Count</th>" +
					"<th>Battery</th>" +
				"</tr>"
			;
			
			for ( var i = 0; i < sensors.length; i++ ) {
				var sensor = sensors[i];
				sensorTable.innerHTML +=
					"<tr>" +
						"<td>" + sensor.id + "</td>" +
						"<td>" + sensor.lotname + "</td>" +
						"<td>" + sensor.carcount + "</td>" +
						"<td>" + sensor.lastcount + "</td>" +
						"<td>" + sensor.battery + "</td>" +
					"</tr>"
				;
			}
		}
	}
}