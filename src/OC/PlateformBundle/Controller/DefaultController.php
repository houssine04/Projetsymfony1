<?php

namespace OC\PlateformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@OCPlateform/Default/index.html.twig');
    }
}
