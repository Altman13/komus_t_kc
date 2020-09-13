<?php

namespace Komus;

class TimeZone
{
    private $db;
    private $ret;
    public function __construct($db)
    {
        $this->db = $db;
        $this->ret =array('data' =>'', 'error_text'=> '');
    }
    
    public function create($timezone)
    {
        $timezones = json_decode($timezone);
        //TODO: дописать запрос на инсерт временных зон
        $this->db->prepare("INSERT INTO ");
        foreach ($timezones as $tz) { }
    }
    
    public function read()
    {
        $all_time_zones = $this->db->prepare("SELECT * FROM timezone");
        try {
            $all_time_zones->execute();
        } catch (\Throwable $th) {
            die('Произошла ошибка при выборке временных зон ' . $th->getMessage());
        }
        $timezone = $all_time_zones->fetchAll();
        //echo json_encode($timezone);
        return json_encode($timezone);
    }
    
    public function update($id)
    {
        # code...
    }
    
    public function delete($id)
    {
        # code...
    }
}
