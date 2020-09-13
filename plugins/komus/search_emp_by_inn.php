<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/datas/config_pdo.php';

    $emp_inn = '%'.$_POST['emp_inn'].'%';
    $select_emp_by_inn=$db->prepare("SELECT cot_komus_contacts.company_name, cot_komus_contacts.fio, 
                                    cot_komus_contacts.email, cot_komus_contacts.phone, cot_komus_contacts.inn,
                                    cot_komus_contacts.number_lot, cot_komus_contacts.organization, cot_komus_contacts.code_partner
                                    FROM cot_komus_contacts WHERE cot_komus_contacts.inn LIKE :emp_inn  ORDER BY cot_komus_contacts.id DESC LIMIT 1");
    $select_emp_by_inn->bindParam(':emp_inn', $emp_inn, PDO::PARAM_STR);
    $select_emp_by_inn->execute();
    $result=$select_emp_by_inn->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
?>