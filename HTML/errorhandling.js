
// Error handling function can be called in any html file to target JS errors, just needs to be imported.
// Much easier to use than the console.
function errorHandler(message, url, line)
	{
		out = "Sorry, an error was encountered.\n\n";
		out += "Error: " + message + "\n";
		out += "URL: " + url + "\n";
		out += "Line: " + line + "\n\n";
		out += "Click OK to continue.\n\n";
		alert(out);
		return true;
	}

// On errors, defer to the error handling funciton.
onerror = errorHandler