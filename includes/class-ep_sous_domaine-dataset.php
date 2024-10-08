<?php
/**
 * Fired during plugin activation
 *
 * @link       https://takamoastudio.com/
 * @since      1.0.0
 *
 * @package    Ep-sous-domaine-plugin
 * @subpackage Ep-sous-domaine-plugin/includes
*/

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ep-sous-domaine-plugin
 * @subpackage Ep-sous-domaine-plugin/includes
 * @author     Takamoa Studio <responsable@takamoastudio.com>
*/
class Ep_sous_domaine_Dataset {
    /*
    * All Customers
    */
    public function get_allCustomer() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'clients_epsd';
        $results = $wpdb->get_results("SELECT * FROM $table_name");
        return $results;
    }
    public function insert_new_customer($id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'clients_epsd';
    
        $data = array(
            'fk_id_customer' => $id
        );
    
        // Insérez les données dans la table "clients_epsd"
        $wpdb->insert($table_name, $data);
        var_dump($id);
    }      
    
}