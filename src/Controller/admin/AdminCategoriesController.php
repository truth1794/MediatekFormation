<?php

namespace App\Controller\admin;

use App\Form\CategorieType;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Gère les routes de la page d'administration des catégories
 *
 * @author truth1794
 */
class AdminCategoriesController extends AbstractController {
    
    /**
     * 
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    /**
     * Constructeur
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepository
     */
    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
    }
    
    /**
     * Fonction pour initier une route vers la page admin
     * @Route("/admin/categories", name="admin.categories")
     * @return Response
     */
    public function index(): Response{
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render("/admin/admin.categories.html.twig", [
            'formations' => $formations,
            'categories' => $categories,
        ]);
    }
    
    
    
    /**
     * Fonction pour supprimer une categorie
     * @Route("/admin/categories/del/{id}", name="admin.categories.del")
     * @param Categorie $categorie
     * @return Response
     */
    public function del(Categorie $categorie): Response{
        $this->categorieRepository->remove($categorie, true);
        return $this->redirectToRoute('admin.categories');
    }
    
    
    /**
     * Fonction pour ajouter d'une categorie
     * @Route("/admin/ajout.categories", name="admin.ajout.categories")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response{
        $categories = new Categorie();
        $formCategorie = $this->createForm(CategorieType::class, $categories);
       
        $formCategorie->handleRequest($request);
        if($formCategorie->isSubmitted() && $formCategorie->isValid()){
            $this->categorieRepository->add($categories, true);
            return $this->redirectToRoute('admin.categories');
        }
        
        return $this->render("admin/admin.categorie.add.html.twig", [
            'categories' => $categories,
            'formcategorie' => $formCategorie->createView()                
        ]);
    }
    
    
    /**
     * Fonction de tri des categories
     * @Route("/admin/categories/tri/{champ}/{ordre}", name="admin.categories.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response{
        $categories = $this->categorieRepository->findAllOrderBy($champ, $ordre);
        $formations = $this->formationRepository->findAll();
        return $this->render('/admin/admin.categories.html.twig', [
            'formations' => $formations,
            'categories' => $categories,
        ]);
    }
}