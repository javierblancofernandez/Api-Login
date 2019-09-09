<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use AppBundle\Entity\User;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use AppBundle\Form\UserType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('frontal/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }



       /**
     * @Route("/registro/", name="registro")
     */
    public function registroAction(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        // Construyendo formulario
        $form = $this->createForm(UserType::class, $user);
        //Recogemos la informacion
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
                // 3) Encode the password (you could also do this via Doctrine listener)
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
    
                $user->setUsername($user->getEmail());


                $user->setRoles(array('ROLE_USER'));


                // 4) save the User!
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();
            return $this->redirectToRoute('login');
        }

       


        // replace this example code with whatever you need
        return $this->render('frontal/registro.html.twig',array( 'form' => $form->createView()));
    
    
    } 

       /**
     * @Route("/login/", name="login")
     */
    public function loginAction(Request $request,AuthenticationUtils $authenticationUtils)
    {
           // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();

    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('frontal/login.html.twig' , array(
            'last_username' => $lastUsername,
            'error'         => $error
        ));
       

    }
    /**
     * @Route("/dashboard/", name="dashboard")
     */
    public function dashboardAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('frontal/dashboard.html.twig'); 
    }

    
}
