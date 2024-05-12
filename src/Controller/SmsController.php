<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twilio\Rest\Client;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class SmsController extends AbstractController
{

    private $contactRepository;
    private $langueRepository;
    private $messageRepository;
    #[Route('/sms', name: 'app_sms')]
    public function index(): Response
    {
        $authKey = "b46bc345-4e00-468a-ba66-a2d06082044b:fx";
        $translator = new \DeepL\Translator($authKey);

        $result = $translator->translateText('hello,nul', 'fr', 'en');

        $twilio = new Client(env('TWILIO_ACCOUNT_SID'),env('TWILIO_AUTH_TOKEN'));
        $message = $twilio->messages->create('to',
        [
            'messagingserviceSid' => env('TWILIO_MESSAGING_APP'),
            'body' => $result->text,
            'sendAt' => '',
        ]);
        return $result->text;
    }
}
