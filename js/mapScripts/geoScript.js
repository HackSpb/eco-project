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

        var arr = new Array(13);
        var arr_str = new Array(13);
        for (i = 0; i < arr.length; i++) {
            arr[i] = false;
        }


        arr[0] = !!$('#dangerousGarbage').prop('checked');
        arr[1] = !!$('#paper').prop('checked');
        arr[2] = !!$('#metal').prop('checked');
        arr[3] = !!($('#plastic').prop('checked'));
        arr[4] = !!($('#glass').prop('checked'));
        arr[5] = !!($('#clothes').prop('checked'));
        arr[6] = !!($('#other').prop('checked'));
        arr[7] = !!($('#events').prop('checked'));
        arr[8] = !!($('#bicycle').prop('checked'));
        arr[9] = !!($('#eco-cafe').prop('checked'));
        arr[10] = !!($('#shelter').prop('checked'));
        arr[11] = !!($('#vegan').prop('checked'));
        arr[12] = !!($('#eco-goods').prop('checked'));

        arr_str[0] = '"islands#blackIcon"'; //опасные отходы
        arr_str[1] = '"islands#yellowIcon"'; //макулатура
        arr_str[2] = '"islands#redIcon"'; //металлолом
        arr_str[3] = '"islands#orangeIcon"'; //пластик
        arr_str[4] = '"islands#greenIcon"'; //стекло
        arr_str[5] = '"islands#violetIcon"'; //одежда
        arr_str[6] = '"islands#blueIcon"'; //иное
        arr_str[7] = '"islands#blueCircleDotIcon"'; //Опасные отходы – металл
        arr_str[8] = '"islands#redCircleDotIcon"'; //Опасные отходы – иное
        arr_str[9] = '"islands#darkOrangeCircleDotIcon"'; //Опасные отходы – иное – металл
        arr_str[10] = '"islands#nightCircleDotIcon"'; //Бумага – металл
        arr_str[11] = '"islands#darkBlueCircleDotIcon"'; //Иное - металл – пластик – стекло – бумага
        arr_str[12] = '"islands#pinkCircleDotIcon"'; //Металл – пластик – стекло – бумага
        arr_str[13] = '"islands#grayCircleDotIcon"'; //Пластик – бумага
        arr_str[14] = '"islands#brownCircleDotIcon"'; //Пластик – Стекло – бумага
        arr_str[15] = '"islands#darkGreenCircleDotIcon"'; //Одежда – металл – стекло – бумага
        arr_str[16] = '"islands#violetCircleDotIcon"'; //Металл – пластик – бумага
        arr_str[17] = '"islands#blackCircleDotIcon"'; //Стекло – бумага
        arr_str[18] = '"islands#yellowCircleDotIcon"'; //Опасные отходы – пластик – стекло – бумага
        arr_str[19] = '"islands#greenCircleDotIcon"'; //Опасные отходы – металл – бумага
        arr_str[20] = '"islands#orangeCircleDotIcon"'; //Металл – стекло – бумага
        arr_str[21] = '"islands#lightBlueCircleDotIcon"'; //Одежда – металл – пластик – стекло – бумага
        arr_str[22] = '"islands#oliveCircleDotIcon"'; //Иное – металл – пластик – бумага
        arr_str[23] = '"islands#blueDotIcon"'; //Бумага – стекло- пластик – металл
        arr_str[24] = '"islands#redDotIcon"'; //Металл – пластик – стекло
        arr_str[25] = '"islands#darkOrangeDotIcon"'; //Металл – стекло
        arr_str[26] = '"islands#nightDotIcon"'; //Иное – металл – пластик – стекло
        arr_str[27] = '"islands#darkBlueDotIcon"'; //Бумага – стекло – пластик – металл – одежда – одежда – иное – опасные отходы
        arr_str[28] = '"islands#pinkDotIcon"'; //Иное – металл
        arr_str[29] = '"islands#grayDotIcon"'; //Иное – одежда
        arr_str[30] = '"islands#brownDotIcon"'; //Предстоящие мероприятия
        arr_str[31] = '"islands#darkGreenDotIcon"'; //Пункты велопроката
        arr_str[32] = '"islands#violetDotIcon"'; //Эко-кафе
        arr_str[33] = '"islands#blackDotIcon"'; //Приюты для животных
        arr_str[34] = '"islands#yellowDotIcon"'; //Магазины с веганскими товарами
        arr_str[35] = '"islands#greenDotIcon"'; //Магазины с экотоварами

        var arr_res = [];
        for (var i = 0; i < arr.length; i++) {
            if (!arr[i]) {
                switch (i) {
                    case (0):
                        arr_res.push('options.preset != ' + arr_str[0] + ' && options.preset != ' + arr_str[7] + ' && options.preset != ' +
                            arr_str[8] + ' && options.preset != ' + arr_str[9] + ' && options.preset != ' + arr_str[18] + ' && options.preset != ' +
                            arr_str[19] + ' && options.preset != ' + arr_str[27]);
                        break;
                    case (1):
                        arr_res.push('options.preset != ' + arr_str[1] + ' && options.preset != ' + arr_str[10] + ' && options.preset != ' + arr_str[11] +
                            ' && options.preset != ' + arr_str[12] + ' && options.preset != ' + arr_str[13] + ' && options.preset != ' +
                            arr_str[14] + ' && options.preset != ' + arr_str[15] + ' && options.preset != ' +
                            arr_str[16] + ' && options.preset != ' + arr_str[17] + ' && options.preset != ' +
                            arr_str[18] + ' && options.preset != ' + arr_str[19] + ' && options.preset != ' +
                            arr_str[20] + ' && options.preset != ' + arr_str[21] + ' && options.preset != ' +
                            arr_str[22] + ' && options.preset != ' + arr_str[23] + ' && options.preset != ' +
                            arr_str[27]);
                        break;
                    case 2:
                        arr_res.push('options.preset != ' + arr_str[2] + ' && options.preset != ' + arr_str[7] +
                            ' && options.preset != ' + arr_str[9] + ' && options.preset != ' + arr_str[10] +
                            ' && options.preset != ' + arr_str[11] + ' && options.preset != ' + arr_str[12] +
                            ' && options.preset != ' + arr_str[15] + ' && options.preset != ' + arr_str[16] +
                            ' && options.preset != ' + arr_str[19] + ' && options.preset != ' + arr_str[20] +
                            ' && options.preset != ' + arr_str[21] + ' && options.preset != ' + arr_str[22] +
                            ' && options.preset != ' + arr_str[23] + ' && options.preset != ' + arr_str[24] +
                            ' && options.preset != ' + arr_str[25] + ' && options.preset != ' + arr_str[26] +
                            ' && options.preset != ' + arr_str[27] + ' && options.preset != ' + arr_str[28]);
                        break;
                    case 3:
                        arr_res.push('options.preset != ' + arr_str[3] + ' && options.preset != ' + arr_str[11] +
                            ' && options.preset != ' + arr_str[12] + ' && options.preset != ' + arr_str[13] +
                            ' && options.preset != ' + arr_str[14] + ' && options.preset != ' + arr_str[16] +
                            ' && options.preset != ' + arr_str[18] + ' && options.preset != ' + arr_str[21] +
                            ' && options.preset != ' + arr_str[22] + ' && options.preset != ' + arr_str[23] +
                            ' && options.preset != ' + arr_str[24] + ' && options.preset != ' + arr_str[26] +
                            ' && options.preset != ' + arr_str[27]);
                        break;
                    case 4:
                        arr_res.push('options.preset != ' + arr_str[4] + ' && options.preset != ' + arr_str[11] +
                            ' && options.preset != ' + arr_str[12] + ' && options.preset != ' + arr_str[14] +
                            ' && options.preset != ' + arr_str[15] + ' && options.preset != ' + arr_str[17] +
                            ' && options.preset != ' + arr_str[18] + ' && options.preset != ' + arr_str[20] +
                            ' && options.preset != ' + arr_str[21] + ' && options.preset != ' + arr_str[23] +
                            ' && options.preset != ' + arr_str[24] + ' && options.preset != ' + arr_str[25] +
                            ' && options.preset != ' + arr_str[26] + ' && options.preset != ' + arr_str[27]);
                        break;
                    case 5:
                        arr_res.push('options.preset != ' + arr_str[5] + ' && options.preset != ' + arr_str[15] +
                            ' && options.preset != ' + arr_str[21] + ' && options.preset != ' + arr_str[27] +
                            ' && options.preset != ' + arr_str[29]);
                        break;
                    case 6:
                        arr_res.push('options.preset != ' + arr_str[6] + ' && options.preset != ' + arr_str[8] +
                            ' && options.preset != ' + arr_str[9] + ' && options.preset != ' + arr_str[11] +
                            ' && options.preset != ' + arr_str[22] + ' && options.preset != ' + arr_str[26] +
                            ' && options.preset != ' + arr_str[27] + ' && options.preset != ' + arr_str[28] +
                            ' && options.preset != ' + arr_str[29]);
                        break;
                    case 7:
                        arr_res.push('options.preset != ' + arr_str[30]);
                        break;
                    case 8:
                        arr_res.push('options.preset != ' + arr_str[31]);
                        break;
                    case 9:
                        arr_res.push('options.preset != ' + arr_str[32]);
                        break;
                    case 10:
                        arr_res.push('options.preset != ' + arr_str[33]);
                        break;
                    case 11:
                        arr_res.push('options.preset != ' + arr_str[34]);
                        break;
                    case 12:
                        arr_res.push('options.preset != ' + arr_str[35])
                }
            }
        }

        var str = '';
        for (var j = 0; j < arr_res.length; j++) {
            str += arr_res[j] + ' ';
        }

        objectManager.setFilter(str);
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
        url: "map_files/data_for_map.json"
    }).done(function (data) {
        objectManager.add(data);
    });
}