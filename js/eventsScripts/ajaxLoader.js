$(document).ready(function () {
  var inProgress = false;
  var startFrom = 8;
  var eventheight = 300;
  $(window).scroll(function () {
    if ($(window).scrollTop() + $(window).height() >= $(document).height() - eventheight && !inProgress) {
      $.ajax({
        url: 'eventsController.php',
        method: 'POST',
        data: { "startFrom": startFrom },
        beforeSend: function () { inProgress = true; }
      }).done(function (data) {
        data = jQuery.parseJSON(data);
        if (data.length > 0) {
          $.each(data, function (index, data) {
            $(".grid").append(
              '<div class="col-1-4"><div class="content">'
              + '<img class="post-logo" src="images/event/'
              + data.ev_image +'" alt="" /> '
              + '<figcaption class="card-info"><p class="residents">'
              + data.tag_name
              + '</p><h3>'
              + data.ev_title
              + '</h3><p class="timeinfo">'
              + data.ev_begin_time
              + data.ev_end_time
              + data.ev_begin_date
              + data.ev_end_date
              + data.ev_address
              + '</p></figcaption><button class="btn btn-small">Присоединяйся</button></div></div>'
            );
          });
          inProgress = false;
          startFrom += 8;
        }
      });
    }
  });
});