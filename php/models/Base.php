<?php

namespace Komus;

class Base
{
    private $db;
    private $total_rows;
    private $ret;
    public function __construct($db)
    {
        $this->db = $db;
        $this->ret = array('data' => '', 'error_text' => '');
    }
    public function create($files)
    {
        //TODO : убедиться в том, что не может быть пропущенных пустых столбцов в файле для импорта базы,
        //!иначе загрузка произойдет до первого пустого столбца заголовка
        try {
            $upload_file = $this->uploadFile($files);
            $obj_php_excel = $this->objectXls($upload_file);
            $columns_name = array();
            $data = array();
            $query_insert_columns_name = "INSERT INTO contacts (";
            $i = 0;
            do {
                $column_name_rus = $obj_php_excel->getActiveSheet()->getCellByColumnAndRow($i, 1)->getValue();
                $column_name_temp = $this->translitColumn($column_name_rus);
                $column_name_translit = explode('-', $column_name_temp, 2);
                $column_name_translit = preg_replace("/[^a-zA-ZА\s]/", '', $column_name_translit[0]);
                array_push($columns_name, $column_name_translit);
                $query_insert_columns_name .= '`' . $column_name_translit . '`, ';
                $data[$column_name_translit] = (string)$column_name_rus;
                $alter_table_contacts = $this->db->prepare("ALTER TABLE contacts ADD IF NOT EXISTS $column_name_translit VARCHAR(255)");
                $alter_table_contacts->execute();
                $i++;
            } while ($column_name_rus != NULL);
            $this->saveArrayToFile($data);
            $this->insertDb($query_insert_columns_name, $columns_name, $upload_file);
        } catch (\Throwable $th) {
            $this->ret['error_text'] = 'Произошла ошибка при добавлении полей в таблицу contacts ' . $th->getMessage() . PHP_EOL;
        }
        return json_encode($this->ret, JSON_UNESCAPED_UNICODE);
    }
    public function uploadFile($files)
    {
        $directory = __DIR__ . '/../files/';
        $i = 1;
        foreach ($files as $f) {
            move_uploaded_file($f, $directory . "$i.xlsx");
            $i++;
        }
        $uploadfile = $directory . '1.xlsx';
        return $uploadfile;
    }
    public function objectXls($upload_file)
    {
        $obj_php_excel = new \PHPExcel();
        $input_file_type = \PHPExcel_IOFactory::identify($upload_file);
        $obj_reader = \PHPExcel_IOFactory::createReader($input_file_type);
        if ($input_file_type == 'OOCalc') {
            $obj_reader->setLoadSheetsOnly('Лист1');
        }
        $obj_php_excel = $obj_reader->load($upload_file);
        $worksheetData = $obj_reader->listWorksheetInfo($upload_file);
        $this->total_rows = $worksheetData[0]['totalRows'];
        return $obj_php_excel;
    }

    public function translitColumn($column_name_rus)
    {
        $column_name_rus = (string) $column_name_rus;
        $column_name_rus = strip_tags($column_name_rus);
        $column_name_rus = str_replace(array("\n", "\r"), " ", $column_name_rus);
        $column_name_rus = preg_replace("/\s+/", ' ', $column_name_rus);
        $column_name_rus = trim($column_name_rus);
        $column_name_rus = function_exists('mb_strtolower') ? mb_strtolower($column_name_rus) : strtolower($column_name_rus);
        $column_name_rus = strtr($column_name_rus, array(
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e',
            'ж' => 'j', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
            'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'shch', 'ы' => 'y', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', 'ъ' => '', 'ь' => ''
        ));
        // очищаем строку от недопустимых символов
        $column_name_rus = preg_replace("/[^0-9a-z-_ ]/i", "", $column_name_rus);
        $column_name_translit = str_replace(" ", "-", $column_name_rus);
        return $column_name_translit;
    }
    public function saveArrayToFile($data)
    {
        $data_json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $fn = "columns_name.json";
        file_put_contents($fn, $data_json);
    }

    public function insertDb($query_insert_columns_name, $columns_name, $uploadfile)
    {
        try {
            $obj_php_excel = $this->objectXls($uploadfile);
            $query_insert_columns_name = substr($query_insert_columns_name, 0, -4);
            $query_insert_columns_name = $query_insert_columns_name . '`regions_id`' . ',' . '`users_id`' . ')';
            for ($i = 1; $i < $this->total_rows; $i++) {
                $query_insert_columns_values = 'VALUES (';
                for ($column_num = 0; $column_num < count($columns_name); $column_num++) {
                    $columns_value = $obj_php_excel->getActiveSheet()->getCellByColumnAndRow($column_num, $i)->getValue();
                    $query_insert_columns_values .= '\'' . $columns_value . '\', ';
                }
                $query_insert_columns_values = substr($query_insert_columns_values, 0, -4);
                $query_insert_columns_values = substr_replace($query_insert_columns_values, ',\'1\',\'1\')', -2, -1);
                $insert_row = $this->db->prepare($query_insert_columns_name . $query_insert_columns_values);
                $insert_row->execute();
            }
        } catch (\Throwable $th) {
            $this->ret['error_text'] =  'Произошла ошибка при добавлении записи в таблицу contacts ' . $th->getMessage() . PHP_EOL;
        }
        return json_encode($this->ret, JSON_UNESCAPED_UNICODE);
    }
}
