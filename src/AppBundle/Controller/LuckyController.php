<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class LuckyController extends Controller
{
    /**
     * @Route("/lucky/number/{count}", name="luckynumber")
     */
    public function numberAction(Request $request ,$count)
    {

        $i = 1;
        $numbers = array();

        while($i <= $count){
            $numbers[] = mt_rand(0,100);
            $i++;
        }

        $listeNumbers = implode(', ', $numbers);

        // g�n�rer une URL � la route fournie
        $url = $this->generateUrl('luckynumber', array('count' => 38));

        // retourner le chemin de la page
        $path = $request->getPathInfo();
        $param = $request->attributes->get('count');
        // r�cup�re un param�tre $_GET
        $param1 = $request->query->get('param1');
        // r�cup�re un param�tre $_POST
        $param2 = $request->request->get('param2');
        // r�cup�re un objet UploadedFile
        $fichier = $request->files->get('fichier');

        // gerer les sessions
        $session = $request->getSession();
        $session->set('nom', 'yassine');
        $nom = $session->get('nom');

        // ajouter un message flash
        $this->addFlash('notice', 'Message de notification!!');

        // redirection � un route
        // return $this->redirectToRoute('homepage', array(), 301);

        // redirection simple
        //return $this->redirect('http://symfony.com');

        // redirection � un controller
        // return $this->forward('AppBundle:Default:index');

            // acc�s au services
        $route = $this->get('router');
        $doctrine = $this->get('doctrine');


        return $this->render('lucky/number.html.twig', array('numbers' => $listeNumbers, 'myurl' => $url, 'mypath' => $path));

        //return new Response('<html><body>Lucky numbers : '.$listeNumbers.'</body></html>');
        //return new JsonResponse($data);

    }
}
