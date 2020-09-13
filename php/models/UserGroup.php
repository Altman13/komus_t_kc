<?php

namespace Komus;

class UserGroup
{
    private $db;
    private $ret;
    public function __construct($db)
    {
        $this->db = $db;
        $this->ret =array('data' =>'', 'error_text'=> '');      
    }
    
    public function create($group_users)
    {
        //TODO: дописать запрос на инсерт групп пользователей
        $this->db->prepare("INSERT INTO ");
    }
    
    public function read()
    {
        $all_groups_users = $this->db->prepare("SELECT * FROM groups_users");
        try {
            $all_groups_users->execute();
        } catch (\Throwable $th) {
            die('Произошла ошибка при выборке групп пользователей ' . $th->getMessage());
        }
        $groups_users = $all_groups_users->fetchAll();
        //echo json_encode($group_users);
        return json_encode($groups_users);
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
