<?php

namespace App\Controller\admin;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Gère les routes de la page d'administration des playlists
 *
 */
class AdminPlaylistsController extends AbstractController {
    
    /**
     * 
     * @var FormationRepository
     */
    private $playlistRepository;
    
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
     * @param PlaylistRepository $playlistRepository
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepository
     */
    function __construct(PlaylistRepository $playlistRepository, FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->playlistRepository = $playlistRepository;
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }
    
    
    /**
     * Fonction pour initier une route vers la page admin
     * @Route("/admin/playlists", name="admin.playlists")
     * @return Response
     */
    public function index(): Response{
        $playlists= $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admin.playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }
    
    /**
     * Fonction pour supprimer une playlist
     * @Route("/admin/del.playlist/{id}", name="admin.playlists.del")
     * @param Playlist $playlists
     * @return Response
     */
    public function del(Playlist $playlists): Response{
        $this->playlistRepository->remove($playlists, true);
        return $this->redirectToRoute('admin.playlists');
    }
    
    /**
     * Fonction pour editer une playlist
     * @Route("/admin/edit.playlists/{id}", name="admin.playlists.edit")
     * @param Playlist $playlists
     * @param Request $request
     * @return Response
     */
    public function edit(Playlist $playlists, Request $request): Response{
        $formPlaylist = $this->createForm(PlaylistType::class, $playlists);
        
        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
            $this->playlistRepository->add($playlists, true);
            return $this->redirectToRoute('admin.playlists');
        }
        
        return $this->render("admin/admin.playlist.edit.html.twig", [
            'playlists' => $playlists,
            'formplaylist' => $formPlaylist->createView()
        ]);
    }
    
    /**
     * Fonction pour ajouter une playlist
     * @Route("/admin/add.playlists", name="admin.playlists.add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response{
        $playlists = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlists);
        
        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
            $this->playlistRepository->add($playlists, true);
            return $this->redirectToRoute('admin.playlists');
        }
        
        return $this->render("admin/admin.playlist.add.html.twig", [
            'playlists' => $playlists,
            'formplaylist' => $formPlaylist->createView()                
        ]);
    }
    
    /**
     * Fonction de tri des playlists
     * @Route("/admin/playlists/tri/{champ}/{ordre}", name="admin.playlists.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response{
        switch($champ){
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "nbformations":
                $playlists = $this->playlistRepository->findAllByQtyFormations($ordre);
                break;
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admin.playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }
    
    /**
     * Ronction qui trouve les playlists en fonction d'un champ
     * @Route("/admin/playlists/recherche/{champ}/{table}", name="admin.playlists.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        if($table ==""){
            $playlists = $this->playlistRepository->findByTableContainValue($champ, $valeur, $table);
        }else{
            $playlists = $this->playlistRepository->findByContainValue($champ, $valeur);
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admin.playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories,            
            'valeur' => $valeur,
            'table' => $table
        ]);
    }
    
    
}