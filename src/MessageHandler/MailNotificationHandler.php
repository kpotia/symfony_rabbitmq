<?php 
	
namespace App\MessageHandler;

use App\Message\MailNotification;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessengerHandlerInterface;
use Symfony\Component\Mime\Email;


/**
 * MailNotificationHandler
 */
class MailNotificationHandler extends MessengerHandlerInterface
{

	private $mailer;
	
	function __construct(MailerInterface $mailer)
	{
		$this->mailer = $mailer;
	}

	public function __invoke(MailNotification $message)
	{
		$email = (new Email())
        	->from($message->getUser()->getEmail())
        	->to('admin@incidents.com')
        	->subject('New Incident #'. $message->getId(). ' - '. $message->getUser()->getEmail())
        	->html('<p>'. $message->getDescription( ) . '</p>');

        	sleep(10);

        	$this->mailer->send($email);

	}
}

 ?>