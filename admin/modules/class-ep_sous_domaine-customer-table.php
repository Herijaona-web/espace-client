<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       x
 * @since      4.0.0
 *
 * @package    Etapes_Print
 * @subpackage Etapes_Print/admin
 */

 /**
  * The admin-specific functionality of the plugin.
  *
  * Defines the plugin name, version, and two examples hooks for how to
  * enqueue the admin-specific stylesheet and JavaScript.
  *
  * @package    Etapes_Print
  * @subpackage Etapes_Print/admin/modules
  * @author     Njakasoa Rasolohery <ras.njaka@gmail.com>
  */
  class EP_Sous_Domaine_Customer_Table extends WP_List_Table {

    public static $_page;

    public function __construct($pages){
        parent::__construct(array(
            'singular' => 'item',
            'plural'   => 'items',
            'ajax'     => false
        ));
        self::$_page = $pages;
    }
    // Définir les colonnes de la table
    public function get_columns() {
        $columns = array(
            'id'    => 'ID',
            'nom'    => 'Nom',
            'email'  => 'Email',
            'statut'  => 'Statut',
            'action' => 'Action'
        );

        return $columns;
    }

    // Récupérer les données de la table
    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $primary = 'id';
        $this->_column_headers = array($columns, $hidden, $sortable, $primary);

        global $wpdb;
        $table_name = $wpdb->prefix . "clients_epsd";

        // Définir le nombre d'éléments par page
        $per_page = 20;

        // Récupérer le numéro de la page courante
        $current_page = $this->get_pagenum();

        // Calculer le nombre total d'enregistrements dans la table
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

        // Définir le nombre total de pages
        $total_pages = ceil($total_items / $per_page);

        // Limiter les enregistrements à ceux qui sont affichés sur la page courante
        $offset = ($current_page - 1) * $per_page;
        $query = "SELECT * FROM $table_name ORDER BY id DESC LIMIT $offset, $per_page";
        $data = $wpdb->get_results($query, ARRAY_A);

        // Ajouter des informations supplémentaires aux données de la table
        foreach ($data as &$item) {
            $user = get_user_by('ID', $item['fk_id_customer']);
            $domain = $_SERVER['HOST'];
            $item['email'] = $user->user_email;
            $item['action'] = $item['status'];

            $desactive = '<span class="status_invoice" style="background:#f5821cab">Désactivé</span>';
            $active = '<span class="status_invoice" style="background:#4caf50ab">Activé</span>';
            $item['statut'] = $item['action'] == 0 ? $desactive : $active;

            // Ajoutez l'attribut data-status au lien "Activer" ou "Désactiver"
            if ($item['action'] == 0) {
                $item['action'] = '<a href="' . admin_url('admin.php?page='.self::$_page.'&customer_id=' . $item['fk_id_customer']) . '">Personnaliser</a> | <a href="' . admin_url('admin.php?page='.self::$_page.'&status=0&epsd_id=' . $item['id']) . '" class="action_epsd" data-epsd-id="' . $item['id'] . '" data-status="1">Activer</a>';
            } else {
                $item['action'] = '<a href="' . admin_url('admin.php?page='.self::$_page.'&customer_id=' . $item['fk_id_customer']) . '">Personnaliser</a> | <a href="#" class="action_epsd" data-epsd-id="' . $item['id'] . '" data-status="0">Désactiver</a>';
            }
        }
        

        // Envoyer les données à la classe parente
        $this->items = $data;

        // Définir les arguments de pagination
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => $total_pages
        ));
    }
    

    public function column_default($item, $column_name)
    {
        $user = get_user_by('ID', $item['fk_id_customer']);
        switch ($column_name) {
            case 'id':
                return "<a target='_blank' href='$domain/wp-admin/user-edit.php?user_id=$user->ID'><u>#" .$user->ID. "</u></a>";
            case 'nom':
                return "<a target='_blank' href='$domain/wp-admin/user-edit.php?user_id=$user->ID'>" .$user->display_name. "</a>";
            case 'email':
           case 'statut':
            case 'action':
                return $item[$column_name];
            default:
                return '';
        }
    }
    
}

?>