var filterElements = [];
var filterIds = [];
var filterFocusStates = [];
var filterCount = 0;
var postCount = 0;
var postCounter = 0;
var inputElements = "";
var postElements = "";
var focusState =  "";
var temp = "";

filterElements = jQuery("input[id*=category-].btn");
filterCount = filterElements.length;

for(var i = 0; i < filterCount; i++)
{
	temp = jQuery("input[id*=category-].btn")[i].id;
	temp = temp.replace(" ", "-");
	filterIds.push(temp);
}

function filterPosts(clickedId)
{
    inputElements = jQuery("input#" + clickedId + ".btn");
	postElements = jQuery("div." + clickedId);
	postCount = postElements.length;
    focusState = jQuery("input#" + clickedId).attr("focus");
   	classList = [];
	filterFocusStates = [];

	switch(focusState)
	{
		case null:
		case "false":
			inputElements.toggleClass('btn-primary btn-success');
			inputElements.attr("focus", true);

			for(var i = 0; i < filterCount; i++)
			{
				temp = jQuery("input[id*=category-].btn")[i].attributes["focus"].value;
				filterFocusStates.push(temp);
			}

			/*for(var i = 0; i < postCount; i++)
			{
				classList = jQuery("div." + clickedId)[i].classList;
				classList = toArray(classList);
				classList = jQuery.grep(classList, function(item, index)
				{
					return item.startsWith("category-");
				});

				for(var j = 0; j < filterCount; j++)
				{
					if(filterFocusStates[j] == "true")
					{
						postCounter++;
						if(postCounter == (postCount - 1))
						{
							postElements.show();
							break;
						}
					}
				}
			}
			postCounter = 0;*/

			for(var j = 0; j < filterCount; j++)
			{
				if(filterFocusStates[j] == "true")
				{
					postElements.show();
				}
			}
			break;
		case "true":
			inputElements.toggleClass('btn-success btn-primary');
			inputElements.attr("focus", false);

			for(var i = 0; i < filterCount; i++)
			{
				temp = jQuery("input[id*=category-].btn")[i].attributes["focus"].value;
				filterFocusStates.push(temp);
			}

			/*for(var i = 0; i < postCount; i++)
			{
				classList = jQuery("div." + clickedId)[i].classList;
				classList = toArray(classList);
				classList = jQuery.grep(classList, function(item, index)
				{
					return item.startsWith("category-");
				});
				for(var j = 0; j < filterCount; j++)
				{
					if(filterFocusStates[j] == "false")
					{
						postCounter++;
						if(postCounter == (postCount - 1))
						{
							postElements.show();
							break;
						}
					}
				}
			}
			postCounter = 0;*/

			for(var j = 0; j < filterCount; j++)
			{
				if(filterFocusStates[j] == "false")
				{
					postElements.hide();
					break;
				}
			}
			break;
		default:
			inputElements.attr("focus", "false");
			break;
	}
}

/*function toArray(obj)
{
	var array = [];

	for (var i = obj.length >>> 0; i--;)
	{
		array[i] = obj[i];
	}

	return array;
}*/