/* This JavaScript calls a .php document that searches the database for the list of rolls for an instance and outputs the result in HTML as a table.  The script then writes this HTML into the page.
*/

var xmlHttp

function getExternalHTML2(url, containerID) {
    xmlHttp = GetXmlHttpObject()
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request")
        return
    }
    var url = url + "&sid=" + Math.random()
/*
	alert(url)
*/
	xmlHttp.onreadystatechange = function () {
        stateChanged(containerID)
    }
    xmlHttp.open("GET", url, true)
    xmlHttp.send(null)
}

function stateChanged(containerID) {
    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
        document.getElementById(containerID).innerHTML = xmlHttp.responseText;
    }
}

function GetXmlHttpObject() {
    var objXMLHttp = null
    if (window.XMLHttpRequest) {
        objXMLHttp = new XMLHttpRequest()
    }
    else if (window.ActiveXObject) {
        objXMLHttp = new ActiveXObject("Microsoft.XMLHTTP")
    }
    return objXMLHttp
}