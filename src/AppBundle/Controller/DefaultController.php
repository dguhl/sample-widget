<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\Exception\NotImplementedException;

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
     * @Route(
     *     "/widget/{id}.{_format}",
     *     name="widget",
     *     requirements={
     *          "_format": "html|js|json|png|xml"
     *     }
     * )
     */
    public function widgetAction(Request $request, $id)
    {
        $response = new Response();
        $format   = $request->getRequestFormat();

        if ($format == 'png')
            throw new NotImplementedException('The feature does not exist yet.');

        /** @var UserRepository $userRepository */
        $userRepository = $this->getDoctrine()->getManager()->getRepository('AppBundle:User');

        /** @var User $user */
        $user = $userRepository->findOneById($id);

        if ($user) {
            $response->setContent($this->renderView('AppBundle::uuid.'.$format.'.twig', [
                'user'   => $user,
                'rating' => $user->getAverageRating()
            ]));
        }

        return $response;
    }
}
