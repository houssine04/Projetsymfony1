<?php

namespace OC\PlateformBundle\Controller;

use OC\PlateformBundle\Entity\Advert;
use OC\PlateformBundle\Entity\AdvertSkill;
use OC\PlateformBundle\Entity\Application;
use OC\PlateformBundle\Entity\Image;
use OC\PlateformBundle\Form\AdvertType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AdvertController extends Controller
{
    public function indexAction($page)
    {
        if ($page < 1) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        // utilisation de service
        /*$antispam = $this->get('oc_platform.antispam');
        $text = "some text";
        if($antispam->isSpam($text)){
            throw new \Exception('Votre message a �t� d�tect� comme spam !');
        }*/

        // Creer une Entity Manager em
        $em = $this->getDoctrine()->getManager();
        // $listAdverts = $em->getRepository(Advert::class)->findAll();

        $nbPerPage = 5;

        // M�thode personnelle
        $listAdverts = $em->getRepository(Advert::class)->getAdverts($page, $nbPerPage);

        // Nombre total de pages
        $nbPages = ceil(count($listAdverts) / $nbPerPage);

        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        $data = array('listAdverts' => $listAdverts, 'nbPages' => $nbPages, 'page' => $page);

        return $this->render('@OCPlateform/Advert/index.html.twig', $data);
    }

    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository(Advert::class)->find($id);

        // M�thode personnelle
        //$advert = $em->getRepository(Advert::class)->myFindOne($id);

        if (!$advert) {
            throw $this->createNotFoundException('No advert found for id ' . $id);
        }

        // liste des candidatures
        $listApplications = $em->getRepository(Application::class)->findBy(array('advert' => $advert));

        // liste des competences
        $listAdvertSkills = $em->getRepository(AdvertSkill::class)->findBy(array('advert' => $advert));

        $data = array('advert' => $advert,
            'listApplications' => $listApplications,
            'listAdvertSkills' => $listAdvertSkills);

        return $this->render('@OCPlateform/Advert/view.html.twig', $data);
    }

    public function menuAction($limit)
    {
        $em = $this->getDoctrine()->getManager();

        $listAdverts = $em->getRepository(Advert::class)->findBy(array(), array('date' => 'desc'), $limit, 0);

        return $this->render('@OCPlateform/Advert/menu.html.twig', array('listAdverts' => $listAdverts));
    }

    public function addAction(Request $request)
    {

        $advert = new Advert();

        // on cree une form et on ajoute les champs de l'entit�
        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);

        // On v�rifie que les valeurs entr�es sont correctes
        if ($form->isSubmitted() && $form->isValid()) {

            $image = new Image();
            $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
            $image->setAlt('Image test');
            $advert->setImage($image);

            $em = $this->getDoctrine()->getManager();

            $em->persist($advert);
            $em->flush();

            $this->addFlash('notice', 'Annonce bien enregistr�e.');
            return $this->redirectToRoute('oc_platform_home');

        }

        return $this->render('@OCPlateform/Advert/add.html.twig', array('form' => $form->createView()));

    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository(Advert::class)->find($id);

        if (!$advert) {
            throw $this->createNotFoundException('Annonce no trouv�e avec id : ' . $id);
        }

        // on cree une form et on ajoute les champs de l'entit�
        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);

        // On v�rifie que les valeurs entr�es sont correctes
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($advert);
            $em->flush();

            $this->addFlash('notice', 'Annonce bien modifi�e.');
            return $this->redirectToRoute('oc_platform_home');

        }

        return $this->render('@OCPlateform/Advert/edit.html.twig', array('advert' => $advert, 'form' => $form->createView()));
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository(Advert::class)->find($id);

        if (!$advert) {
            throw $this->createNotFoundException('No advert found for id ' . $id);
        }

        $em->remove($advert);
        $em->flush();

        $this->addFlash('notice', 'Annonce bien supprim�e.');
        return $this->redirectToRoute('oc_platform_home');
    }

    public function viewSlugAction($year, $slug)
    {
        return new Response("viewSlug");
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

}
