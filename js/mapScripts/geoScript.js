// Пример правильного пути '/js/mapScripts/icon.json'
$(document).ready(function () {
    $.post('/map_files/checkExpiredEvents.php');
});

ymaps.ready(init);



function init () {

    /**
     * Инициализируем карту
     */

    var myMap = new ymaps.Map('map', {
            center: [59.9158,30.2394],
            zoom: 9
        }, {
            searchControlProvider: 'yandex#search'
        }),

        /**
         * Инициализируем хранилище объектов objectManager
         */

        objectManager = new ymaps.ObjectManager({
            // Чтобы метки начали кластеризоваться, выставляем опцию.
            clusterize: true,
            // ObjectManager принимает те же опции, что и кластеризатор.
            gridSize: 32
        });

    /**
     * Очищаем хранилище объектов предыдущей сессии
     */

    objectManager.removeAll();

    // Добавляем новые иконки в хранилище опций presetStorage
    ymaps.option.presetStorage.add("Event#image", {
        iconLayout: "default#image",
        iconImageHref: "../../map_files/icon/event.svg",
        iconImageSize: [34, 34],
        iconImageOffset: [0, 0]
    });

    /**
    * Чтобы задать опции одиночным объектам и кластерам,
    * обратимся к дочерним коллекциям ObjectManager.
    */

    objectManager.clusters.options.set('preset', 'islands#violetClusterIcons');
    myMap.geoObjects.add(objectManager);

    /**
     * Функция проверки состояния флажков
     */

    function checkState () {
        var filterArr = [];

        var acceptArr = [],
            pointsArr = [];

        for (var i = 0; i < document.getElementsByName('acception[]').length; i++) {
            if (document.getElementsByName('acception[]')[i].checked) {
                acceptArr.push(document.getElementsByName('acception[]')[i].value);
            }
        }

        for (i = 0; i < document.getElementsByName('points[]').length; i++) {
            if (document.getElementsByName('points[]')[i].checked) {
                pointsArr.push(document.getElementsByName('points[]')[i].value);
            }
        }

        $.getJSON('/js/mapScripts/preset.json', function (data) {
            if (document.getElementById('check').checked) {
                $.each(data[1], function(key, value) {
                    var keys = key.split(" ");
                    for (i = 0; i < acceptArr.length; i++) {
                        for (var j = 0; j < keys.length; j++) {
                            if (acceptArr[i] == keys[j] && filterArr.indexOf(value) == -1) {
                                filterArr.push(value);
                            }
                        }
                    }
                })
            }
            $.each(data[0], function(key,value){
                var keys = key.split(" ");
                for (i = 0; i < pointsArr.length; i++) {
                    for (var j = 0; j < keys.length; j++) {
                        if (pointsArr[i] == keys[j] && (filterArr.indexOf(keys[j]) == -1)) {
                            filterArr.push(value);
                        }
                    }
                }
            });

            var filterString = "";
            var count = 0;
            for (i = 0; i < filterArr.length; i++) {
                count = i;
                if (count < (filterArr.length - 1)) {
                    filterString = filterString + 'options.preset == "' + filterArr[i] + '" || ';
                }
                if (count == (filterArr.length - 1)) {
                    filterString = filterString + 'options.preset == "' + filterArr[i] + '"';
                }
            }

            objectManager.setFilter(filterString);
        });
    }

    $('#dangerousGarbage').click(checkState);
    $('#paper').click(checkState);
    $('#metal').click(checkState);
    $('#glass').click(checkState);
    $('#clothes').click(checkState);
    $('#plastic').click(checkState);
    $('#other').click(checkState);
    $('#pink').click(checkState);
    $('#events').click(checkState);
    $('#bicycle').click(checkState);
    $('#eco-cafe').click(checkState);
    $('#shelter').click(checkState);
    $('#vegan').click(checkState);
    $('#eco-goods').click(checkState);



    $.ajax({
        url: "/map_files/data.json"
    }).done(function (data) {
        objectManager.add(data);
    });
}