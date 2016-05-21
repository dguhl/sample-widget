<?php

namespace AppBundle\Controller;

use AppBundle\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..')
        ]);
    }

    /**
     * @Route("/widget/{uuid}.js", name="widget")
     */
    public function widgetAction(Request $request, $uuid)
    {
        $response = new Response();

        /** @var UserRepository $userRepository */
        $userRepository = $this->getDoctrine()->getManager()->getRepository('AppBundle:User');
        $user = $userRepository->findByUuid($uuid);

        if ($user)
            $response->setContent($this->renderView('default/uuid.js.twig', [
                'rating' => $user->getRating()
            ]));

        return $response;
    }
}
