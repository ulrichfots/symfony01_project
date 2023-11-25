<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    public function __construct(private ContactRepository $contactRepository, private RequestStack $requestStack, private EntityManagerInterface $entityManager)
	{
	}

    #[Route('/contact', name: 'contact.index')]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig', [
            'contacts' => $this -> contactRepository -> findAll(),
        ]);
    }

    #[Route('contact/form', name: 'contact.form')]

    public function form(int $id = null): Response
	{
		// création d'un formulaire
		$entity = $id ? $this->contactRepository->find($id) : new Contact();
		$type = ContactType::class;

		$form = $this->createForm($type, $entity);

		// récupérer la saisie précédente dans la requête http
		$form->handleRequest($this->requestStack->getMainRequest());

		// si le formulaire est valide et soumis
		if ($form->isSubmitted() && $form->isValid()) {
			// insérer dans la base
			$this->entityManager->persist($entity);
			$this->entityManager->flush();

			// message de confirmation
			$message ='Contact created';

			// message flash : message stocké en session, supprimé suite à son affichage
			$this->addFlash('notice', $message);

			// redirection vers la page d'accueil de l'admin
			return $this->redirectToRoute('contact.index');
		}
            return $this->render('contact/form.html.twig', [
                'form' => $form->createView(),
            ]);
        
    }
}