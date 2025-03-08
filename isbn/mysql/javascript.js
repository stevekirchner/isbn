function fillPage(actionArg,id)
{
	var resultarea = document.getElementById("results_area");
	var action = actionArg;
	var theForm = document.getElementById("input_controls");
	if ("" == action)
	{
		for (var i = 0; i < theForm.length; i++)
		{
			if (theForm.elements[i].type == "radio" &&
             theForm.elements[i].name == "action")
			{
				if (theForm.elements[i].checked)
				{
					action = theForm.elements[i].id;
				}
			}
		}
	}
	else if ( "search" == action) // Leave the radio buttons alone
	{
	}
	else if ("getInfo" != action)
	{
		for (var i = 0; i < theForm.length; i++)
		{
			if(theForm.elements[i].type == "radio" &&
				theForm.elements[i].name == "action")
			{
				theForm.elements[i].checked = false;
			}
		}
	}
   
	if (window.XMLHttpRequest)
	{
		// IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else
	{
		// IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	if ("" == action)
	{
		resultarea.innerHTML = "";
		return;
	}
   
	if ("getInfo" != action)
	{
		xmlhttp.onreadystatechange = function()
		{
			if (xmlhttp.readyState==4)
			{
				if (xmlhttp.status==200)
				{
					var results = xmlhttp.responseText.split("-----");
					resultarea.innerHTML = results[0];
					eval(results[1]);
				}
				document.getElementById("waitbox").className = "idle";
				spinner.stop();
				xmlhttp.onreadystatechange = null;
			}
		}
	}
	else
	{
		xmlhttp.onreadystatechange = function()
		{
			if (xmlhttp.readyState==4)
			{
				if(xmlhttp.status==200)
				{
					var results = xmlhttp.responseText.split(/\r\n|\r|\n/);
               
					var theForm = document.getElementById("newpart");
					for(var i=0; i < results.length; i++)
					{
						var values = results[i].split("|");
						if (values.length < 2)
						{
							continue;
						}
						for (var j=0; j < theForm.elements.length; j++)
						{
							if (theForm.elements[j].name == values[0])
							{
								theForm.elements[j].value = values[1];
								break;
							}
						}
					}
				}
				document.getElementById("waitbox").className = "idle";
				spinner.stop();
				xmlhttp.onreadystatechange = null;
			}
		}
	}
   
	document.getElementById("waitbox").className = "busy";
	spinner.spin(target);
	
	if ("update" == action)
	{
	   xmlhttp.open("GET", "query.php?action=" + action + "&id=" + id, true);
	}
	else if ("search" == action)
	{
      var author_fn = document.getElementById("author_fn");
		var author_ln = document.getElementById("author_ln");
		
		if("" != author_fn.value && "" != author_ln.value)
		{
		   xmlhttp.open("GET", "search_author.php?author_fn=" + author_fn.value + "&author_ln=" + author_ln.value, true);
        }
        else
        {
           alert("Author first and last names not set");
        }
	}
	else if ("delete" == action)
	{
        if ( confirm_delete () )
        {
           xmlhttp.open("GET", "query.php?action=" + action + "&id=" + id, true);
		}
		else
		{
		   // Change action to browse
		   action = "browse";
		   xmlhttp.open("GET", "query.php?action=" + action + "&id=" + id, true);
		}
	}
	else if("getInfo" == action)
	{
		var isbnNum = document.getElementById("isbnnum");
		
		if("" != isbnNum.value)
		{
			xmlhttp.open("GET", "isbn.php?isbn=" + isbnNum.value, true);
		}
		else
		{
		   alert("ISBN number not set");
		}
	}
	else
	{
		xmlhttp.open("GET", "query.php?action=" + action, true);
	}
	xmlhttp.send(null);
	return false;
}

function confirm_delete()
{
   var answer = confirm("Delete entry?")
	
   return answer;
}

function validateForm()
{
	var theForm = document.getElementById("newpart");
	for (var i=0; i < theForm.length; i++)
	{
		for (var j=0; j < theForm.elements[i].parentElement.classList.length; j++)
		{
         // Strip off any whitespace
			theForm.elements[i].value = theForm.elements[i].value.replace(/(^\s+|\s+$)/g,'');
		}
	}
	return true;
}