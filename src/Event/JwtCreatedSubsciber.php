<?php

namespace App\Event;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JwtCreatedSubscriber{
    public function updateJwtData(JWTCreatedEvent $event)
    {
        // On recupere l'utlisateur
        $user = $event->getUser();

        // On enrichit le data du token
        $data = $event->getData();

        /*$data['id'] = $user->getId();*/
        $data['archive'] =  $user->getArchive();

        // Revoie des donnees du Token
        $event->setData($data);
    }
}