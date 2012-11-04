<?php

class mModules extends CI_Model {
    function mModules () {
        parent::__construct();
    }

    // Recherche des modules du tableau de bord
    function get ( $params = array() ) {
        if ( !isset( $params['active'] ) ) $params['active'] = true;

        // Tri des modules
        if ( isset( $params[ 'order' ] ) ) {
            $this->db->order_by( $params[ 'order' ] );
            unset( $params[ 'order' ] );
        } else {
            $this->db->order_by( 'order asc' );
        }

        // Sélection des modules selon les critères demandés
        $this->db->where( $params );

        // Récupération des modules sélectionnés
        $result = $this->db->get( 'modules' );
        $modules = $result->result_array();

        foreach ( $modules as &$module ) {
            $module[ 'data' ] = unserialize( $module[ 'data' ] );
        }

        if ( isset( $params[ 'id' ] ) ) {
            // Renvoi du module demandé
            return ( $modules[ 0 ] );
        } else {
            // Renvoi des modules trouvés
            return ( $modules );
        }
    }

    // Recherche des modules d'un utilisateur
    function getUserModules ( $user_id = '' ) {
        // Si aucun utilisateur n'est défini, l'utilisateur actuel sera utilisé
        if ( empty( $user_id ) ) $user_id = $this->session->userdata('pilule_user');

        $this->db->select( 'modules.*' );

        // Sélection des modules selon les critères demandés
        $this->db->where( 'users_modules_map.idul', $user_id );

        // Annexion de la table d'information des modules
        $this->db->join( 'modules', 'modules.id = users_modules_map.module_id', 'left' );

        // Tri des modules
        $this->db->order_by( 'modules.order asc' );

        // Récupération des modules sélectionnés
        $result = $this->db->get( 'users_modules_map' );
        $modules = $result->result_array();

        foreach ( $modules as &$module ) {
            $module[ 'data' ] = unserialize( $module[ 'data' ] );
        }

        // Renvoi des modules trouvés
        return ( $modules );
    }

    function addUserModule ( $module_id ) {
        $user_id = $this->session->userdata('pilule_user');

        // Vérification que le module n'est pas déjà activé pour l'utilisateur
        $this->db->where( array( 'idul' => $user_id, 'module_id' => $module_id ) );
        $this->db->from( 'users_modules_map' );

        if ( $this->db->count_all_results() == 0 ) {
            // Ajout du module dans la DB
            if ( $this->db->insert( 'users_modules_map', array( 'idul' => $user_id, 'module_id' => $module_id ) ) ) {
                return true;
            } else {
                return false;
            }
        } else {
            // Le module est déjà actif
            return true;
        }
    }

    function removeUserModule ( $module_id ) {
        $user_id = $this->session->userdata('pilule_user');

        // Suppression du module dans la DB
        if ( $this->db->delete( 'users_modules_map', array( 'idul' => $user_id, 'module_id' => $module_id ) ) ) {
            return true;
        } else {
            return false;
        }
    }
}