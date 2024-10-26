<?php
namespace App\Services\Comun;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/***
 * Servicio para facilitar el trabajo con el envio de correo electronicos de forma simple,
 * @author Eidy Estupiñan Varona <eidy.estupinan@motorflash.com>
 */

class SimpleMailer
{
    private SimpleLog $log;

    private Configuration $config;

    private FileManager $fileManager;

    private MailerInterface $mailer;


    private string $defaultFrom;
    private bool $saveOnLogs;


    public function __construct(SimpleLog $log, Configuration $config, FileManager $fileManager,MailerInterface $mailer)
    {
        $this->log = $log;
        $this->config = $config;
        $this->fileManager = $fileManager;
        $this->mailer = $mailer;

        $this->defaultFrom = '';    //tomar de servicio de configuración o de archivo .env
        $this->saveOnLogs = true;   //tomar de servicio de configuración o de archivo .env
    }

    private function sendEmail($email): void
    {
        try {
            $this->mailer->send($email);
            if ($this->saveOnLogs) {
                $this->saveOnLog($email);
            }
        } catch (TransportExceptionInterface $e) {
            throw new \Exception('Error al enviar el correo electrónico: ' . $e->getMessage());
        }
    }

    // Enviar correo electrónico en formato texto
    public function sendSimpleEmail(string $destinatario, string $asunto, string $texto): void
    {
        $email = (new Email())
            ->from($this->defaultFrom)
            ->to($destinatario)
            ->subject($asunto)
            ->text($texto);

        $this->sendEmail($email);
    }


    // Enviar correo electrónico usando una plantilla Twig
    public function sendEmailWithTemplate(string $destinatario, string $asunto, string $plantilla, array $variables): void
    {
        $email = (new TemplatedEmail())
            ->from($this->defaultFrom)
            ->to($destinatario)
            ->subject($asunto)
            ->htmlTemplate($plantilla)
            ->context($variables);

        $this->sendEmail($email);
    }



    private function saveOnLog(Email $email): void
    {
        // Generar el registro de correo electrónico
        $registro = "Fecha: " . date('Y-m-d H:i:s') . "\n";
        $registro .= "De: " . $email->getFrom()[0]->getAddress() . "\n";
        $registro .= "Para: " . implode(', ', array_map(fn($recipient) => $recipient->getAddress(), $email->getTo())) . "\n";
        $registro .= "Asunto: " . $email->getSubject() . "\n";
        $registro .= "Cuerpo: " . $email->getTextBody() ?? $email->getHtmlBody() ?? '' . "\n";

        // Guardar el registro en el archivo de log
        file_put_contents('email_log.txt', $registro, FILE_APPEND);
    }
}
