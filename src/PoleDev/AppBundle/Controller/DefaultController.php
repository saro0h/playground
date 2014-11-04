<?php

namespace PoleDev\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/github", name="github")
     */
    public function githubAction()
    {
        return $this->redirect('https://github.com/login/oauth/authorize?client_id='.$this->container->getParameter('client_id'));
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction()
    {
        return $this->redirect($this->generateUrl('admin_github'));
    }


    /**
     * @Route("/admin/github", name="admin_github")
     * @Template
     */
    public function adminGithubAction()
    {
        return array();
    }
}
