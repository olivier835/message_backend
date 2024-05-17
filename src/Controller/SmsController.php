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
use Symfony\Component\Validator\Constraints\Date;
use Twilio\Rest\Client;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;
use Doctrine\Persistence\ManagerRegistry;


class SmsController extends AbstractController
{

    private $contactRepository;
    private $langueRepository;
    private $contactHasMessageRepository;

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager,
                                LangueRepository $langueRepository,
                                ContactRepository $contactRepository,
                                ContactHasMessageRepository $contactHasMessageRepository,
                                )
    {
        $this->entityManager = $entityManager;
        $this->contactRepository = $contactRepository;
        $this->langueRepository = $langueRepository;
        $this->contactHasMessageRepository = $contactHasMessageRepository;
    }

    #[Route('/sms', name: 'app_sms', methods: ['POST'])]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $authKey = 'b46bc345-4e00-468a-ba66-a2d06082044b:fx';
        $translator = new \DeepL\Translator($authKey);
        $content = json_decode($request->getContent(), true);
        //dd($content);

        // liste des utilisateurs qui parlent une langue dans la table contact
        $contacts = $this->contactRepository->findBy(['langue' => $content['langue_id']]);
        $langue = $this->langueRepository->find($content['langue_id']);


        $result = $translator->translateText($content['contenu'], null, $langue->getCode());

        $message = new Message();
        $message->setStatut($content['statut'] ?? false);
        $message->setContenu($content['contenu']);
        $message->setScheduleDate(isset($content['schedule_date']) ? new \DateTime($content['schedule_date']) : null);
        $message->setDateMessage(new \DateTime());
        $message->setTradContenu($result->text);
        //$message->setSender($content['sender']);

        //$entityManager = $this->entityManager->getManager();
        $this->entityManager->persist($message);
        $this->entityManager->flush();

        $twilio = new Client("AC428c28c185b4cb9a6b3f38e83ff314da", "b9c1ebc7f8fd477672ca2fe77d51c21d");
        for ($i = 0; $i < count($contacts); $i++) {
        $sms = $twilio->messages->create($contacts[$i]->getPhoneNumber(),
            [
                'messagingserviceSid' => "MG1fe0b4539ed464ce7cf6deb63330b7b8",
                'body' => $result->text,
                'sendAt' => isset($content['schedule_date']) ? new \DateTime($content['schedule_date']) : new \DateTime(),
                "scheduleType" => "fixed",
            ]);
        }
        if (in_array($sms->status, ['scheduled', 'accepted'])) {
            return new JsonResponse("Message sent successfully", 200);
        } else {
            return new JsonResponse("Error", 200);
        }
    }
}
