ymaps.ready(init);
var obj = {
    x: [],
    y: [],
    location: []
},
    pointsAmount = 0;
var myMap;

function init() {
    var myPlacemark,
        location;

    myMap = new ymaps.Map('map', {
        center: [59.9392, 30.3410],
        zoom: 9
    }, {
        searchControlProvider: 'yandex#search'
    });

    // Слушаем клик на карте
    myMap.events.add('click', function (e) {
        var coords = e.get('coords');

        // Если метка создана и их меньше 10, то создаем новою и
        // создадим новое поле для адреса и координат
        if (pointsAmount < 10) {

            document.getElementById('coords').innerHTML += "" +
                "<input type='text' id='location" + pointsAmount + "' name='location[]' value='' placeholder=\"Адрес\">" +
                "<input type='hidden' id='coord_x" + pointsAmount + "' name='coord_x[]' value=''>" +
                "<input type='hidden' id='coord_y" + pointsAmount + "' name='coord_y[]' value=''>";
        }

        if (pointsAmount >= 10) {
            alert('Нельзя создавать более 10 точек для событий');
        }
        else {
            myPlacemark = createPlacemark(coords);
            getAddress(coords);
            myMap.geoObjects.add(myPlacemark);
            pointsAmount++;

        }

    });

    // Создание метки
    function createPlacemark(coords) {
        // Создаем внешний вид метки событий
        ymaps.option.presetStorage.add("default#image", {
            iconLayout: "default#image",
            iconImageHref: '../../map_files/icon/event.svg',
            iconImageSize: [34, 34],
            iconImageOffset: [-12, -12]
        });

        return new ymaps.Placemark(coords, {
            iconContent: 'поиск...'
        }, {
            preset: 'default#image',
            draggable: false
        });
    }

    // Определяем адрес по координатам (обратное геокодирование)
    function getAddress(coords) {
        myPlacemark.properties.set('iconContent', 'поиск...');
        ymaps.geocode(coords).then(function (res) {
            var firstGeoObject = res.geoObjects.get(0),
                locationID = 'location' + (pointsAmount - 1),
                coord_x = 'coord_x' + (pointsAmount - 1),
                coord_y = 'coord_y' + (pointsAmount - 1);
            location = res.geoObjects.get(0).properties.get('text');

            obj.x.push(coords[0].toPrecision(6));
            obj.y.push(coords[1].toPrecision(6));
            obj.location.push(firstGeoObject.properties.get('text'));


            // if (pointsAmount == 2) {
            //     document.getElementById('location').value = firstGeoObject.properties.get('text');
            //     document.getElementById('coord_x').value = coords[0].toPrecision(6);
            //     document.getElementById('coord_y').value = coords[1].toPrecision(6);
            // }
            // else {
            // }

            for (var i = 0; i < pointsAmount; i++) {
                document.getElementById('location' + i).value = obj.location[i];
                document.getElementById('coord_x' + i).value = obj.x[i];
                document.getElementById('coord_y' + i).value = obj.y[i];
            }

            myPlacemark.properties
                .set({
                    iconContent: firstGeoObject.properties.get('name'),
                    balloonContent: firstGeoObject.properties.get('text')
                });
        });
    }


}

function clearMap() {
    myMap.geoObjects.removeAll();
    document.getElementById('coords').innerHTML = "";
    obj = {
        x: [],
        y: [],
        location: []
    };
    pointsAmount = 0;
}