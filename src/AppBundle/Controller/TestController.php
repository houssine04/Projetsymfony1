<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class TestController extends Controller
{
    /**
     * @Route("test/show/{id}")
     */
    public function showAction($id)
    {
        return $this->render('@AppBundle/Test/show.html.twig', array(
            // ...
        ));
    }

}
