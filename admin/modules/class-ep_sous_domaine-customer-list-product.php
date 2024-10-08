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
class Product_List_Table  extends WP_List_Table {
 
    // Définir les colonnes de la table
    public static $_page ,$_customer_id;

    public function __construct($pages,$customer){
        parent::__construct(array(
            'singular' => 'item',
            'plural'   => 'items',
            'ajax'     => false
        ));
        self::$_page = $pages;
        self::$_customer_id=$customer;
    }    
    public function get_columns() {
        $columns = array(
            'id_costum_ep'=>'ID',
            'product_name' => 'Nom',
            'category' => 'Catégories',
            'apercue' => '<span class="wc-image tips custom">Image</span>',
            'actions' => 'Actions'
        );
 
        return $columns;
    }
 
    // Récupérer les données de la table
    public function prepare_items() {
        
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $primary  = 'id';
        $this->_column_headers = array($columns, $hidden, $sortable, $primary);


        global $wpdb;
        $table_name = $wpdb->prefix."product_epsd";
        // Définir le nombre d'éléments par page
        $per_page = 20;
    
        // Récupérer le numéro de la page courante
        $current_page = $this->get_pagenum();
    
        // Calculer le nombre total d'enregistrements dans la table
        $total_items = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
    
        // Définir le nombre total de pages
        $total_pages = ceil( $total_items / $per_page );
    
        // Limiter les enregistrements à ceux qui sont affichés sur la page courante
        $offset = ( $current_page - 1 ) * $per_page;
        $user_id = self::$_customer_id;
        $query = "SELECT * FROM $table_name WHERE fk_id_user = $user_id ORDER BY id DESC LIMIT $offset, $per_page";
        $data = $wpdb->get_results( $query, ARRAY_A );

        // Ajout des actions "Modifier" et "Supprimer" aux données de la table
        foreach ( $data as &$item ) {
            $edit_link = add_query_arg( array(
                'page'   => self::$_page,
                'action' => 'configurer',
                'customer_id' =>self::$_customer_id,
                'idproduct'     => $item['fk_id_product']
            ), admin_url( 'admin.php' ) );
            $delete_link = add_query_arg( array(
                'page'   => self::$_page,
                'action' => 'delete',
                'customer_id' =>self::$_customer_id,
                'idproduct'     => $item['id']
            ), admin_url( 'admin.php' ) );
            // $item['actions'] = '<a href="' . $edit_link . '">Configurer</a> | <a href="' . $delete_link . '">Supprimer</a>';
            $item['actions'] = '<a href="' . $edit_link . '">Configurer</a> | <a href="' . $delete_link . '" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce produit ?\')">Supprimer</a>';
        }
    
        // Envoyer les données à la classe parente
        $this->items = $data;
    
        // Définir les pagination_args pour la pagination
        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => $total_pages
        ) );
    }

    public function column_default( $item, $column_name ) {
        $product = wc_get_product($item['fk_id_product']);
        $product_slug = $product->get_slug();
        $domain = $_SERVER['HOST'];
        $product_categories = wp_get_post_terms($product->get_id(), 'product_cat');
        if (!empty($product_categories)) {
            $category = $product_categories[0];
            $category_name = $category->name;
        }
        $edit_link = add_query_arg( array(
            'page'   => self::$_page,
            'action' => 'configurer',
            'customer_id' =>self::$_customer_id,
            'idproduct'     => $item['fk_id_product']
        ), admin_url( 'admin.php' ) );
        switch ( $column_name ) {
            case 'id_costum_ep':
                return $item['id'];
            case 'product_name':
                $items = '<div>';
                $items .= '<span><a class="es_product_link" href="' . $edit_link . '">' .$item['nom_product'] .'</a></span>';
                $items .= '<div><span style="opacity: 0.6;">ID : ' .$item['fk_id_product'] .'</span>&nbsp;|&nbsp;';
                $items .= '<a target="_blank" href="'. $domain .'/espace-client/produit/'.$product_slug.'">Voir</a>';
                $items .= '</div>';
                $items .= '</div>';
                return $items;
            case 'category':
                $items = '<div>';
                $items .= '<span>'.$category_name.'</span>';
                $items .= '</div>';
                return $items;
            case 'apercue':
                // Code pour afficher l'aperçu
                $productId = $item['fk_id_product'];
                $product = wc_get_product($productId);
                if ($product) {
                    $image_url_full = get_post_meta($productId, 'epsd_image_preview', true);
                    $image_id = $item['image'];
                    $image_url = wp_get_attachment_image_src(
                        $image_id,
                        array('auto', 50), // Dimensions souhaitées (largeur, hauteur)
                    );
                    $src = $image_url_full ? $image_url_full : $image_url[0]; 
                    $image = '<img width="50" height="50" class="product-image" src="'.$src.'" class="product-image" alt="" decoding="async" sizes="(max-width: 50px) 100vw, 50px"';
                    return '<div class="product-cell"><a target="_blank" style="display: inline-flex;" class="es_product_link" href="'. $src .'">' . $image . '</a></div>';
                } else {
                    return '<div class="product-cell"> N/A </div>';
                }                
                
            case 'actions':
                    return $item[$column_name];
            default:
                return '';
        }
    }
    
}

?>