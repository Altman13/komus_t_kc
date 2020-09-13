<?php

namespace Komus;

require "config/config.php";
require "vendor/autoload.php";

class User
{
    private $db;
    private $resp;
    private $ret;
    public function __construct($db)
    {
        $this->db = $db;
        $this->ret =array('data' =>'', 'error_text'=> '');
    }

    public function create($files)
    {
        //TODO: Реализовать подгрузку пользователей сразу из центрального проекта
        try {
        $directory = __DIR__ . '/../files/';
        foreach ($files as $f) {
            move_uploaded_file($f, $directory . "operators.xlsx");
        }
        //TODO: удалить файл
        $uploadfile = $directory . 'operators.xlsx';
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($uploadfile);
        $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        if ($inputFileType == 'OOCalc') {
            $objReader->setLoadSheetsOnly('Операторы');
        }
            $objPHPExcel = $objReader->load($uploadfile);
        } catch (\Throwable $th) {
            die('Произошла ошибка при попытке чтения файла с операторами ' . $th->getMessage() . PHP_EOL);
        }
        $operators = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        foreach ($operators as $operator) {
            $operator_fist_name = $operator['C'];
            $operator_last_name = $operator['B'];
            $operator_login     = $operator['E'];
            $operator_depass    = $operator['F'];

            $payload = [
                "user" => $operator_login,
                "passwords" => $operator_depass,
            ];

            $token = \Firebase\JWT\JWT::encode($payload, "thisissecret", "HS256");
            $insert_users = $this->db->prepare("INSERT IGNORE INTO users (login, firstname, lastname, depass, token,
                                                            timezone_id, groups_id)
                                                                VALUES (:operator_login, :operator_fist_name, :operator_last_name, :depass, :token,
                                                            1, 1)");
            $insert_users->bindParam(':operator_login', $operator_login, \PDO::PARAM_STR);
            $insert_users->bindParam(':operator_fist_name', $operator_fist_name, \PDO::PARAM_STR);
            $insert_users->bindParam(':operator_last_name', $operator_last_name, \PDO::PARAM_STR);
            $insert_users->bindParam(':token', $token, \PDO::PARAM_STR);
            // //TODO : убрать после отладки depass из базы
            $insert_users->bindParam(':depass', $operator_depass, \PDO::PARAM_STR);
            try {
                $insert_users->execute();
            } catch (\Throwable $th) {
                die('Произошла ошибка при добавлении оператора в базу ' . $th->getMessage());
            }
        }
        echo 'Операторы добавлены. ' . PHP_EOL;
    }
    /**
     * Read
     *
     * @return void
     */
    public function getAllOperators()
    {
        try {
            $all_users = $this->db->prepare("SELECT CONCAT(users.firstname,' ', users.lastname) as operators FROM users");
            $all_users->execute();
        } catch (\Throwable $th) {
            die('Произошла ошибка при выборке контактов ' . $th->getMessage());
        }
        $users = $all_users->fetchAll();
        return json_encode($users);
    }

    public function setStOperator($operator)
    {
        $fio = explode(" ", $operator);
        $first_name = $fio[0]; 
        $last_name  = $fio[1];
        $update_role_user = $this->db->prepare("UPDATE `users` SET `groups_id`='2' 
            WHERE users.firstname =:first_name AND users.lastname =:last_name");
        $update_role_user->bindParam(':first_name', $first_name, \PDO::PARAM_STR);
        $update_role_user->bindParam(':last_name', $last_name, \PDO::PARAM_STR);
        $update_role_user->execute();
        $count = $update_role_user->rowCount();
        if ($count == 0) {
            $this->resp = false;
        }
        else {
            $this->resp = true;
        }
        return $this->resp;
    }
    /**
     * Delete
     *
     * @param  mixed $id
     *
     * @return void
     */
    public function delete($id)
    {
        # code...
    }
}
