<?php
class Crons extends CI_Controller {
	
	function Crons() {
		parent::__construct();
		
		// Ouverture de la session
		if (!isset($_SESSION)) session_start();
		
		// Chargement des modèles
		$this->load->model('mUser');
		$this->load->model('mUsers');
	}
	
	// Nettoyage du cache des données enregistrées depuis plus de 24h
	function cleanCache() {
		$this->mCache->cleanCache();
		
		$this->load->dbutil();
		
		$users = $this->mUsers->getUsers();
		
		foreach ($users as $user2) {
			if ($user2['last_visit']<(time()-3600*24)) {
				// Suppression des données enregistrées de l'utilisateur
				$this->mUser->deleteCourses($user2['idul']);
				$this->mUser->deleteStudies($user2['idul']);
				$this->mUser->deleteClasses($user2['idul']);
			}
		}
		
		// Optimisation des tables
		$this->dbutil->optimize_table('cache');
		$this->dbutil->optimize_table('studies');
		$this->dbutil->optimize_table('users_courses');
		$this->dbutil->optimize_table('users_classes');
		$this->dbutil->optimize_table('users_courses_sections');
		
		?>OK<?php
	}
	
	
	function infophp() {
		phpinfo();
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */