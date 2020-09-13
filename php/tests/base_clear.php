<?php
require "config/config.php";

$clear_base = $db->prepare("SET FOREIGN_KEY_CHECKS = 0;
                            TRUNCATE table komus_new.timezone;
                            TRUNCATE table komus_new.users;
                            TRUNCATE table komus_new.groups_users;
                            TRUNCATE table komus_new.regions;
                            TRUNCATE table komus_new.contacts;
                            TRUNCATE table komus_new.maillog;
                            TRUNCATE table komus_new.calls;
                            SET FOREIGN_KEY_CHECKS = 1;");
try {
    $clear_base->execute();
} catch (\Throwable $th) {
    die('Произошла ошибка при очистке базы ' . $th->getMessage());
}
echo 'База успешно очищена.';
