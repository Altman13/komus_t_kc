<?php

require "config/config.php";
require "vendor/autoload.php";
$faker = Faker\Factory::create();

global $groups_users_id;
global $timezone_id;
global $regions_id;
global $contact_id;
global $mail_log;

$timezone = array(
    'Africa/Abidjan',
    'Africa/Accra',
    'Africa/Addis_Ababa',
    'Africa/Algiers',
    'Africa/Asmara',
    'Africa/Bamako',
    'Africa/Bangui',
);
foreach ($timezone as $tz) {
    $tzone = $db->prepare("INSERT INTO `komus_new`.`timezone` (`zone`) VALUES (:tz)");
    $tzone->bindParam(':tz', $tz, PDO::PARAM_STR);
    try {
        $tzone->execute();
    } catch (\Throwable $th) {
        die('Произошла ошибка при добавлении временной зоны ' . $th->getMessage());
    }
}
echo 'Временные зоны добавлены ' . PHP_EOL;

//Выбираем значения по внешним ключам из связанной таблицы для заполнения
$select_timezone_id = $db->prepare("SELECT timezone.id FROM timezone");
try {
    $select_timezone_id->execute();
} catch (\Throwable $th) {
    die('Произошла ошибка при выборе внешних ключей из таблицы timezone ' . $th->getMessage());
}
$tz_id = $select_timezone_id->fetchAll(PDO::FETCH_ASSOC);
$min_tz_id = ($tz_id[0]['id']);
end($tz_id);
$last_key = key($tz_id);
$max_tz_id = ($tz_id[$last_key]['id']);
for ($i = 0; $i < 100; $i++) {
    $region_name = $faker->city;
    $region_code = $faker->citySuffix;
    $region_subcode = $faker->randomNumber();
    $timezone_id = $faker->numberBetween($min = $min_tz_id, $max = $max_tz_id);
    $regions_insert = $db->prepare("INSERT INTO `komus_new`.`regions` 
                                (`name`, `code`, `subcode`, `timezone_id`) 
                    VALUES (:region_name, :region_code, :region_subcode, :timezone_id)");
    $regions_insert->bindParam(':region_name', $region_name, PDO::PARAM_STR);
    $regions_insert->bindParam(':region_code', $region_code, PDO::PARAM_STR);
    $regions_insert->bindParam(':region_subcode', $region_subcode, PDO::PARAM_STR);
    $regions_insert->bindParam(':timezone_id', $timezone_id, PDO::PARAM_STR);

    try {
        $regions_insert->execute();
    } catch (\Throwable $th) {
        die('Произошла ошибка при добавлении регионов ' . $th->getMessage());
    }
}
echo 'Регионы добавлены ' . PHP_EOL;

//Выбираем значения по внешним ключам из связанной таблицы для заполнения
$select_regions_id = $db->prepare("SELECT regions.id FROM regions");
try {
    $select_regions_id->execute();
} catch (\Throwable $th) {
    die('Произошла ошибка при выборе внешних ключей из таблицы regions ' . $th->getMessage());
}
$rg_id = $select_regions_id->fetchAll(PDO::FETCH_ASSOC);
$min_rg_id = ($rg_id[0]['id']);
end($rg_id);
$last_key = key($rg_id);
$max_rg_id = ($rg_id[$last_key]['id']);
$groups_user = array('оператор', 'старший оператор', 'администратор');
foreach ($groups_user as $gr_user) {
    $regions_id = $faker->numberBetween($min = $min_rg_id, $max = $max_rg_id);
    $groups_user_insert = $db->prepare("INSERT INTO `komus_new`.`groups_users` (`groups`) VALUES (:gr_u)");
    $groups_user_insert->bindParam(':gr_u', $gr_user, PDO::PARAM_STR);
    try {
        $groups_user_insert->execute();
    } catch (\Throwable $th) {
        die('Произошла ошибка при добавлении групп пользователей ' . $th->getMessage());
    }
}
echo 'Группы пользователей добавлены ' . PHP_EOL;

//Выбираем значения по внешним ключам из связанной таблицы для заполнения
$select_groups_users_id = $db->prepare("SELECT groups_users.id FROM groups_users");
try {
    $select_groups_users_id->execute();
} catch (\Throwable $th) {
    die('Произошла ошибка при выборе внешних ключей из таблицы groups_users ' . $th->getMessage());
}
$gu_id = $select_groups_users_id->fetchAll(PDO::FETCH_ASSOC);
$min_gu_id = ($gu_id[0]['id']);
end($gu_id);
$last_key = key($gu_id);
$max_gu_id = ($gu_id[$last_key]['id']);

for ($i = 0; $i < 100; $i++) {
    $timezone_id = $faker->numberBetween($min = $min_tz_id, $max = $max_tz_id);
    $groups_id = $faker->numberBetween($min = $min_gu_id, $max = $max_gu_id);
    $user_login = $faker->name;
    $user_password = $faker->password();
    $user_email = $faker->email;
    $user_firstname = $faker->name();
    $user_lastname = $faker->lastName;
    $user_gender = 'не указано';
    $user_birthdate = $faker->dateTime($max = 'now', $timezone = null);
    $u_birthdate = date_format($user_birthdate, 'Y-m-d H:i:s');
    $user_lastvisit = $faker->dateTime($max = 'now', $timezone = null);
    $u_last_visit = date_format($user_lastvisit, 'Y-m-d H:i:s');
    $user_ban = null;
    $insert_user = $db->prepare("INSERT INTO `komus_new`.`users` (`login`, `token`, `email`, 
                                                            `firstname`, `lastname`, `gender`,
                                                            `birthdate`, `lastvisit`, `ban`,
                                                            `timezone_id`, `groups_id`) 
                            VALUES (:user_login, :user_password, :user_email, 
                                    :user_firstname, :user_lastname, :user_gender, 
                                    :user_birthdate, :user_lastvisit, 
                                    :user_ban, :timezone_id, :groups_id)");
    $insert_user->bindParam(':user_login', $user_login, PDO::PARAM_STR);
    $insert_user->bindParam(':user_password', $user_password, PDO::PARAM_STR);
    $insert_user->bindParam(':user_email', $user_email, PDO::PARAM_STR);
    $insert_user->bindParam(':user_firstname', $user_firstname, PDO::PARAM_STR);
    $insert_user->bindParam(':user_lastname', $user_lastname, PDO::PARAM_STR);
    $insert_user->bindParam(':user_gender', $user_gender, PDO::PARAM_STR);
    $insert_user->bindParam(':user_birthdate', $u_birthdate, PDO::PARAM_STR);
    $insert_user->bindParam(':user_lastvisit', $u_last_visit, PDO::PARAM_STR);
    $insert_user->bindParam(':user_ban', $user_ban, PDO::PARAM_STR);
    $insert_user->bindParam(':timezone_id', $timezone_id, PDO::PARAM_STR);
    $insert_user->bindParam(':groups_id', $groups_id, PDO::PARAM_STR);
    try {
        $insert_user->execute();
    } catch (\Throwable $th) {
        die('Произошла ошибка при добавлении пользователей ' . $th->getMessage());
    }
}
echo 'Пользователи добавлены ' . PHP_EOL;
for ($i = 0; $i < 100; $i++) {
    $regions_id = $faker->numberBetween($min = $min_rg_id, $max = $max_rg_id);
    $city = $faker->city;
    $company = $faker->company;
    $streetAddress = $faker->streetAddress;
    $name = $faker->name;
    $phoneNumber = $faker->phoneNumber;
    $email = $faker->email;
    $category = "category " . $faker->word;
    $subcategory = "subcategory " . $faker->word;
    $question = "question " . $faker->word;
    $comment = "comment " . $faker->word;
    $groups_users_id = $faker->numberBetween($min = $min_gu_id, $max = $max_gu_id);
    $cont_insert = $db->prepare("INSERT INTO `komus_new`.`contacts` (`creation_time`, `city`, `organization`, 
                                `address`, `fio`, `phone`, `email`, `category`, `subcategory`, `question`, 
                                `comment`, `regions_id`, `users_id`) 
                                VALUES (NOW(), :city, :company, 
                                :streetAddress, :name, :phoneNumber, :email, :category, :subcategory,
                                :question, :comment, :regions_id, :users_id)");
    $cont_insert->bindParam(':city', $city, PDO::PARAM_STR);
    $cont_insert->bindParam(':company', $company, PDO::PARAM_STR);
    $cont_insert->bindParam(':streetAddress', $streetAddress, PDO::PARAM_STR);
    $cont_insert->bindParam(':name', $name, PDO::PARAM_STR);
    $cont_insert->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
    $cont_insert->bindParam(':email', $email, PDO::PARAM_STR);
    $cont_insert->bindParam(':category', $category, PDO::PARAM_STR);
    $cont_insert->bindParam(':subcategory', $subcategory, PDO::PARAM_STR);
    $cont_insert->bindParam(':question', $question, PDO::PARAM_STR);
    $cont_insert->bindParam(':comment', $comment, PDO::PARAM_STR);
    $cont_insert->bindParam(':regions_id', $regions_id, PDO::PARAM_STR);
    $cont_insert->bindParam(':users_id', $groups_users_id, PDO::PARAM_STR);
    try {
        $cont_insert->execute();
    } catch (\Throwable $th) {
        die('Произошла ошибка при добавлении контактов ' . $th->getMessage());
    }
}
echo 'Контакты добавлены ' . PHP_EOL;
//Выбираем значения по внешним ключам из связанной таблицы для заполнения
$contacts_id = $db->prepare("SELECT contacts.id FROM contacts");
try {
    $contacts_id->execute();
} catch (\Throwable $th) {
    die('Произошла ошибка при выборе внешних ключей из таблицы timezone ' . $th->getMessage());
}
$ct_id = $contacts_id->fetchAll(PDO::FETCH_ASSOC);
$min_ct_id = ($ct_id[0]['id']);
end($ct_id);
$last_key = key($ct_id);
$max_ct_id = ($ct_id[$last_key]['id']);
$mail_log = 'письмо отправлено';

for ($i = 0; $i < 100; $i++) {
    $mail_send = $faker->dateTime($max = 'now', $timezone = null);
    $mail_send_date = date_format($mail_send, 'Y-m-d H:i:s');
    $contact_id = $faker->numberBetween($min = $min_ct_id, $max = $max_ct_id);
    $maillog_insert = $db->prepare("INSERT INTO `komus_new`.`maillog` (`date`, `log`, `contacts_id`) 
                                VALUES (:mail_send_date, :mail_log, :contact_id);");
    $maillog_insert->bindParam(':mail_send_date', $mail_send_date, PDO::PARAM_STR);
    $maillog_insert->bindParam(':mail_log', $mail_log, PDO::PARAM_STR);
    $maillog_insert->bindParam(':contact_id', $contact_id, PDO::PARAM_STR);
    try {
        $maillog_insert->execute();
    } catch (\Throwable $th) {
        die('Произошла ошибка при добавлении лога почты ' . $th->getMessage());
    }
}
echo 'Лог отправленных писем добавлен' . PHP_EOL;
for ($i = 0; $i < 100; $i++) {
    $contact_id = $faker->numberBetween($min = $min_ct_id, $max = $max_ct_id);
    $begin_time = $faker->dateTime($max = 'now', $timezone = null);
    $b_time = date_format($begin_time, 'Y-m-d H:i:s');
    $end_time = $faker->dateTime($max = 'now', $timezone = null);
    $e_time = date_format($end_time, 'Y-m-d H:i:s');
    $recall_time = $faker->dateTime($max = 'now', $timezone = null);
    $r_time = date_format($recall_time, 'Y-m-d H:i:s');
    $status_call = 'перезвон';
    $call_insert = $db->prepare("INSERT INTO `komus_new`.`calls` (`begin_time`, `end_time`, `recall_time`, 
                                                                `status`, `contacts_id`) 
                                VALUES (:b_time, :e_time, 
                                :r_time, :status_call, :contact_id)");
    $call_insert->bindParam(':b_time', $b_time, PDO::PARAM_STR);
    $call_insert->bindParam(':e_time', $e_time, PDO::PARAM_STR);
    $call_insert->bindParam(':r_time', $r_time, PDO::PARAM_STR);
    $call_insert->bindParam(':status_call', $status_call, PDO::PARAM_STR);
    $call_insert->bindParam(':contact_id', $contact_id, PDO::PARAM_STR);
    try {
        $call_insert->execute();
    } catch (\Throwable $th) {
        die('Произошла ошибка при добавлении звонков' . $th->getMessage());
    }
}
echo 'Звонки добавлены' . PHP_EOL;
