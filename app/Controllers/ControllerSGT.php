<?php

namespace App\Controllers;

use App\Models\TacheModel;
use Config\Pager;

class ControllerSGT extends BaseController
{
    public function index(): string
    {
        // Charger le modèle
        $tacheModel = new TacheModel();

        // Charger les services Request et Response
        $request = service('request');
        $response = service('response');

        // Récupérer les filtres de l'URL ou des cookies
        $filters = [
            'task-name' => $request->getVar('task-name') ?? $request->getCookie('task-name') ?? '',
            'due-date' => $request->getVar('due-date') ?? $request->getCookie('due-date') ?? '',
            'priority' => $request->getVar('priority') ?? $request->getCookie('priority') ?? '0',
            'filter-dropdown' => $request->getVar('filter-dropdown') ?? $request->getCookie('filter-dropdown') ?? 'date_echeance',
            'sortOrder' => $request->getVar('sortOrder') ?? $request->getCookie('sortOrder') ?? 'ASC',
            'show-completed-tasks' => $request->getVar('show-completed-tasks') ?? $request->getCookie('show-completed-tasks') ?? 0,
            'page' => $request->getVar('page') ?? $request->getCookie('page') ?? 1,
        ];

        // Sauvegarder les filtres dans des cookies (expiration 7 jours)
        foreach ($filters as $key => $value) {
            $response->setCookie($key, $value . "", 7 * 24 * 60 * 60); // Cookie de 7 jours
        }

        // Session
        
        $session = session();

        $configPager = config(Pager::class);
        $perPage = $configPager->perPage;

        // Appliquer les filtres si l'utilisateur est connecté
        $idUtilisateur = null;
        if ($session->get('isLoggedIn')) {
            $idUtilisateur = $session->get('idutilisateur');
            $tacheModel->where('idutilisateur', $idUtilisateur);
        }

        // Appliquer les filtres aux tâches
        if (!empty($filters['task-name'])) {
            $tacheModel->like('titre', $filters['task-name']);
        }

        if (!empty($filters['due-date'])) {
            $tacheModel->where('date_echeance', $filters['due-date']);
        }

        if (!empty($filters['priority']) && $filters['priority'] !== '0') {
            $tacheModel->where('priorite', $filters['priority']);
        }

        if ($filters['show-completed-tasks'] != 1) {
            $tacheModel->where('status', FALSE); // Ne pas afficher les tâches terminées
        }

        if ($filters['filter-dropdown']) {
            $tacheModel->orderBy($filters['filter-dropdown'], $filters['sortOrder']);
        }

        // Récupérer les tâches filtrées avec pagination
        $taches = $tacheModel->paginate($perPage);

        $tachesUtil = $tacheModel->where('idutilisateur', $idUtilisateur)->findAll();


        // Pagination
        $pager = $tacheModel->pager;

        // Retourner la vue avec les tâches et les filtres restaurés
        return view('pageAccueil', [
            'taches' => $taches,
            'tachesUtil' => $tachesUtil,
            'pager' => $pager,
            'recherche' => $filters['task-name'],
            'date' => $filters['due-date'],
            'priorite' => $filters['priority'],
            'trierpar' => $filters['filter-dropdown'],
            'sens' => $filters['sortOrder'],
            'showCompletedTasks' => $filters['show-completed-tasks'],
        ]);
    }

    public function getComments($idTache)
    {
        // Chargement du modèle des commentaires
        $commentairesModel = new \App\Models\CommentaireModel();

        // Récupération des commentaires associés à la tâche
        $commentaires = $commentairesModel->where('idtache', $idTache)->findAll();

        // Si des commentaires sont trouvés
        if ($commentaires) {
            return $this->response->setJSON([
                'status' => 'success',
                'comments' => $commentaires
            ]);
        } else {
            // Aucun commentaire trouvé
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Aucun commentaire disponible pour cette tâche.'
            ]);
        }
    }

    public function resetFilters()
    {
        $response = service('response');
    
        // Supprimer tous les cookies des filtres
        $filterKeys = ['task-name', 'due-date', 'priority', 'filter-dropdown', 'sortOrder', 'show-completed-tasks', 'page'];
        foreach ($filterKeys as $key) {
            $response->deleteCookie($key);
        }
    
        // Rediriger vers la page principale
        return redirect()->to('/ControllerSGT');
    }
    
}