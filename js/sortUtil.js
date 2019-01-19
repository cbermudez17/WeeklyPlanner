/*
 * sortUtil.js is a utility javascript file that contains sort and comparison functions for different elements of the page.
 */

var days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

// Sorts deadlines and rearranges them in order
function sortDeadlines() {
	var deadlines = [];
	$('#deadlines ul li:not(:last-of-type)').each(function() {
		deadlines.push(this.id.split('deadline')[1]);
	});
	if (deadlines.length > 1) {
		deadlines.sort(compareDeadline);
		for (var i = 0; i < deadlines.length - 1; i++) {
			$('#deadline'+deadlines[i+1]).insertAfter('#deadline'+deadlines[i]);
		}
	}
}

// Convert dates into integers and then compares them
function compareDeadline(id1, id2) {
	// Get dates in mm/dd format
	a = $('#deadline'+id1+' span:nth-child(2)').text().trim();
	b = $('#deadline'+id2+' span:nth-child(2)').text().trim();
	
	// Remove '/' and convert into integers
	var date1 = parseInt(a.replace('/', ''));
	var date2 = parseInt(b.replace('/', ''));
	
	return date1-date2;
}

//Sorts tasks for each day and rearranges them in order
function sortTasks() {
	days.forEach(function(day) {
		var cards = [];
		$('#'+day+' .card').each(function() {
			cards.push(this.id.split('card')[1]);
		});
		if (cards.length > 1) {
			cards.sort(compareTime);
			for (var i = 0; i < cards.length - 1; i++) {
				$('#card'+cards[i+1]).insertAfter('#card'+cards[i]);
			}
		}
	});
}

//Convert times into 24-hour format time and concatenate start and end times then compare both results as integers
function compareTime(a, b) {
	// Gets the times in hh:mm%p-hh:mm%p format
	a = $('#modal'+a+' .body-time').text().trim();
	b = $('#modal'+b+' .body-time').text().trim();
	
	var time1, time2;
	if (a == '') {
		// No time was provided therefore set to max
		time1 = 24000000;
	} else {
		var times = a.split('-');
		
		// Remove colon and convert to integer
		// Note: multiply by 10000 to move plave value over 4 digits because it is the first time in the range therefore more significant
		time1 = parseInt(times[0].substring(0, times[0].length-2).replace(':', '')) * 10000;
		var type = times[0].substring(times[0].length-2);
		
		// Convert to 24-hr format integer
		if (type == 'PM') {
			time1 += 12000000;
		} else if (times[0].substring(0, 2) == '12') {
			time1 -= 12000000;
		}
		
		// Remove colon and convert to integer
		// Add it to the total because it is ultimately a single integer
		time1 += parseInt(times[1].substring(0, times[1].length-2).replace(':', ''));
		var type = times[1].substring(times[1].length-2);
		
		// Convert to 24-hr format integer
		if (type == 'PM') {
			time1 += 1200;
		} else if (times[1].substring(0, 2) == '12') {
			time1 -= 1200;
		}
	}
	
	//
	if (b == '') {
		// No time was provided therefore set to max
		time2 = 24000000;
	} else {
		var times = b.split('-');
		
		// Remove colon and convert to integer
		// Note: multiply by 10000 to move plave value over 4 digits because it is the first time in the range therefore more significant
		time2 = parseInt(times[0].substring(0, times[0].length-2).replace(':', '')) * 10000;
		var type = times[0].substring(times[0].length-2);
		
		// Convert to 24-hr format integer
		if (type == 'PM') {
			time2 += 12000000;
		} else if (times[0].substring(0, 2) == '12') {
			time2 -= 12000000;
		}
		
		// Remove colon and convert to integer
		// Add it to the total because it is ultimately a single integer
		time1 += parseInt(times[1].substring(0, times[1].length-2).replace(':', ''));
		var type = times[1].substring(times[1].length-2);
		
		// Convert to 24-hr format integer
		if (type == 'PM') {
			time2 += 1200;
		} else if (times[1].substring(0, 2) == '12') {
			time2 -= 1200;
		}
	}
	
	return time1-time2;
}