<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class SaludoController {

    public function holaAction() {
        return new Response('<h1>Buenos días!</h1>');
    }

}
