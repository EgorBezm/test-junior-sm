<?php

namespace app;

class Data
{

    public $maxRaces = 0;
    public $sortCol = "sum";
    public $sortMethod = "b-s";
    public $page = 1;
    public $maxEl = 10;

    public function __construct()
    {
        if (isset($_GET['sort']))
            $this->sortCol = $_GET['sort'];
        if (isset($_GET['sort-method']))
            $this->sortMethod = $_GET['sort-method'];
        if (isset($_GET['page']))
            $this->page = $_GET['page'];
        if (isset($_GET['max-el']))
            $this->maxEl = $_GET['max-el'];
    }

    public function getData()
    {
        $attempts = new ReadingFile('data_attempts.json', 'data');
        $data_cars = new ReadingFile('data_cars.json', 'data');

        return $this->pagination($this->sorting(
            $this->union(
                $attempts->convertToAssociativeArray(),
                $data_cars->convertToAssociativeArray()
            )
        ), $this->page, $this->maxEl);
    }

    private function sorting($arr)
    {
        if ($this->sortMethod == "b-s")
            usort($arr, [Data::class, "sorting__bs"]);
        if ($this->sortMethod == "s-b")
            usort($arr, [Data::class, "sorting__sb"]);

        return $arr;
    }

    private function sorting__bs($a, $b) // сортировка от большего к меньшему
    {
        if ($this->sortCol == "sum")
            return ($b[$this->sortCol] - $a[$this->sortCol]);
        return ($b["result"][$this->sortCol - 1] - $a["result"][$this->sortCol - 1]);
    }

    private function sorting__sb($a, $b) // сортировка от меньшего к большему
    {
        if ($this->sortCol == "sum")
            return ($a[$this->sortCol] - $b[$this->sortCol]);

        return ($a["result"][$this->sortCol - 1] - $b["result"][$this->sortCol - 1]);
    }

    private function union($arr1, $arr2)
    {
        $arr = [];
        $res = [];

        foreach ($arr1 as $item){
            $res[$item['id']]['result'][] = $item['result'];
        }

        foreach ($arr2 as $item){
            $this -> numberOfRaces($res[$item['id']]['result']);

            $item['result'] = $res[$item['id']]['result'];
            $item['sum'] = $this -> sumOfRaces($res[$item['id']]['result']);

            $arr[] = $item;
        }

        return $arr;
    }

    private function numberOfRaces($races)
    {
        if ($this->maxRaces < count($races))
            $this->maxRaces = count($races);
    }
    
    private function sumOfRaces($races)
    {
        $sum = 0;

        foreach ($races as $race)
        {
            $sum += $race;
        }

        return $sum;
    }

    private function pagination($data, $page = 1, $maxEl = 10)
    {
        $newArr = array_slice($data, $maxEl * $page - $maxEl, $maxEl);
        return ['arr' => [$newArr],
            'paginate' => [
                'page' => $page,
                'pages' => round(count($data) / $maxEl),
                'maxEl' => $maxEl
            ]
        ];
    }
}