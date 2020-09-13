<?php

namespace Komus;

use PDO;

class Login
{
    private $db;
    private $ret;
    public function __construct($db)
    {
        $this->db = $db;
        $this->ret =array('data' =>'', 'error_text'=> '');
    }
    public function sign($user_password, $user_name)
    {
        try {
            //TODO: валидация
            $payload = [
                "user" => $user_name,
                "passwords" => $user_password,
            ];
            $token = \Firebase\JWT\JWT::encode($payload, "thisissecret", "HS256");

            $users_data = $this->db->prepare("SELECT users.groups_id, token, CONCAT(users.firstname,' ', users.lastname) as 
                                            user_fio, users.id FROM users
                                            WHERE users.token=:token");
            $users_data->bindParam(':token', $token, PDO::PARAM_STR);
            $users_data->execute();
            $user_data = $users_data->fetch();
        } catch (\Throwable $th) {
            $this->ret['error_text'] = 'Произошла ошибка при выборе пользователя из базы ' . $th->getMessage();
        }
        if ($token == $user_data['token']) {
            $user_id = $user_data['id'];
            $user_group = $user_data['groups_id'];
            $user_token = $user_data['token'];
            $user_fio = $user_data['user_fio'];
            $token_exp  = date("Y-m-d H:i:s", strtotime("+9 hours"));
            $u = array('user_id', 'user_group', 'user_token', 'token_exp', 'user_fio');
            $user = compact($u);
            $this->ret = $user;
        } else {
            $this->ret['error_text'] = 'Введенны некорректные данные для авторизации';
        }
        return $this->ret;
    }
}
