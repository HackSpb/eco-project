/*
 1.1.	Опасные отходы - islands#blackIcon
 1.2.	Бумага - islands#yellowIcon
 1.3.	Стекло - islands#greenIcon
 1.4.	Пластик - islands#orangeIcon
 1.5.	Металл - islands#redIcon
 1.6.	Одежда - islands#violetIcon
 1.7.	Иное - islands#blueIcon

 1.8.	Опасные отходы – металл     - islands#blueCircleDotIcon

 1.9.	Опасные отходы – иное   - islands#redCircleDotIcon

 1.10.	Опасные отходы – иное – металл  - islands#darkOrangeCircleDotIcon

 1.11.	Бумага – металл     - islands#nightCircleDotIcon

 1.12.	Иное - металл – пластик – стекло – бумага   - islands#darkBlueCircleDotIcon

 1.13.	Металл – пластик – стекло – бумага  - islands#pinkCircleDotIcon

 1.14.	Пластик – бумага    - islands#grayCircleDotIcon

 1.15.	Пластик – Стекло – бумага   - islands#brownCircleDotIcon

 1.16.	Одежда – металл – стекло – бумага   - islands#darkGreenCircleDotIcon

 1.17.	Металл – пластик – бумага   - islands#violetCircleDotIcon

 1.18.	Стекло – бумага     - islands#blackCircleDotIcon

 1.19.	Опасные отходы – пластик – стекло – бумага  - islands#yellowCircleDotIcon

 1.20.	Опасные отходы – металл – бумага    - islands#greenCircleDotIcon

 1.21.	Металл – стекло – бумага    - islands#orangeCircleDotIcon

 1.22.	Одежда – металл – пластик – стекло – бумага     - islands#lightBlueCircleDotIcon

 1.23.	Иное – металл – пластик – бумага    - islands#oliveCircleDotIcon

 1.24.	Бумага – стекло- пластик – металл   - islands#blueDotIcon

 1.25.	Металл – пластик – стекло   - islands#redDotIcon

 1.26.	Металл – стекло     - islands#darkOrangeDotIcon

 1.27.	Иное – металл – пластик – стекло    - islands#nightDotIcon

 1.28.	Бумага – стекло – пластик – металл – одежда – одежда – иное – опасные отходы    - islands#darkBlueDotIcon

 1.29.	Иное – металл   - islands#pinkDotIcon

 1.30.	Иное – одежда   - islands#grayDotIcon

 2.	Предстоящие мероприятия     - islands#brownDotIcon
 3.	Пункты велопроката  - islands#darkGreenDotIcon
 4.	Эко-кафе    - islands#violetDotIcon
 5.	Приюты для животных     - islands#blackDotIcon
 6.	Магазины с веганскими товарами  - islands#yellowDotIcon
 7.	Магазины с экотоварами  - islands#greenDotIcon

 */

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

    // Чтобы задать опции одиночным объектам и кластерам,
    // обратимся к дочерним коллекциям ObjectManager.
    objectManager.clusters.options.set('preset', 'islands#violetClusterIcons');
    myMap.geoObjects.add(objectManager);

    function checkState () {
        myMap.geoObjects.removeAll();
        $.ajax({
            url: "map/filtration.php"
        });

        $.ajax({
            url: "map/data_for_map.json"
        }).done(function (data) {
            objectManager.add(data);
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
        url: "map/data_for_map.json"
    }).done(function (data) {
        objectManager.add(data);
    });
}