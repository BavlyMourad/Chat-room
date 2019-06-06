function loadMessage() {
	var request;

	if(window.XMLHttpRequest) {
		request = new XMLHttpRequest(); //Modern Browsers
	}
	else {
		request = new ActiveXObject("Microsoft.XMLHTTP"); //Old Browsers
	}

	request.onreadystatechange = function() {
		if(request.readyState == 4 && request.status == 200) {
			document.getElementById("chat").innerHTML = request.responseText;
		}
	}

	request.open('GET','message.php',true);
	request.send();
}

setInterval(function(){ loadMessage() }, 1);