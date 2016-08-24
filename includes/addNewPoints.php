<?php

function addNewPoint()
{
    global $db, $app;

    if (isset($_POST['submit'])) {  //Проверка, заполнена ли форма
        $coordinates = $_POST['coords'];    //Строка с координатами точки в формате "x, y"
        $title = $_POST['name'];

        $schedule = $_POST['schedule'];
        $description = $_POST['description'];
        $info = array();


        switch ($_POST['point_type']) {
            case "null":
                print_r("Не выбран тип точки");
                break;
            case "recycle_point": {
                $acception = $_POST['acception'];   //Массив типов вторсырья, которые принимает точка
                $info = array('description' => $description);


                break;
            }
            case "Eco_organization": {
                $slug = $_POST['slug'];
                $website = $_POST['site'];
                $tel = $_POST['tel'];
                $mail = $_POST['email'];
                $info = array(
                    "slug" => $slug,
                    "website" => $website,
                    "mail" => $mail,
                    "tel" => $tel
                );

                if ($_FILES['userfile']['error'] != 0) {
                    print_r('Ошибка при закрузке картинке № ' . $_FILES['userfile']['error']);
                }

                $uploadDIR = 'images/organizations';
                $tmp = explode('.', $_FILES['userfile']['name']);
                $extension = $tmp[count($tmp) - 1];
                $filename = getRandomName($uploadDIR, $extension);

                $target = $uploadDIR . '/' . $filename . '.' . $extension;
                move_uploaded_file($_FILES['userfile']['tmp_name'], $target);
                break;
            }


        }


    }
}

function getRandomName($path, $extension)
{
    $extension = $extension ? '.' . $extension : '';
    $path = $path ? $path . '/' : '';

    do {
        $name = md5(microtime() . rand(0, 9999));
        $file = $path . $name . $extension;
    } while (file_exists($file));

    return $name;
}