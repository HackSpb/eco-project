ymaps.ready(init);


function init () {
    var myMap = new ymaps.Map('map', {
            center: [59.9158,30.2394],
            zoom: 9
        }, {
            searchControlProvider: 'yandex#search'
        }),
        objectManager = new ymaps.ObjectManager({
            // Чтобы метки начали кластеризоваться, выставляем опцию.
            clusterize: true,
            // ObjectManager принимает те же опции, что и кластеризатор.
            gridSize: 32
        });
    objectManager.removeAll();



    $.getJSON('js/mapScripts/icon.json', function (data) {
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

            console.log(data.length);

            ymaps.option.presetStorage.add(iconLayout, {
                iconLayout: "default#image",
                iconImageHref: iconImageHref,
                iconImageSize: [64, 46],
                iconImageOffset: [-12, -12]
            })
        }
    });

    console.log(ymaps.option.presetStorage);
    // Чтобы задать опции одиночным объектам и кластерам,
    // обратимся к дочерним коллекциям ObjectManager.

    objectManager.clusters.options.set('preset', 'islands#violetClusterIcons');
    myMap.geoObjects.add(objectManager);



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

        $.getJSON('../js/mapScripts/preset.json', function (data) {
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
        url: "../../map_files/data.json"
    }).done(function (data) {
        objectManager.add(data);
    });
}