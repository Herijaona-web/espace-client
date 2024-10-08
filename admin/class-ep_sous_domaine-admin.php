<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://takamoastudio.com/
 * @since      1.0.0
 *
 * @package    ep-sous-domaine-plugin
 * @subpackage ep-sous-domaine-plugin/admin
*/

global $wpdb;
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @subpackage ep-sous-domaine-plugin
 * @subpackage ep-sous-domaine-plugin/admin
 * @author    Takamoa Studio <responsable@takamoastudio.com>
*/

class Ep_sous_domaine_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	*/    
    private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */    
    private $version;

    private $dataset;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	*/
    public function __construct($plugin_name, $version, $dataset)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->dataset = $dataset;
    }    

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	*/
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Codebase_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Codebase_Print_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
         // Enqueue Select2 CSS
        if ($this->plugin_name === 'ep-sous-domaine-plugin') {
            wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css', array(), '4.0.13', 'all');
        }

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ep_sous_domaine-admin.css', array(), $this->version, 'all');
    }
    
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	*/    
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Codebase_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Codebase_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        // Enqueue jQuery UI Autocomplete
        // wp_enqueue_script( 'jquery-ui-autocomplete' );
        if ($this->plugin_name === 'ep-sous-domaine-plugin') {
            // Enqueue Select2 script
            wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array('jquery'), '4.0.13', true);
        }
        wp_enqueue_media();  
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ep_sous_domaine-admin.js', array('jquery'), $this->version, false);
        wp_localize_script( $this->plugin_name, 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

    }


    public function add_menu()
    {        
        add_menu_page('Ep sous domaine', 'EP Sous Domaine', 'manage_woocommerce', $this->plugin_name, array($this, 'ep_sous_domaine'));
		// ADD CUSTOMER LIST
		// add_submenu_page(
		// 	$this->plugin_name,
		// 	'Liste clients',
		// 	'Liste clients',
		// 	'manage_woocommerce',
		// 	$this->plugin_name . '-liste-clients',
		// 	array($this, 'displayListeClients')
		// );                 
                              
    }

    public function displayListeClients(){
        require_once(plugin_dir_path(__FILE__) . 'modules/class-ep_sous_domaine-customer-table.php');
        $customer_table = new EP_Sous_Domaine_Customer_Table('ep-sous-domaine-plugin');
        $customer_table->prepare_items();
        include(plugin_dir_path(__FILE__) . 'partials/ep-sous-domaine-plugin-add-clients.php');      
        include(plugin_dir_path(__FILE__) . 'partials/ep-sous-domaine-plugin-listes-clients.php');
    }

    public function ep_sous_domaine(){
        $page=$_GET['page'];
        if(isset($page)){
                require_once(plugin_dir_path(__FILE__) . 'modules/class-ep_sous_domaine-customer-table.php');
                $customer_table = new EP_Sous_Domaine_Customer_Table($page);
                $customer_table->prepare_items();                                  
                require_once(plugin_dir_path(__FILE__) . 'modules/class-ep_sous_domaine-customer-list-product.php');
                $product_list_table = new Product_List_Table($page,$_GET['customer_id'] ?? NULL);
                $product_list_table->prepare_items(); 
                            
                if(isset($_GET['action']) && $_GET['action']=="add"){
                    include(plugin_dir_path(__FILE__) . 'partials/ep-sous-domaine-plugin-add-clients.php');//formulaire ajout client                
                    include(plugin_dir_path(__FILE__) . 'partials/ep-sous-domaine-plugin-listes-clients.php');//tableau liste client
                }elseif(isset($_GET['customer_id'])){
                    $idproduct = $_GET['idproduct'];           
                    
                    if(isset($_GET['action'])){
                        if($_GET['action']=='delete'){
                        global $wpdb;
                        $table_name = $wpdb->prefix . 'product_epsd';                
                        // Supprimer l'entrée de la table "product_epsd"
                        
                        $test= $wpdb->delete($table_name, array('id' => $idproduct)); 
                    
                        // Ajouter la notification
                        add_settings_error(
                            'product_deleted',
                            'product_deleted',
                            'Le produit a été supprimé avec succès.',
                            'updated'
                        );
                        // Rediriger vers la page "ep-sous-domaine-plugin-personnaliser" avec la notification
                        wp_redirect(admin_url('admin.php?page='.$page.'&customer_id='.$_GET['customer_id']));
                        }elseif($_GET['action']=='configurer'){  
                            $options = $this->dataset->get_options();        
                            $custom_options = $this->dataset->get_custom_options_data();
                            $options_table = $this->dataset->get_options_table();
                            $selectRules = $this->dataset->get_select_rules();
                            $covers = $this->dataset->get_covers();
                            $this->display_fiches_produits_save($idproduct,$options,$_GET['customer_id']);
                            echo "<form style='padding:0 15px;margin-top: 20px;' id='form-id-panel' method='post'>" ;
                                include(plugin_dir_path(__FILE__) . 'partials/ep-sous-domaine-plugin-fiche-produits.php');
                            echo '</form>';
                        }
                                                            
                    }
                    if(!isset($_GET['action'])){
                        include(plugin_dir_path(__FILE__) . 'partials/ep-sous-domaine-plugin-personnaliser.php');
                    }
                }
                else{
                    echo '<h2>Ajouter un sous domaine</h2>';                
                    $this->displayListeClients();
                }
        }
    }

    //Save all options
    public function display_fiches_produits_save($id,$options,$idUser){          
        if(isset($_POST['btn_enregistrer_panel'])){
            $options = $this->dataset->get_options();	
            return $this->meta_post_ep($id, $options,$idUser);		
        }
    }

    //Save all options
    public function display_fiches_produits($id,$options){          
        return $this->meta_post_ep($id, $options, null);		
    }

    //meta_post_ep
    public function meta_post_ep($id, $options,$idUser){
        $idUser = $_GET['customer_id'];
        foreach ($options as $option) {
            $data = $this->get_post_meta_ep($id, $option);	
            if ($option === 'production') {
                update_post_meta($id, 'epsd_' . $option . '_delay', $_POST['epsd_' . $option . '_delay']);
                update_post_meta($id, 'epsd_' . $option . '_rules', $_POST['epsd_' . $option . '_rules']);
                update_post_meta($id, 'epsd_' . $option . '_rules_value', $_POST['epsd_' . $option . '_rules_value']);
                update_post_meta($id, 'epsd_' . $option . '_rush_deadline',  $_POST['epsd_' . $option . '_rush_deadline']);
            } else {
                update_post_meta($id, 'epsd_' . $option, $_POST['epsd_' . $option]);
            }
            if ($option === 'format') {
                update_post_meta($id, 'epsd_' . $option, $_POST['epsd_' . $option]);
            }
            if ($option === 'cover') {
                update_post_meta($id, 'epsd_' . $option . '_value', $_POST['epsd_' . $option . '_value']);
            } else if ($option === 'display' || $option === 'select_rules') {
                update_post_meta($id, 'epsd_' . $option . '_values', $_POST['epsd_' . $option . '_values']);
            } else if ($option === 'quantity') {
                update_post_meta($id, 'epsd_' . $option . '_price_array', $_POST['epsd_' . $option . '_price_array']);
                update_post_meta($id, 'epsd_' . $option . '_default_quantity', $_POST['epsd_' . $option . '_default_quantity']);
                update_post_meta($id, 'epsd_' . $option . '_max', $_POST['epsd_' . $option . '_max']);
                update_post_meta($id, 'epsd_' . $option . '_min', $_POST['epsd_' . $option . '_min']);
            } else if ($option === 'custom_format') {
                update_post_meta($id, 'epsd_' . $option . '_width', $_POST['epsd_' . $option . '_width']);
                update_post_meta($id, 'epsd_' . $option . '_height', $_POST['epsd_' . $option . '_height']);
                update_post_meta($id, 'epsd_' . $option . '_setup_price', $_POST['epsd_' . $option . '_setup_price']);
                update_post_meta($id, 'epsd_' . $option . '_p1000', $_POST['epsd_' . $option . '_p1000']);
                update_post_meta($id, 'epsd_' . $option, $_POST['epsd_' . $option]);
            } else {
                update_post_meta($id, 'epsd_' . $option . '_values', $_POST['epsd_' . $option . '_values']);
                update_post_meta($id, 'epsd_' . $option . '_default_value', $_POST['epsd_' . $option . '_default_value']);
            }
        }
        update_post_meta($id, 'epsd_image_preview', $_POST['image_preview']);
        update_post_meta($id, 'designer_active_'.$idUser, $_POST['designer_active']);
    }

    // Get post_meta etapes print
    public function get_post_meta_ep($id, $option){
        $data = array();
            if ($option === 'production') {
                $data['post_delay'] = get_post_meta($id, 'etapes_print_' . $option . '_delay', true);
                $data['post_rules'] = get_post_meta($id, 'etapes_print_' . $option . '_rules', true);
                $data['post_rules_value'] = get_post_meta($id, 'etapes_print_' . $option . '_rules_value', true);
                $data['post_rush_deadline'] = get_post_meta($id, 'etapes_print_' . $option . '_rush_deadline',  true);
            }else{
                $data['post_option'] = get_post_meta($id, 'etapes_print_' . $option,true);
            }
            if ($option === 'format') {
                $data['post_format'] = get_post_meta($id, 'etapes_print_' . $option, true);
            }
            if ($option === 'cover') {
                $data['post_cover'] = get_post_meta($id, 'etapes_print_' . $option . '_value', true);
            } else if ($option === 'display' || $option === 'select_rules') {
                $data['post_display'] = get_post_meta($id, 'etapes_print_' . $option . '_values', true);
            } else if ($option === 'quantity') {
                $data['post_price_array'] = get_post_meta($id, 'etapes_print_' . $option . '_price_array', true);
                $data['post_default_quantity'] = get_post_meta($id, 'etapes_print_' . $option . '_default_quantity', true);
                $data['post_max'] = get_post_meta($id, 'etapes_print_' . $option . '_max', true);
                $data['post_min'] = get_post_meta($id, 'etapes_print_' . $option . '_min', true);
            } else if ($option === 'custom_format') {
                $data['post_width'] =  get_post_meta($id, 'etapes_print_' . $option . '_width', true);
                $data['post_height'] = get_post_meta($id, 'etapes_print_' . $option . '_height', true);
                $data['post_setup_price'] = get_post_meta($id, 'etapes_print_' . $option . '_setup_price', true);
                $data['post_p1000'] = get_post_meta($id, 'etapes_print_' . $option . '_p1000', true);
                $data['post_option'] = get_post_meta($id, 'etapes_print_' . $option, true);
            } else {
                $data['values'] = get_post_meta($id, 'etapes_print_' . $option . '_values', true);
                $data['default_values'] = get_post_meta($id, 'etapes_print_' . $option . '_default_value', true);
            }
        return $data;
    }
    
    public function woocommerce_search_customers() {
        $searchText = $_POST['search_text']; // POST    
        // Effectuer votre recherche de clients ici et obtenir les résultats
        $args = array(
            'number' => 10,
            'search' => '*' . sanitize_text_field( $searchText ) . '*',
            'order' => 'DESC',
        );
        $results = get_users( $args );
        // Générer un tableau des résultats de recherche
        $searchResults = array();
        foreach ($results as $result) {
            $searchResults[] = array(
                'id' => $result->ID,
                'text' => $result->display_name,
                'email' => $result->user_email 
            );
        }   
        echo json_encode($searchResults);
        wp_die();
    }

    public function get_product_data() {
        // Récupérez les données du produit en fonction de la valeur envoyée via AJAX
        $search_text = $_POST['search_text'];
        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'meta_query'     => array(
                array(
                    'value'   => $search_text,
                    'compare' => 'LIKE',
                ),
            ),
            'orderby'        => 'ID',
            'order'          => 'ASC',
            'numberposts'    => 10,
        );
        $results = get_posts($args);
        $searchResults = array();
        foreach ($results as $result) {
            $product = wc_get_product($result->ID);
            $searchResults[] = array(
                'id'   => $product->get_id(),
                'text' => $product->get_name()
            );
        }
        echo json_encode($searchResults);
        wp_die();
    }
        
    // Insertion dans la table "product_epsd"
    public function insert_product_epsd() {
        $id_product = sanitize_text_field($_POST['id_product']);       
        if (isset($id_product)) {            
            global $wpdb;
            $table_name = $wpdb->prefix . 'product_epsd';
            $existing_product = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE fk_id_product = %d AND fk_id_user = %d", $id_product, $_GET['customer_id'] ) );

            if ($existing_product > 0) {
                $response = array(
                    'success' => false,
                    'message' => 'Le produit est déjà présent'
                );
            }else {
                // Récupérer les informations du produit
                $product = wc_get_product($id_product);

                if ($product) {
                    $data= array(
                        'nom_product'=>$product->get_name(),
                        'image'=>$product->get_image_id(),
                        'description'=>$product->get_description(),
                        'fk_id_product'=>$product->get_id(),
                        'fk_id_user'=>$_POST['customer_id']
                    );
                    $wpdb->insert($table_name, $data);

                    // Envoyer une réponse JSON pour confirmer l'insertion
                    $response = array(
                        'success' => true,
                        'message' => 'Produit inséré avec succès'
                    );
                    $options = $this->dataset->get_options();		
                    $this->display_fiches_produits($product->get_id() , $options);
                } else {
                    // Le produit n'a pas été trouvé
                    $response = array(
                        'success' => false,
                        'message' => 'Produit non trouvé'
                    );
                }
            }
        } else {
            // Si aucun ID de produit n'est présent dans la requête, envoyer une réponse JSON avec une erreur
            $response = array(
                'success' => false,
                'message' => 'ID produit non fourni'
            );
        }
        wp_send_json($response);
    }
           
    public function insert_client_epsd() {
        if (isset($_POST['client_id'])) {
            $client_id = sanitize_text_field($_POST['client_id']);    
            // Vérifier si le client existe déjà dans la table "clients_epsd"
            global $wpdb;
            $table_name = $wpdb->prefix . 'clients_epsd';
            $existing_client = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE fk_id_customer = %d", $client_id));
    
            if ($existing_client) {
                $response = array(
                    'success' => false,
                    'message' => 'Le client existe déjà'
                );
            } else {
                // Insérer l'ID du client dans la table "clients_epsd"
                $data = array(
                    'fk_id_customer' => $client_id
                );
                $wpdb->insert($table_name, $data);
                $response = array(
                    'success' => true,
                    'message' => 'Client inséré avec succès'
                );
            }
        } else {
            $response = array(
                'success' => false,
                'message' => 'ID client non fourni'
            );
        }
        wp_send_json($response);
    }
    
    // Update status clients
    public function update_status_callback() {
            // Récupérez l'ID de l'utilisateur et le statut à partir de la requête AJAX
            $epsd_id = isset($_POST['epsd_id']) ? intval($_POST['epsd_id']) : 0;
            $status = $_POST['status'];            
            $data = [
                'status' => $status
            ];
            global $wpdb;
            $table = $wpdb->prefix . 'clients_epsd';
            $where = array(id => $epsd_id);
            $response = $wpdb->update($table, $data, $where);   
            if ($response) {
                // Réponse réussie
                echo 'success';
            } else {
                // Erreur lors de la mise à jour
                echo 'error';
            }                      
        wp_die();
    }        
    
}
?>
