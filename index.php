<?php

use app\Data;

include "app/autoload.php";

$change = new Data();
$data = $change->getData();
$races = $data['arr'][0];
$pagination = $data['paginate'];
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Турнирная таблица</title>

    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
    <section class="header">
        <div class="container">
            <h1 class="header__name">Турнирная таблица</h1>
        </div>
    </section>

    <section class="main">
        <div class="container container_table">
            <table class="main__table" id="table">
                <tr>
                    <th rowspan="2" colspan="1" class="bg_black">Место</th>
                    <th rowspan="2" colspan="1" class="bg_black">Гонщик</th>
                    <th rowspan="2" colspan="1" class="bg_gray">Город</th>
                    <th rowspan="2" colspan="1" class="bg_gray">Машина</th>
                    <th rowspan="1" class="bg_gray"
                        colspan="<?php echo($change->maxRaces)?>">
                        Результаты заездов
                    </th>
                    <th rowspan="2" colspan="1" class="sort bg_black
                    <?php
                    if (!isset($_GET['sort']))
                        echo "sort_b-s";
                    else
                    if ($_GET['sort'] == 'sum')
                        if (isset($_GET['sort-method']))
                            echo "sort_" . $_GET['sort-method'];
                        else
                            echo "sort_b-s";
                    ?>"
                    onclick="toSort(this)" data-id="sum">
                        Итого
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                             class="sort__svg" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M7.776 5.553a.5.5 0 0 1 .448 0l6 3a.5.5 0 1 1-.448.894L8 6.56 2.224 9.447a.5.5 0 1 1-.448-.894l6-3z"/>
                        </svg>
                    </th>
                </tr>
                <tr>
                    <?php
                    for ($i = 1; $i - 1 < $change->maxRaces; $i++)
                    {
                        $class = "";
                        if (isset($_GET['sort']))
                            if ($_GET['sort'] == $i)
                                if (isset($_GET['sort-method']))
                                    $class = "sort_" . $_GET['sort-method'];
                                else
                                    $class = "sort_b-s";

                        echo("<th rowspan='1' colspan='1' class='sort bg_gray " . $class .
                            "' onclick='toSort(this)' data-id='" . $i . "'>
                                $i
                                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' 
                                class='sort__svg' viewBox='0 0 16 16'>
                                    <path fill-rule='evenodd' 
                                    d='M7.776 5.553a.5.5 0 0 1 .448 0l6 3a.5.5 0 1 1-.448.894L8 6.56 2.224 9.447a.5.5 0 1 1-.448-.894l6-3z'/>
                                </svg>
                            </th>");
                    }
                    ?>
                </tr>

                <?php
                for ($i = 1; $i - 1 < count($races); $i++)
                {
                    echo("
                    <tr>
                        <th class='bg_light-gray table__top'>" . $i + ($pagination['page'] * $pagination['maxEl']) - $pagination['maxEl'] . "</th>
                        <td class='bg_light-gray'>" . $races[$i-1]['name'] . "</td>
                        <td>" . $races[$i-1]['city'] . "</td>
                        <td>" . $races[$i-1]['car'] . "</td>"
                    );

                    for ($y = 0; $y < $change->maxRaces; $y++)
                    {
                        if (isset($races[$i-1]['result'][$y]))
                            echo("<td>" . $races[$i-1]['result'][$y] . "</td>");
                        else
                            echo("<td>0</td>");
                    }

                    echo("<td class='bg_light-gray'>" . $races[$i-1]['sum'] . "</td>");

                }
                ?>
            </table>
        </div>
        <div class="pagination <?php echo($pagination['pages'] > 1) ? "" : "display-none"?>">
            <button class="btn <?php echo($pagination['page'] == 1) ? "display-none" : ""?>" id="page-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                     fill="currentColor" class="pagination__btn pagination__back" viewBox="0 0 15 15">
                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
            </button>
            <span class="pagination__page" id="page" data-page="<?php echo($pagination['page']); ?>">
                <?php echo($pagination['page']); ?>
            </span>
            <button class="btn <?php echo($pagination['page'] == $pagination['pages']) ? "display-none" : ""?>" id="page-next">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                     fill="currentColor" class="pagination__btn pagination__next" viewBox="0 0 15 15">
                    <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                </svg>
            </button>
        </div>
    </section>

    <script>
        <?php if (isset($_GET['sort'])) echo("let sort = '" . $_GET['sort'] . "';"); ?>
        <?php if (isset($_GET['sort-method'])) echo("let sort_method = '" . $_GET['sort-method'] . "';"); ?>
        <?php if (isset($_GET['page'])) echo("let page = " . $_GET['page'] . ";"); ?>
    </script>
    <script src="scripts/main.js"></script>
</body>
</html>