//Roy Stillwell
//ACMxLabs.org
//2.24.14
//This is a small library we will be using to send and receive ajax responses.
//for SmartLots framework.

//Global variables
var dataRequest; //object that will be used to house the json response from status
var startTime;
var loadTime;
var timer;
var delay = 500;


function start() {
    startTime = new Date();  //start the timer

    //This will kick off an async request to grab data
    grabData();
    
    loadTime = new Date();  //check time 
    var timeDiff = (loadTime - startTime)/1000; //for debug if need

    //document.getElementById("sendbutton").disabled = true;
}


function sendAjaxGetRequest(url, returnFunction) {
    ajaxGetRequest = false;
    try {
        ajaxGetRequest = new XMLHttpRequest();  //This is the javaScript all powerful asynchronous method that essentially runs all of Web2.0
          
    } catch (trymicrosoft) {
        try {
            ajaxGetRequest = new ActiveXObject("Msxml2.XMLHTTP");  //THis is the code to 'fix' half Microsoft's browsers
        } catch (othermicrosoft) {
            try {
                ajaxGetRequest = new ActiveXObject("Microsoft.XMLHTTP"); //This is the code to 'fix' the other half.
            } catch (failed) {
                ajaxGetRequest = false;
            }  
        }
    }

    if (!ajaxGetRequest)
        alert("Error initializing XMLHttpRequest!");

    ajaxGetRequest.open("GET", url, true); //'GET' is the type of request, url is the request srting (obviously) and true
    //states that the request is asynchronous.  Funny enough, the open method
    //does not OPEN anything (no data is sent, check it with wireshark).  It's 
    //just setting up the request.  open() was a stupid name. send() does all the 
    //actual http work.
                                                
    ajaxGetRequest.onreadystatechange = returnFunction; 
               
    //The real asynch magic is here. When the server is done responding to
    //the request, it 'callsback' to the method mentioned here as 'updatePage'. 
    
    ajaxGetRequest.send();  //This actually opens the request and makes the url call.

}    

function grabData() {
    
    try
    {
 
        var key = "mainpage";
        var url = '/parking/monitor/?requestor=' + key + '&amp;uniqueifier=' +new Date();
        
        //this sends an initial request immediately.
         sendAjaxGetRequest(url, function(){updatePage()});
          
        //this builds a java timer that kicks off the Ajax request every 500ms
        timer=setInterval(function(){
            sendAjaxGetRequest(url, function(){
                updatePage()
            })
        },delay);
      
    }
    catch(err)
    {
        document.getElementById('info').innerHTML = "Error!: " + err.message + "." + ajaxGetRequest.responseText;

    }
  
    
}    

function updatePage() {
    //alert("updateTwilioNumbers readystate: " + EDIRequest.readyState);
    if (ajaxGetRequest.readyState == 4) {  //readyState 4 is that the response is actually complete, this is required!!
        if (ajaxGetRequest.status == 200) { //200 is the http response that means 'ok.'  this is required as well!
            try
            {
                var response = JSON.parse(ajaxGetRequest.responseText); //data received by system
                //var response = ajaxPostRequest.responseText;
                loadTime = new Date();
            
                //used for testing to display time
                document.getElementById('info').innerHTML = "Diagnostics: <br/>" + (loadTime-startTime)/1000 +  "sec.<br/>";
                
                //iterate through json object and spit out pertinent data!
                for(var i=0; i<response.length ; i++){
                    
                   // document.getElementById('info').innerHTML += "i =" + i +"<br/>";
                    var openslots = 0;
                    var style  = "";
                    var status = "";
                    
                    //calculate open slots
                    openslots = response[i].carmax - response[i].carcount;
                    document.getElementById('info').innerHTML += "<br/>" + openslots;
                    
                    //For each iteration, check to see if the lot is full, or close, and set the colors apporpriately
                    if( openslots <= 5 ) {
                        color = "red-lot";
                        status = "FULL";
                        
                    }
                    else if( 5 < openslots  && openslots< 10 ) {
                        color = "yellow-lot";
                        status = "Kinda close";
                    }
                    else if( openslots >= 10 ) {
                        color = "green-lot";
                        status = "OPEN";
                    } 
                    
                    //CTLM upper
                    if(response[i].lotid==1) {
                        document.getElementById('CTLMUpper').innerHTML =  
                            response[i].lotname + " Lot " + "SHOULD be " + 
                            status + "!</br>" + 
                            response[i].carcount + " of " +
                            response[i].carmax + " spots filled." + "<br/>"
                        document.getElementById('CTLMUpper').className = color;    
                    }
                    
                    //CTLM lower
                    if(response[i].lotid==2) {
                        document.getElementById('CTLMLower').innerHTML =  
                            response[i].lotname + " Lot " + "SHOULD be " + 
                            status + "!</br>" + 
                            response[i].carcount + " of " +
                            response[i].carmax + " spots filled." + "<br/>"
                        document.getElementById('CTLMLower').className = color;    
                    }
                        
                                     //Weaver lot
                if(response[i].lotid==4) {
                    //Future use  
                }
                
                     document.getElementById('info').innerHTML +=  
                    response[i].lotname + " " +
                    response[i].lotid + " " +
                    response[i].carcount + " " +
                    response[i].carmax + " " + "<br/>"
                ;
                }
   
                     if( (loadTime-startTime)/1000 >1800) { //1800 seconds is 30 minutes
                        clearInterval(timer);
                        document.getElementById("info").innerHTML += 'The timer ran longer than 30 minutes, So we are just stopping it to be safe.<br/>';
                        setInterval(function(){location.reload(); },10000);
                 }

            
        }
        catch(err)
        {
            document.getElementById('info').innerHTML = "Error!: " + err.message + "." + ajaxGetRequest.responseText;

        }
    
        //checks for common errors in the async url request and sends simple alert for debug
    } else if (ajaxGetRequest.status == 404) {
        alert ("Requested URL from updatepage() is not found.");
    } else if (ajaxGetRequest.status == 403) {
        alert("Access denied.");
    } 
//    else
//        alert("status from updatepage() is " + ajaxGetRequest.status);
}
}

function sendAjaxPostRequest(url, data, returnFunction) {
ajaxPostRequest = false;
try {
    ajaxPostRequest = new XMLHttpRequest();  //This is the javaScript all powerful asynchronous method that essentially runs all of Web2.0
          
} catch (trymicrosoft) {
    try {
        ajaxPostRequest = new ActiveXObject("Msxml2.XMLHTTP");  //THis is the code to 'fix' half Microsoft's browsers
    } catch (othermicrosoft) {
        try {
            ajaxPostRequest = new ActiveXObject("Microsoft.XMLHTTP"); //This is the code to 'fix' the other half.
        } catch (failed) {
            ajaxPostRequest = false;
        }  
    }
}

if (!ajaxPostRequest)
    alert("Error initializing XMLHttpRequest!");
     
ajaxPostRequest.open("POST", url, true); //'GET' is the type of request, url is the request srting (obviously) and true
ajaxPostRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                        
ajaxPostRequest.onreadystatechange = returnFunction; 
               
ajaxPostRequest.send(data);
    

}    
 
//app to pause the machine for a short time typically for testing
function pausecomp(millis)
{
var date = new Date();
var curDate = null;

do {
    curDate = new Date();
}
while(curDate-date < millis);
} 