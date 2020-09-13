<?php

namespace Komus;

class Report
{
    private $db;
    private $ret;
    public function __construct($db)
    {
        $this->db = $db;
        $this->ret =array('data' =>'', 'error_text'=>'');
    }
    public function read()
    {
        try {
            $get_report = $this->db->prepare("SELECT calls.status, contacts.id, contacts.phone, contacts.fio, contacts.naimenovanie,
                                                    contacts.organization, CONCAT(users.firstname, ' ', users.lastname) as fio 
                                            FROM contacts
                                            LEFT JOIN calls ON contacts.id=calls.contacts_id
                                            LEFT JOIN users ON users.id =contacts.users_id
                                            GROUP BY calls.contacts_id");
            $get_report->execute();
        } catch (\Throwable $th) {
            $this->ret['error_text'] = 'Произошла ошибка при выборе истории звонков и попыток дозвона ' . $th->getMessage();
        }
        $this->ret['data'] = $get_report->fetchAll(\PDO::FETCH_ASSOC);
        return $this->ret;
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
