<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Message;
use App\Repository\ContactHasMessageRepository;
use App\Repository\ContactRepository;
use App\Repository\LangueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twilio\Rest\Client;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class SmsController extends AbstractController
{

    private $contactRepository;
    private $langueRepository;
    private $contactHasMessageRepository;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, LangueRepository $langueRepository, ContactRepository $contactRepository, ContactHasMessageRepository $contactHasMessageRepository)
    {
        $this->entityManager = $entityManager;
        $this->contactRepository = $contactRepository;
        $this->langueRepository = $langueRepository;
        $this->contactHasMessageRepository = $contactHasMessageRepository;
    }

    #[Route('/sms', name: 'app_sms', methods: ['POST'])]
    public function index(Request $request): Response
    {
        $authKey = getenv('DEEPL_API_KEY');
        $translator = new \DeepL\Translator($authKey);
        $content = json_decode($request->getContent(), true);

        // liste des utilisateurs qui parlent une langue dans la table contact
        $contacts = $this->contactRepository->findBy(['langue' => $content['langue_id']]);


        $result = $translator->translateText($content['contenu'], null, 'en');

        $message = new Message();
        $message->setStatut($content['statut']);
        $message->setContenu($content['contenu']);
        $message->setDateMessage(new \DateTime($content['date_message']));
        $message->setTradContenu($result->text);
        $message->setSender($content['sender_id']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($message);
        $entityManager->flush();

        $twilio = new Client(env('TWILIO_ACCOUNT_ID'), env('TWILIO_AUTH_TOKEN'));
        for ($i = 0; $i < count($contacts); $i++) {
        $sms = $twilio->messages->create($contacts[$i]->getPhoneNumber(),
            [
                'messagingserviceSid' => env('TWILIO_MESSAGING_APP'),
                'body' => $result->text,
                'sendAt' => new \DateTime($content['date_message']),
            ]);
        }
        return new JsonResponse("Message sent successfully", 200);
    }
}
