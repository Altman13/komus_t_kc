<?php
    // ФИЛЬТР НА ПОСТУПАЮЩИЕ ЗВОНКИ НА ТЕКУЩУЮ ДАТУ
    require_once $_SERVER['DOCUMENT_ROOT'].'/datas/config_pdo.php';

    $emp_name = '%'.$_POST['emp_name'].'%';
    $select_emp_by_name=$db->prepare("SELECT cot_komus_contacts.id, cot_komus_contacts.company_name, cot_komus_contacts.fio, 
    (SELECT cot_komus_references_items.title 
    FROM cot_komus_references_items, cot_komus_contacts
    WHERE cot_komus_references_items.id=cot_komus_contacts.is_type 
    AND cot_komus_contacts.company_name LIKE :emp_name LIMIT 1) as is_type,
    (SELECT cot_komus_references_items.title 
    FROM cot_komus_references_items, cot_komus_contacts
    WHERE cot_komus_references_items.id=cot_komus_contacts.organization 
    AND cot_komus_contacts.company_name LIKE :emp_name LIMIT 1) as organization, 
    (SELECT cot_komus_references_items.title 	
    FROM cot_komus_references_items, cot_komus_contacts
                            WHERE cot_komus_references_items.id=cot_komus_contacts.`status` 
                            AND cot_komus_contacts.company_name LIKE :emp_name LIMIT 1) as status,
                                        cot_komus_contacts.email, cot_komus_contacts.phone, cot_komus_contacts.result, 
                                        cot_komus_contacts.address, cot_komus_contacts.number_lot
                                        FROM cot_komus_contacts  WHERE cot_komus_contacts.company_name LIKE :emp_name
                                        AND CAST(cot_komus_contacts.creation_time AS DATE) = CAST(NOW() AS DATE)                                  
                                        LIMIT 1");
    $select_emp_by_name->bindParam(':emp_name', $emp_name, PDO::PARAM_STR);
    $select_emp_by_name->execute();
    $result=$select_emp_by_name->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
    //AND CAST(cot_komus_contacts.creation_time AS DATE) = CAST(NOW() AS DATE)
?>
