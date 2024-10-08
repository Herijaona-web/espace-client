<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://takamoastudio.com/
 * @since      1.0.0
 *
 * @package    Ep-sous-domaine-plugin
 * @subpackage Ep-sous-domaine-plugin/includes
 */
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Ep-sous-domaine-plugin
 * @subpackage Ep-sous-domaine-plugin/includes
 * @author     Takamoa Studio <responsable@takamoastudio.com>
 */
class Ep_sous_domaine {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Ep_sous_domaine_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
    protected $loader;
    
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
    protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
    protected $version;

	/**
	 * DATASET
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Ep_sous_domaine_Dataset    $dataset
	 */	 
    // protected $dataset_epsd;
	protected $dataset;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	*/
	public function __construct() {
		if ( defined( 'EP_SOUS_DOMAINE_PLUGIN_VERSION' ) ) {
			$this->version = EP_SOUS_DOMAINE_PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'ep-sous-domaine-plugin';
		$this->load_dependencies();
		$this->set_locale();
		$this->set_routes();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Codebase_Loader. Orchestrates the hooks of the plugin.
	 * - Codebase_i18n. Defines internationalization functionality.
	 * - Codebase_Router. Defines studio routes.
	 * - Codebase_Admin. Defines all hooks for the admin area.
	 * - Codebase_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	*/
    private function load_dependencies() {
		// dataset etapes print
		require_once WP_PLUGIN_DIR . '/etapes-print/includes/class-etapes-print-dataset.php';
		//dataset ep spus domaine
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ep_sous_domaine-dataset.php';
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		*/		
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ep_sous_domaine-loader.php';
        
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ep_sous_domaine-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the router area.
		*/		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ep_sous_domaine-router.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ep_sous_domaine-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */		
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ep_sous_domaine-public.php';

        $this->loader = new Ep_sous_domaine_Loader();
        // $this->dataset_epsd = new Ep_sous_domaine_Dataset();

		$this->dataset = new Etapes_Print_Dataset();
    }
	
	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Codebase_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	*/
	private function set_locale() {

		$plugin_i18n = new Ep_sous_domaine_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the studio area functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	*/
	private function set_routes() {
		$plugin_routes = new Ep_sous_domaine_Router();
		$this->loader->add_filter( 'template_include', $plugin_routes, 'include_template' );
		$this->loader->add_filter( 'init', $plugin_routes, 'flush_rules' );		
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	*/
	private function define_admin_hooks() {

		$plugin_admin = new Ep_sous_domaine_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_dataset() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu' );

		// $this->loader->add_action('admin_init', $plugin_admin, 'enregistrer_reglages');

		// REST API
		// $this->loader->add_action( 'rest_api_init', $plugin_admin, 'init_admin_route' );

		//call ajax		
		//recherche clients
		$this->loader->add_action('wp_ajax_woocommerce_search_customers',$plugin_admin, 'woocommerce_search_customers');
		$this->loader->add_action('wp_ajax_nopriv_woocommerce_search_customers',$plugin_admin, 'woocommerce_search_customers');	

		//rechercher produits
		$this->loader->add_action( 'wp_ajax_get_product_data',$plugin_admin, 'get_product_data' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_product_data',$plugin_admin, 'get_product_data' );
				

		// Action AJAX pour insérer l'ID du client dans la table "clients_epsd"
		$this->loader->add_action('wp_ajax_insert_client_epsd', $plugin_admin, 'insert_client_epsd');
		$this->loader->add_action('wp_ajax_nopriv_insert_client_epsd', $plugin_admin, 'insert_client_epsd');

		//insertion product
		$this->loader->add_action( 'wp_ajax_insert_product_epsd',$plugin_admin,  'insert_product_epsd' );
		$this->loader->add_action( 'wp_ajax_nopriv_insert_product_epsd',$plugin_admin,  'insert_product_epsd' );

		//delete product epsd
		// $this->loader->add_action('admin_post_delete_product_epsd',$plugin_admin, 'delete_product_epsd');
		// $this->loader->add_action('admin_post_nopriv_delete_product_epsd',$plugin_admin, 'delete_product_epsd');		
		
		
		$this->loader->add_action('wp_ajax_update_status',$plugin_admin, 'update_status_callback');
		// $this->loader->add_action('wp_ajax_nopriv_update_status',$plugin_admin, 'update_status_callback');
		// $this->loader->add_action('wp_ajax_insert_client_epsd',$plugin_admin, 'insert_client_epsd');
		// $this->loader->add_action('wp_ajax_nopriv_insert_client_epsd',$plugin_admin, 'insert_client_epsd');
		// $this->loader->add_action('wp_ajax_add_new_customer',$plugin_admin, 'add_new_customer');
		// $this->loader->add_action('wp_ajax_nopriv_add_new_customer',$plugin_admin, 'add_new_customer');
		// $this->loader->add_action( 'woocommerce_product_data_panels', $plugin_admin, 'product_panels' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */	
    private function define_public_hooks() {

		$plugin_public = new Ep_sous_domaine_Public( $this->get_plugin_name(), $this->get_version(), $this->get_dataset() );

		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		//FILTER
		$this->loader->add_filter('wp_footer',$plugin_public,'add_custom_menu_item');
		//Remove title and description
		$this->loader->add_action('wp',$plugin_public, 'supprimer_description_courte_produit');
		// create action sous_domaine_display_ep_val
		$this->loader->add_action('sous_domaine_display_ep_val',$plugin_public, 'custom_sous_domaine_display_ep_val_callback', 10, 2);
		$this->loader->add_filter('get_options',$plugin_public, 'get_options_callback', 10, 2);
		$this->loader->add_filter('get_cover_by_code',$plugin_public, 'get_cover_by_code_callback', 10, 1);
		// ajax front
		$this->loader->add_action( 'wp_ajax_filter_products',$plugin_public,'filter_products' );
		$this->loader->add_action( 'wp_ajax_nopriv_filter_products',$plugin_public,'filter_products' );
		
		$this->loader->add_filter('woocommerce_product_single_add_to_cart_text',$plugin_public, 'change_add_to_cart_button_text');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	*/
	public function run() {
		$this->loader->run();
        
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	*/
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Ep_sous_domaine_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function get_dataset() {
		return $this->dataset;
	}


}
