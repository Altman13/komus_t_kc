<?php

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Container;

class MailController
{
    private $transport;
    private $mailer;
    static $message;
    private $ret;

    public function __construct(Container $container)
    {
        //$this->mail = $container['mail'];
        $this->transport = (new Swift_SmtpTransport('smtp.mail.ru', 465))
            ->setUsername('xxx.00@mail.ru')
            ->setPassword('xxx')
            ->setEncryption('SSL');
        $this->mailer = new Swift_Mailer($this->transport);
        $this->ret = array('data' => '', 'error' => '', 'error_text' => '');
    }
    public function send(Request $request, Response $response)
    {
        try {
            $this->mailBuild();
            $result = $this->mailer->send($this::$message);
            if ($result) {
                $this->ret = 'Почта Отправлена';
            } else {
                $this->ret = 'Почта не отправлена';
            }
        } catch (\Throwable $th) {
            $response->getBody()->write('Произошла ошибка при попытке отправить почту' . $th->getMessage() . PHP_EOL);
            $this->ret = $response->withStatus(500);
        }
        return $this->ret;
    }
    public function mailBuild()
    {
        $this->getData()->getTemplate()->getFiles();
    }
    public function getData()
    {
        //if resp != false
        // $this::$message['data'] = $this->mail->show();
        return $this;
    }
    //TODO: реализовать запись логов по отправленным сообщениям
    public function setData()
    {
        return $this;
    }
    public function getTemplate()
    {
        $html = '
                <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 4.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml">
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                            <meta name="viewport" content="width=device-width" />
                            <title>Title</title>
                        </head>
                        <body style = "font: 11pt Arial; line-height: 1.5; font-style: italic;">
                                Добрый день!<br><br>
                                В продолжение нашего разговора по телефону, направляю Вам информацию о компании<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;1. В виде каталогов ИБП и ДГУ. 2 файла в приложении<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;2. Ссылка на наш сайт: <a href="http://neuhaus.ru/"> http://neuhaus.ru/ </a><br>
                                <br>
                                &nbsp;&nbsp;&nbsp;&nbsp;В приложении находятся складские остатки по оборудованию ДГУ и ИБП. 
                                Стоимость можно уточнить у менеджеров отдела продаж.<br> 
                                <br>
                                На текущий момент Группа компаний Нойхаус является инжиниринговой компанией, которая предлагает своим 
                                Заказчикам решения по реализации инженерных проектов в области энергетики любой сложности. 
                                В состав этих решений входят проектные, монтажные и сервисные работы, а также поставка ДГУ, ИБП и ГПУ.
                                <br>
                                <br>
                                Хорошего дня!
                        </body>
                    </html>';
        $this::$message = (new Swift_Message('Проверка'))
            ->setFrom(['xxx@mail.ru' => 'xxx'])
            ->setTo(['xxx']);
        return $this;
    }
    public function getFiles()
    {
        $dir = __DIR__ . '/../files/';
        $files = array_diff(scandir($dir), array('..', '.'));
        foreach ($files as $file) {
            $this::$message->attach(
                Swift_Attachment::fromPath($dir . '/' . $file)->setFilename($file)
            );
        }
        return $this;
    }
}
