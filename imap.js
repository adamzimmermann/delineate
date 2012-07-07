//checks for new data
function checkMail() {
	
	xmlHttp = new XMLHttpRequest();
	xmlHttp.onreadystatechange = function() {
		if(xmlHttp.readyState == 4) {
			
			var response = xmlHttp.responseText;
			
			console.log('RESPONSE: ' + response)
			
			if(response == 'false') {
				console.log('STATUS: no new fillup information');
			}
			else if(response == 'other') {
				console.log('STATUS: information found for other vehicles');
			}
			else {
				console.log('STATUS: new fillups found!');
				updateTable(response);
			}
		}
	};
	var href = window.location.pathname	
	xmlHttp.open("GET", "/fillups/update/" + href.substr(href.lastIndexOf('/') + 1), true);
	xmlHttp.send();
}

//called when data returned
function updateTable(input) {
	var data = JSON.parse(input);
	
	for(var key in data) {
		
		console.log('USER ID: ' + data[key].user_ID);
		
		$('#table').append(fillupRow(data[key]));
		
		console.log("DATA: " + JSON.stringify(data));
	}
}

function fillupRow(fillup) {
	var row = '<tr>';
	row += tableCell(fillup.date);
	row += tableCell(fillup.mpg);
	row += tableCell(fillup.price);
	row += tableCell(fillup.gallons);
	row += tableCell(fillup.cost);
	row += tableCell(fillup.mileage);
	row += tableCell(fillup.user_ID);
	row += '</tr>';
	
	return row;
}

function tableCell(string) {
	return '<td>' + string + '</td>';
}


//checks for new mail every 5 seconds
setInterval("checkMail()", 5000);

console.log('STATUS: live data started');