<!DOCTYPE html>
<html>
    <head profile="http://www.w3.org/2005/10/profile">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="icon" type="image/png" href="images/favicon.png" />
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,100' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script type="text/javascript" language="javascript" src="js/json2.js"></script>
        <script type="text/javascript" language="javascript" src="js/controller.js"></script>
        <title>SmartLots</title>
        <script>
        start();
        </script>
    </head>
    <body>
        <div id="header">
            <div id="header-logo"><img src="images/SmartLotsLogo.png" alt="Smart Lots Logo" width="296" height="72"> </div>

            <div id="header-text"> <h1>Parking Fixed.</h1>
            </div>
        </div>
        <div id="menu">
            <ul>
                <li class="menu-lit"><a href="/smartlots">map</a></li>
                <li><a href="/smartlots/list">list</a></li>
                <li><a href="/smartlots/stats">stats</a></li>
            </ul> 
        </div>
        <div id="container">

            <div id="CTLMUpper" class="blue-lot">
               Calculating...
            </div>
            <div id="CTLMLower" class="blue-lot">
                Calculating...
            </div>
            <div id="fullLot"> 
            </div>
            <div id="info"></div>

        </div>

        <div id="footer">
            <img src="images/ArrowDown.png" alt="Downzies" width="61" height="57">

        </div>

        <div id="next-screen-container"> 
            <div id="next-screen-text-left">
                <p>Is parking making you late for class? Yeah, us too.   <br/>
                    So we are making a real-time parking sensor network at <a href="https://www.mines.edu/">CSM</a>. </p> 
                <img src="images/angryface.png" alt="anry" width="218" height="210">
            </div>
            <div id="next-screen-text-right">
                <p>
                    We track cars coming in and out of lots using custom built magnetometer sensors, and then publish here. Yay!
                </p>
                <img src="images/happyface.png" alt="anry" width="218" height="210">
            </div>
        </div>
        <div id="mini-logo">A project by: <a href="http://www.acmxlabs.org"><img src="images/ACMxTeenyStamp.png" alt="ACMxLabs.org" width="99" height="43"></a>
            Design by Roy Stillwell. ACMxlabs.org. Copyright 2014.
        </div>
    </body>
</html>