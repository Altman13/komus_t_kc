<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/datas/config_pdo.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/plugins/komus/inc/class.phpmailer.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/plugins/komus/inc/class.smtp.php';


$select_user_email=$db->prepare("SELECT cot_users.user_email FROM cot_users where cot_users.user_name=:user_name");
$select_user_email->bindParam(':user_name', $_POST['operator'], PDO::PARAM_STR);
$select_user_email->execute();
$email_adress=$select_user_email->fetchColumn();

global $partner_name;
if($_POST['organization']==88){
    $partner_name="ООО Стилус";
}
elseif($_POST['organization']==89){
    $partner_name="ООО Канцпласт";
}
elseif($_POST['organization']==90){
    $partner_name="ООО СмартОфис";
}
elseif($_POST['organization']==91){
    $partner_name="ООО Интегра";
}
elseif($_POST['organization']==107){
    $partner_name="ЦТО";
}
elseif($_POST['organization']==108){
    $partner_name="Редент";
}
elseif($_POST['organization']==109){
    $partner_name="Профимаркет";
}
elseif($_POST['organization']==125){
	$partner_name="МосКанц";
}
elseif($_POST['organization']==126){
	$partner_name="ДивоОфис";
}
$today = date("Y-m-d H:i:s");  

		$message ='
		<html>
			<head>
			</head>
		<body>
			<table width="70%">';		
				$message .= '<tr>Добрый день!</tr>';
				$message .= '<tr><td>Юридическое лицо:</td><td>'.$partner_name.'</td></tr>'."\r\n";
				$message .= '<tr><td>Дата/время звонка:</td><td>'.$today.'</td></tr>'."\r\n";
				$message .= '<tr><td>Телефон: </td><td>'.$_POST['phone'].'</td></tr>'."\r\n";
				$message .= '<tr><td>Почта: </td><td>'.$_POST['email'].'</td></tr>'."\r\n";
				$message .= '<tr><td>Суть обращения:</td><td>'.$_POST['is_type'].'</td></tr>'."\r\n";
				$message .= '<tr><td>ИНН:</td><td>'.$_POST['inn'].'</td></tr>'."\r\n";
				$message .= '<tr><td>Наименование партнера:</td><td>'.$_POST['company_name'].'</td></tr>'."\r\n";
				$message .= '<tr><td>Код партнера:</td><td>'.$_POST['code_partner'].'</td></tr>'."\r\n";
				$message .= '<tr><td>Лот:</td><td>'.$_POST['number_lot'].'</td></tr>'."\r\n";
				$message .= '<tr><td>Ответственное Лицо:</td><td>'.$_POST['fio'].'</td></tr>'."\r\n";
				$message .= '<tr><td>Комментарий:</td><td>'.$_POST['comment'].'</td></tr>'."\r\n";
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
	$mail_adress=$email_adress;
    $mail->addAddress($mail_adress);
    $mail->SMTPDebug  = 2;
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'поступил звонок Заказчика';
    $mail->Body =   $message;
    try {
        $mail->send();
        echo 'Письмо отправлено';
    } catch (Exception $e) {
        die('Ошибка при отправке анкеты по электронной почте!'. $e->getMessage());
    }
