<?php

namespace Komus;

class Region
{
    private $db;
    private $ret;
    public function __construct($db)
    {
        $this->db = $db;
        $this->ret =array('data' =>'', 'error_text'=> '');
    }
    
    public function create($regions)
    {
        # code...
    }

    public function read()
    {
        $all_regions = $this->db->prepare("SELECT * FROM regions");
        try {
            $all_regions->execute();
        } catch (\Throwable $th) {
            $this->ret = 'Произошла ошибка при выборке регионов ' . $th->getMessage();
        }
        $this->ret['data'] = $all_regions->fetchAll();
        return json_encode($this->ret, JSON_UNESCAPED_UNICODE);
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
