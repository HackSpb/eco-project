$(document).ready(function() {

    // page is now ready, initialize the calendar...

    $('#calendar').fullCalendar({
        dayClick: function() {
        alert('a day has been clicked!');
    }
});}