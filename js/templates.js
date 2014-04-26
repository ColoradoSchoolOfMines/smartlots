function insertTemplates(pageName) {
	
	var header = document.getElementById('header');
	var menu = document.getElementById('menu');
	var miniLogo = document.getElementById('mini-logo');
    var loginDiv = document.getElementById('login_div');

	header.innerHTML =
		"<div id=\"header-logo\">" +
			"<img src=\"images/SmartLotsLogo.png\" alt=\"Smart Lots Logo\" width=\"296\" height=\"72\">" +
		"</div>" +
		"<div id=\"header-text\">" +
			"<h1>Parking Fixed.</h1>" +
   		"</div>"
    ;

	if ( pageName == 'map' ) {
        menu.innerHTML = 
            "<ul>" +
                "<li class=\"menu-lit\"><a href=\"/smartlots/\">map</a></li>" +
                "<li><a href=\"/smartlots/list.html\">list</a></li>" +
                "<li><a href=\"/smartlots/stats.html\">stats</a></li>" +
            "</ul>"
    	;

	} else if ( pageName == 'list' ) {
		menu.innerHTML = 
            "<ul>" +
                "<li><a href=\"/smartlots\">map</a></li>" +
                "<li class=\"menu-lit\"><a href=\"/smartlots/list.html\">list</a></li>" +
                "<li><a href=\"/smartlots/stats.html\">stats</a></li>" +
            "</ul>"
    	;
	} else if ( pageName == 'stats') {
		menu.innerHTML = 
            "<ul>" +
                "<li><a href=\"/smartlots\">map</a></li>" +
                "<li><a href=\"/smartlots/list.html\">list</a></li>" +
                "<li class=\"menu-lit\"><a href=\"/smartlots/stats.html\">stats</a></li>" +
            "</ul>"
    	;
	}

	miniLogo.innerHTML =
		"A project by: " +
		"<a href=\"http://www.acmxlabs.org\">" +
			"<img src=\"images/ACMxTeenyStamp.png\" alt=\"ACMxLabs.org\" width=\"99\" height=\"43\">" +
		"</a>" +
        "Design by Roy Stillwell. ACMxlabs.org. Copyright 2014."
    ;

    loginDiv.innerHTML = "<a href = \"/smartlots/admin.php\" target = _blank>Log in</a>";

}