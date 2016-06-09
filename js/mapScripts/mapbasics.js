/**
 * Created by Олег on 16.04.2016.
 */

var myMap;
var coordX;
var coordY;

// Дождёмся загрузки API и готовности DOM.
ymaps.ready(init);

function init () {
    // Создание экземпляра карты и его привязка к контейнеру с
    // заданным id ("map_files").
    myMap = new ymaps.Map('map', {
        // При инициализации карты обязательно нужно указать
        // её центр и коэффициент масштабирования.
        center: [59.9462, 30.2819], // СПб
        zoom: 10
    }, {
        searchControlProvider: 'yandex#search'
    }),
    objectManager = new ymaps.ObjectManager({
        clusterize: false
    });

    $.getJSON('../../js/mapScripts/icon.json', function (data) {
        for (var i = 0; i < 9; i++) {
            var iconLayout = data[i]['iconLayout'];
            var iconImageHref = data[i]['iconImageHref'];



            ymaps.option.presetStorage.add(iconLayout, {
                iconLayout: "default#image",
                iconImageHref: iconImageHref,
                iconImageSize: [34, 44],
                iconImageOffset: [-12, -12]
            })
        }

        for (i = 9; i < data.length; i++) {
            iconLayout = data[i]['iconLayout'];
            iconImageHref = data[i]['iconImageHref'];

            ymaps.option.presetStorage.add(iconLayout, {
                iconLayout: "default#image",
                iconImageHref: iconImageHref,
                iconImageSize: [64, 46],
                iconImageOffset: [-12, -12]
            })
        }
    });

    myMap.events.add('click', function (e) {
        if (!myMap.balloon.isOpen()) {
            var coords = e.get('coords');
            coordX = coords[0].toPrecision(6);
            coordY = coords[1].toPrecision(6);
            document.getElementById('coords').value = coordX + " " + coordY;
            myMap.balloon.open(coords, {
                contentBody: '<p>Координаты: ' + [
                    coords[0].toPrecision(6),
                    coords[1].toPrecision(6)
                ].join(', ') + '</p>'
            });
        }
        else {
            myMap.balloon.close();
        }
    });

    // Обработка события, возникающего при щелчке
    // правой кнопки мыши в любой точке карты.
    // При возникновении такого события покажем всплывающую подсказку
    // в точке щелчка.
    myMap.events.add('contextmenu', function (e) {
        myMap.hint.open(e.get('coords'), 'Кто-то щелкнул правой кнопкой');
    });


    // Скрываем хинт при открытии балуна.
    myMap.events.add('balloonopen', function (e) {
        myMap.hint.close();
    });

    myMap.geoObjects.add(objectManager);

    $.ajax({
        url: "../../map_files/data.json"
    }).done(function(data) {
        objectManager.add(data);
    });
}

function chooseSelection() {
    if ($('#list').val() != 'пункты приема вторсырья')
        $('#accept').hide("fast");
    else
        $('#accept').show("fast");
}