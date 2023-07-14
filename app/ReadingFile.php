<?php

namespace app;

class ReadingFile
{
    protected static $data;

    protected $name;
    protected $path;

    public function __construct($name, $path=null)
    {
        $this->name=$name;
        $this->path=$path;

        $this->data = ($this->read());
    }

    protected function read()
    {
        return file_get_contents(
            dirname(__DIR__) . "\\" .
            $this->path . "\\" .
            $this->name
        );
    }

    public function convertToObject()
    {
        return json_decode($this->data);
    }

    public function convertToAssociativeArray()
    {
        return json_decode($this->data, true);
    }

}
