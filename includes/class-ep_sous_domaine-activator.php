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
class Ep_sous_domaine_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */    
    // public static function activate() {
    //     var_dump('activé');
    // }

    public static function activate() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
    
        // Création de la table "clients_epsd", les utilisateurs dans cette liste auront accès à la fonctionnalité Sous-domaine en front
        $table_name_clients_epsd = $wpdb->prefix . 'clients_epsd';
        $sql_clients_epsd = "CREATE TABLE IF NOT EXISTS $table_name_clients_epsd (
            `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `fk_id_customer` bigint(20) unsigned,
            `status` int NOT NULL DEFAULT 1,
            FOREIGN KEY (`fk_id_customer`) REFERENCES {$wpdb->prefix}users(`ID`)
        ) $charset_collate;";
    
        // Création de la table "product_epsd"
        $table_name_product_epsd = $wpdb->prefix . 'product_epsd';
        $sql_product_epsd = "CREATE TABLE IF NOT EXISTS $table_name_product_epsd (
            `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `nom_product` varchar(255) NOT NULL,
            `image` varchar(255) NOT NULL,
            `description` text NOT NULL,
            `fk_id_product` bigint(20) unsigned,
            `fk_id_user` bigint(20) unsigned,
            FOREIGN KEY (`fk_id_product`) REFERENCES {$wpdb->prefix}posts(`ID`),
            FOREIGN KEY (`fk_id_user`) REFERENCES {$wpdb->prefix}users(`ID`)
        ) $charset_collate;";
        
    
        // Mettre à jour la table si nécessaire
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_clients_epsd);
        dbDelta($sql_product_epsd);
    }

  
}    
