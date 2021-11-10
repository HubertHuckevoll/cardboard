"use strict";

class cbSearch
{
	constructor(searchForm)
	{
	  this.searchForm = document.querySelector(searchForm);
	}

	init()
	{
		if (this.searchForm)
		{
			let frame = this.vInit();

		  frame.querySelector('#searchResultsCloseButton').addEventListener('click', this.cCloseSearch.bind(this));
		  this.searchForm.querySelector('input').addEventListener('click', this.cEmptySearchField.bind(this));
		  this.searchForm.addEventListener('submit', this.cHandleSubmit.bind(this), true);
		}
	}

	cEmptySearchField(ev)
	{
    this.searchForm.querySelector('input').value = '';
	}

	cHandleSubmit(ev)
	{
	  let scriptFile = searchForm.getAttribute("action");
    let term = searchForm.querySelector('input').value;

    if (term != '')
    {
			this.vShowSearchResultsBox();

			let url = scriptFile + "&ajax=ajax&term=" + term;
			fetch(url).then(response => response.text()).then((data) =>
			{
				this.vDrawContent(data);
			});
    }

    ev.preventDefault();
	  return false;
	}

	cCloseSearch()
	{
		this.vHideSearchResultsBox();
	};

	vInit()
	{
		var body = document.querySelector('body');
	  let frame = document.createElement('div');

	  frame.setAttribute('id', 'searchResultsFrame');
	  frame.style.display = "none";
	  frame.style.position = "absolute";
	  frame.style.boxSizing = "borderBox";
	  frame.style.padding = "3px";
	  frame.style.right = 0;
	  frame.style.top = 0;
	  frame.style.bottom = 0;
	  frame.style.width = "370px";
	  frame.style.zIndex = "999";
	  frame.style.border = "1px solid #949494";
	  frame.style.backgroundColor = "rgba(255, 255, 255, .9)";
	  frame.style.overflow = "auto";

	  frame.innerHTML = '<div id="searchResultsTopLine">' +
                    		'<button id="searchResultsCloseButton">x</button>&nbsp;' +
                    	'</div>'+
                    	'<div id="searchResultsContent">' +
                    	'</div>';

    body.appendChild(frame);

	  return frame;
	}

	vShowSearchResultsBox()
	{
		document.getElementById('searchResultsContent').innerHTML = '<progress max="100"></progress>';
    document.getElementById('searchResultsFrame').style.display = 'block';
	}

	vHideSearchResultsBox()
	{
		document.getElementById('searchResultsFrame').style.display = 'none';
	}

	vDrawContent(html)
	{
		document.getElementById('searchResultsContent').innerHTML = html;
	}

}