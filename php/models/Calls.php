<?php

namespace Komus;

class Calls
{
    private $db;
    private $ret;
    public function __construct($db)
    {
        $this->db = $db;
        $this->ret =array('data' =>'', 'error_text'=> '');
    }
    public function create()
    {
        # code...
    }
    
    public function read()
    {
        try {
            $all_contacts = $this->db->prepare("SELECT * FROM contacts WHERE contacts.allow_call='1' and contacts.id >5700 LIMIT 1");
            $all_contacts->execute();
            $contacts = $all_contacts->fetchAll();
            $this->ret['data'] = $contacts;
            //$this->lockContacts($contacts);
        } catch (\Throwable $th) {
            $this->ret['error_text'] = 'Произошла ошибка при выборке контактов ' . $th->getMessage();
        }
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
