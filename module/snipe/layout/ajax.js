function GetXmlHttpObject() {
  var xmlHttp=null;
  try {
    // Firefox, Opera 8.0+, Safari
    xmlHttp=new XMLHttpRequest();
  }
  catch (e) {
    // Internet Explorer
    try {
      xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch (e) {
      xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
  }
  return xmlHttp;
}

/**
 * Open a connection to the specified URL, which is
 * intended to respond with an XML message.
 *
 * @param string method The connection method; either "GET" or "POST".
 * @param string url    The URL to connect to.
 * @param string toSend The data to send to the server; must be URL encoded.
 * @param function responseHandler The function handling server response.
 */
function xmlOpen ( method, url, toSend, responseHandler ) {
  if (window.XMLHttpRequest) {
    // browser has native support for XMLHttpRequest object
    req = new XMLHttpRequest();
  }
  else if (window.ActiveXObject) {
    // try XMLHTTP ActiveX (Internet Explorer) version
    req = new ActiveXObject("Microsoft.XMLHTTP");
  }
  if (req) {
    req.onreadystatechange = responseHandler;
    req.open(method, url, true);
    req.setRequestHeader("content-type","application/x-www-form-urlencoded");
    req.send(toSend);
  } else {
    alert('Your browser does not seem to support XMLHttpRequest.');
  }
}

function makeHttpRequest ( url, callback_function, method, return_xml ) {
  var HttpRequest = false;

  if (window.XMLHttpRequest) { // Mozilla, Safari,...
    HttpRequest = new XMLHttpRequest();
    if (HttpRequest.overrideMimeType) {
      HttpRequest.overrideMimeType('text/xml');
    }
  } else if (window.ActiveXObject) { // IE
    try {
      HttpRequest = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        HttpRequest = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e) {}
    }
  }

  if (!HttpRequest) {
    alert('Unfortunatelly you browser doesn\'t support this feature.');
    return false;
  }

  HttpRequest.onreadystatechange = function() {
    if ((HttpRequest.readyState == 4) && (HttpRequest.status == 200)) {
      if (return_xml) {
        eval(callback_function + '(HttpRequest.responseXML)');
      } else {
        eval(callback_function + '(HttpRequest.responseText)');
      }
    } else {
      alert('There was a problem with the request.(Code: ' + HttpRequest.status + ')');
    }
  }
  HttpRequest.open(method, url, true);
  HttpRequest.send(null);
}