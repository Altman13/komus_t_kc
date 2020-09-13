<?php
include 'class.phpmailer.php';
include 'class.smtp.php';

date_default_timezone_set('Etc/UTC');

function SendMail($contact_id) {
	global $db, $db_x, $cfg;
        $sql_email_string = "
                SELECT contacts.*, DATE_FORMAT(contacts.creation_time, '%d.%m.%Y %H:%i') creation_time_,
				u.user_lastname, u.user_firstname, DATE_FORMAT(contacts.date_tyre, '%d.%m.%Y %H:%i') date_tyre_				
                FROM {$db_x}komus_contacts contacts
                LEFT JOIN {$db_x}users u ON u.user_id = contacts.user_id              
                WHERE  contacts.id = {$contact_id}
                AND contacts.is_finish = 1";
        $sql_email = $db->query($sql_email_string);
        $message = '';
        foreach ($sql_email as $key => $row) { 
		//if ((($row['is_transfercall'] == 68 ) OR ($row['status'] == 87 )) 
		//AND ($row['nowanswer'] == 1)) {  // если не удалось перевести звонок\
			if($row['organization']==88){
				$partner_name="ООО Стилус";
			}
			elseif($row['organization']==89){
				$partner_name="ООО Канцпласт";
			}
			elseif($row['organization']==90){
				$partner_name="ООО СмартОфис";
			}
			elseif($row['organization']==91){
				$partner_name="ООО Интегра";
			}
			elseif($row['organization']==107){
				$partner_name="ЦТО";
			}
			elseif($row['organization']==108){
				$partner_name="Редент";
			}
			elseif($row['organization']==109){
				$partner_name="Профимаркет";
			}
			elseif($row['organization']==125){
				$partner_name="МосКанц";
			}
			elseif($row['organization']==126){
				$partner_name="ДивоОфис";
			}
		$message ='
		<html>
			<head>
			</head>
		<body>
			<table width="70%">';		
				$message .= '<tr>Добрый день!</tr>';
				$message .= '<tr><td>Юридическое лицо:</td><td>'.$partner_name.'</td></tr>'."\r\n";
				$message .= '<tr><td>Дата/время звонка:</td><td>'.$row['creation_time_'].'</td></tr>'."\r\n";
				$message .= '<tr><td>Телефон: </td><td>'.$row['phone'].'</td></tr>'."\r\n";
				$message .= '<tr><td>Почта: </td><td>'.$row['email'].'</td></tr>'."\r\n";
				$message .= '<tr><td>Суть обращения:</td><td>'.getReferenceItem($row['is_type']).'</td></tr>'."\r\n";
				$message .= '<tr><td>ИНН:</td><td>'.$row['inn'].'</td></tr>'."\r\n";
				$message .= '<tr><td>Наименование партнера:</td><td>'.$row['company_name'].'</td></tr>'."\r\n";
				$message .= '<tr><td>Код партнера:</td><td>'.$row['code_partner'].'</td></tr>'."\r\n";
				$message .= '<tr><td>Лот:</td><td>'.$row['number_lot'].'</td></tr>'."\r\n";
				$message .= '<tr><td>Ответственное Лицо:</td><td>'.$row['fio'].'</td></tr>'."\r\n";
				$message .= '<tr><td>Комментарий:</td><td>'.$row['comment'].'</td></tr>'."\r\n";
				$message .= '<tr><td>Диспетчер ФИО:</td><td>'.$row['user_firstname'].' '.$row['user_lastname'].'</td></tr>'."\r\n";
				$message .= '
			</table>
		</body>
		</html>';
	$mail = new PHPMailer(true);
	$mail->Host 	  = "ssl://smtp.yandex.ru";
	$mail->Port       = 465;
	$mail->SMTPAuth   = true;
	$mail->Username   = "offer@versta24.ru";   
	$mail->Password   = "Hyt-Yd2-Fv9-dBu";
	$mail->SMTPSecure = "tls";

	if($row['is_type']==70)
	$mail_adress='tender_budget@tvr.komus.net';
	// elseif($row['is_type']==73)
	// $mail_adress='obd-doc@komus.net';
	/*elseif($row['is_type']==76)
	$mail_adress='edo.nkp@komus.net';*/
	elseif($row['is_type']==72)
	$mail_adress='budget_help@komus.net';
	elseif($row['is_type']==77)
	$mail_adress='budget_help@komus.net';
	elseif($row['is_type']==78)
	$mail_adress='budget_help@komus.net';
	elseif($row['is_type']==80)
	$mail_adress='edo.nkp@komus.net';
	/*elseif($row['is_type']==81)
	$mail_adress='budget_help@komus.net';*/
	elseif($row['is_type']==82)
	$mail_adress='gcs_portal@komus.net';
	elseif($row['is_type']==93)
	$mail_adress='tender_budget@tvr.komus.net';
	elseif($row['is_type']==102)
	$mail_adress='tender_budget@tvr.komus.net';
	elseif($row['is_type']==106)
	$mail_adress='budget_help@komus.net';
	elseif($row['is_type']==113)
	$mail_adress='budget_help@komus.net';
	elseif($row['is_type']==117)
	$mail_adress='budget_help@komus.net';
	elseif($row['is_type']==120)
	$mail_adress='tender_budget@tvr.komus.net';
	elseif($row['is_type']==124)
	$mail_adress='tender_reg@tvr.komus.net';
	elseif($row['is_type']==129)
	$mail_adress='tender_budget@tvr.komus.net';
	elseif($row['is_type']==130)
	$mail_adress='tender_budget@tvr.komus.net';
				// 	$mail->addAddress('obd60@komus.net');
				if($mail_adress)
				{
			$mail->addAddress($mail_adress);
			$mail->SMTPDebug  = 2;
			$mail->IsHTML(true);
			$mail->CharSet = 'UTF-8';
			$mail->Subject = 'поступил звонок Заказчика';
			$mail->Body =   $message;
					if ($mail->send()){
							echo '<div style=\"padding: 10px\">Письмо отправлено</div>';
						} else {
							echo '<div style=\"padding: 10px\"><strong>Ошибка при отправке анкеты по электронной почте!</strong></div>';
						}
		}
	}
	}	      	
//	}   
?>