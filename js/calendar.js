// objects contains days and months russian names.
var months = {
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
    selectedYear = 0,
    counter = 10;

$('#load-more').click(function () {
    getEventData(counter);
});

/**
 * Function provide getting data from events table in database
 */
function getEventData(limit) {
    $('#loading').show();
    $.ajax({
        url: "../../includes/ajax_scripts/calendar_ajax.php",
        type: "GET",
        data: ({"limit": limit}),
        dataType: 'json',
        success: function (json) {
            if (json.length > 0) {

                $.each(json, function (index, value) {
                    $('#calendar-content-container').append('' +
                        '<article>' +
                        '<div class="row">' +
                        '<div class="calendar-date col-xs-2">' +
                        '<p class="calendar-date-dayNum">' + value['beginDateDay'] + '</p>' +
                        '<p class="calendar-date-day">' + value['DayOfWeek'] + '</p>' +
                        '</div>' +
                        '<div class="calendar-text col-xs-9">' +
                        '<p><b>' + value['name'] + '</b></p>' +
                        '<p>' + value['address'] + '</p>' +
                        '<p>' + value['beginTime'] + '</p>' +
                        '</div>' +
                        '</div>' +
                        '</article>');

                    $('#calendar-content-container').height('auto');

                });

                counter += 5;
            }
            else
                $('#load-more').hide();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(textStatus);
            console.log(errorThrown);
        }
    })
}