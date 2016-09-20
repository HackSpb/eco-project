// objects containes days and monthes russian names.
var monthes = {
	0: "ЯНВАРЬ",
	1: "ФЕВРАЛЬ",
	2: "МАРТ",
	3: "АПРЕЛЬ",
	4: "МАЙ",
	5: "ИЮНЬ",
	6: "ИЮЛЬ",
	7: "АВГУСТ",
	8: "СЕНТЯБРЬ",
	9: "ОКТЯБРЬ",
	10: "НОЯБРЬ",
	11: "ДЕКАБРЬ"
},
	days = {
		0: "вс",
		1: "пн",
		2: "вт",
		3: "ср", 
		4: "чт",
		5: "пт",
		6: "сб"
	},
	selectedMonth = 0,
	selectedYear = 0;


$(document).ready(function() {
	// Getting current date and put current month and year into calendar.
	var now = new Date(),
		selectedMonth = now.getMonth(),
		selectedYear = now.getFullYear();

	$('#MonthYear-string').html(monthes[selectedMonth] + ' ' + selectedYear);
	// Getting data from events table in database by ajax
	// and put in #calendar-content div.

	getEventData(5, 2016);

});

/**
 * Function provide getting data from events table in database
 */
function getEventData(Month, Year) {
	$('#loading').show();   
	$.ajax({
		url: "../../includes/ajax_scripts/calendar_ajax.php",
		type: "GET",
		data: ({"month": 4, "year": Year}),
		dataType: 'json',
		success: function(json) {
			console.log(json);
			$('#loading').show();
			$('#calendar-content-text').append(json);
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus);
			console.log(errorThrown);
		}
	})
}