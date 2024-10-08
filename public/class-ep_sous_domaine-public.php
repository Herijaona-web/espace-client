<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://takamoastudio.com/
 * @since      1.0.0
 *
 * @package    Ep-sous-domaine-plugin
 * @subpackage Ep-sous-domaine-plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ep-sous-domaine-plugin
 * @subpackage Ep-sous-domaine-plugin/public
 * @author     Takamoa Studio <responsable@takamoastudio.com>
 */

class Ep_sous_domaine_Public {
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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	*/
	public function __construct( $plugin_name, $version, $dataset ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->dataset = $dataset;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	*/
	public function enqueue_styles() {

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

		
		// if ( is_page( 'espace-client' ) ) {
			// wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css' );
			// wp_enqueue_style( 'etapes-print', plugin_dir_url( 'etapes-print/public') . 'public/css/etapes-print-public.css', array(), null, 'all' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ep_sous_domaine-public.css', array(), $this->version, 'all' );
			
		// }

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	*/
	public function enqueue_scripts() {

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ep_sous_domaine-public.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'price', plugin_dir_url( __FILE__ ) . 'js/ep_sous_domaine-price.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name, 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		$this->initEPData();

	}
	/**
	 * Add lien in the front
	 */
	public function add_custom_menu_item() {
		if ( function_exists('is_plugin_active') && is_plugin_active( 'etapes-print/etapes-print.php' ) ) {
			if (is_user_logged_in() && in_array( 'customer', (array) wp_get_current_user()->roles ) ) {	
				$user_id = get_current_user_id();			
				global $wpdb;
				$table_name = $wpdb->prefix . 'clients_epsd';
				$query = $wpdb->prepare("SELECT * FROM $table_name WHERE fk_id_customer = %d AND status=1", $user_id);
				$user_info = $wpdb->get_row($query, ARRAY_A);
				if($user_info){

		?>
					<script type="text/javascript">
						jQuery(document).ready(function($) {
							var menuItems = $('.awb-menu__main-ul > li');
				
							if (menuItems.length) {
								var base_url = '<?php echo home_url(); ?>';
								var customLink = $('<li class="menu-item"><a href="' + base_url + '/espace-client">Espace-client</a></li>');	
								// Trouver l'élément "Demande de devis"
								var demandeDeDevisItem = menuItems.find('.menu-text:contains("Demande de devis")').closest('li');	
								if (demandeDeDevisItem.length) {
									demandeDeDevisItem.after(customLink);
								}
							}
						});
					</script>
		<?php
		}}}
	}	

	public function initEPData(){
		// $the_id = 4874;
		$current_url = home_url($_SERVER['REQUEST_URI']);
		$product_slug = basename($current_url);
		
		// Récupérer le post correspondant au slug
		$product = get_page_by_path($product_slug, OBJECT, 'product');
		
		if ($product && $product->post_type === 'product') {
			$the_id = $product->ID;
		}				
		if ( get_post_meta( $the_id, 'etapes_print_quantity', true ) ){
			wp_enqueue_script('vue', 'https://unpkg.com/vue@3', [], '3', true);

			wp_enqueue_script('pdf', 'https://cdn.jsdelivr.net/npm/pdfjs-dist@2.10.377/build/pdf.min.js', [], '3', true);
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ep_sous_domaine-public.js', array( 'jquery' ), $this->version, true );						
			wp_enqueue_script( 'etapes-print', plugin_dir_url( 'etapes-print/public' ) . 'public/js/etapes-print-public-display.js', array( 'jquery' ), $this->version, true );
			
			$wc_cart_session = $this->wh_cartOrderItemsbyNewest();
			$current_item = $_GET['key'] ? $wc_cart_session[$_GET['key']] : '';

			foreach ($this->dataset->get_options() as $option) {
				if ($option === 'production') {				
					$etapes_print_delay_delivery = get_post_meta( $the_id, 'etapes_print_' . $option . '_delay', true );				
					if (!$etapes_print_delay_delivery) {
						$etapes_print_delay_delivery = get_option('etapes_print_delay_delivery');
						
					}
					$etapes_print_production_rules = get_post_meta( $the_id, 'etapes_print_' . $option . '_rules', true );
					
					if ($etapes_print_production_rules) {					
						$data['production_rules'] = get_post_meta( $the_id, 'etapes_print_' . $option . '_rules_value', true );
					}
					$data['delay_delivery'] = $etapes_print_delay_delivery;
				} else if (get_post_meta( $the_id, 'etapes_print_' . $option, true )) {
					if ($option === 'cover') {
						$cover_code = get_post_meta( $the_id, 'etapes_print_' . $option . '_value', true );
						$etapes_print_cover = $this->dataset->get_cover_by_code($cover_code);
						
						$data['cover'] = $etapes_print_cover;
						if($current_item){
							$data['cover']['default_format'] = $current_item["etapes_print_cover_format"];
							$data['cover']['default_paper'] = $current_item["etapes_print_cover_paper"];
							$data['cover']['default_refinement'] = $current_item["etapes_print_cover_refinement"];
						}
					} else if ($option === 'select_rules') {
						$select_rule_codes = get_post_meta( $the_id, 'etapes_print_' . $option . '_values', true );
						$data[$option] = $this->dataset->get_select_rules_by_codes($select_rule_codes);
					} else if ($option === 'custom_format') {
						$etapes_print_format_width = get_post_meta( $the_id, 'etapes_print_' . $option . '_width', true );
						$etapes_print_format_height = get_post_meta( $the_id, 'etapes_print_' . $option . '_height', true );
						$etapes_print_format_setup_price = get_post_meta( $the_id, 'etapes_print_' . $option . '_setup_price', true );
						$etapes_print_format_p1000 = get_post_meta( $the_id, 'etapes_print_' . $option . '_p1000', true );
						$etapes_print_format_default_width = explode(':', $etapes_print_format_width)[0];
						$etapes_print_format_default_height = explode(':', $etapes_print_format_height)[0];
						// set default format value
						if ($current_item && $current_item['etapes_print_format'] === 'custom_format') {
							$etapes_print_format_default_width =  $current_item['etapes_print_custom_format_width'];
							$etapes_print_format_default_height =  $current_item['etapes_print_custom_format_height'];
						}
						$data[$option] = array(
							'default_width' => $etapes_print_format_default_width,
							'default_height' => $etapes_print_format_default_height,
							'width' => $etapes_print_format_width,
							'height' => $etapes_print_format_height,
							'setup_price' => $etapes_print_format_setup_price,
							'p1000' => $etapes_print_format_p1000
						);
					} else if ($option === 'quantity') {							
						$epsd_price_array = get_post_meta( $the_id, 'epsd_' . $option . '_price_array', true );
						$epsd_price_arr = $epsd_price_array ? 'epsd_' : 'etapes_print_';
						$etapes_print_quantity_price_array = get_post_meta( $the_id, $epsd_price_arr . $option . '_price_array', true );
						if (!$etapes_print_quantity_price_array) {
							$etapes_print_quantity_price_array = get_option('etapes_print_price_array');
						}
						
						$epsd_default_quantity = get_post_meta( $the_id, 'epsd_' . $option . '_default_quantity', true );
						$epsd_default_qty  = $epsd_default_quantity ? 'epsd_' : 'etapes_print_';
						$etapes_print_quantity_default_quantity = get_post_meta( $the_id, $epsd_default_qty . $option . '_default_quantity', true );
						if (!$etapes_print_quantity_default_quantity) {
							$etapes_print_quantity_default_quantity = get_option('etapes_print_default_quantity');
						}

						$data['price_array'] = $etapes_print_quantity_price_array;
						if($current_item){
							$data['default_quantity'] = $current_item["etapes_print_quantity"];
						}else{
							$data['default_quantity'] = $etapes_print_quantity_default_quantity;
						}
						$epsd_quantity_min = get_post_meta( $the_id, 'epsd_' . $option . '_min', true );
						$epsd_quantity_mn = $epsd_quantity_min ? 'epsd_' : 'etapes_print_';
						$etapes_print_quantity_min = get_post_meta( $the_id, $epsd_quantity_mn . $option . '_min', true );

						$epsd_quantity_max = get_post_meta( $the_id, 'epsd_' . $option . '_min', true );
						$epsd_quantity_mx = $epsd_quantity_max ? 'epsd_' : 'etapes_print_';
						$etapes_print_quantity_max = get_post_meta( $the_id, $epsd_quantity_mx . $option . '_max', true );

						$data[$option] = array( 
							'min' => $etapes_print_quantity_min,
							'max' => $etapes_print_quantity_max
						);
					} else if (	get_post_meta( $the_id, 'etapes_print_' . $option . '_values', true ) &&
							get_post_meta( $the_id, 'etapes_print_' . $option . '_default_value', true ) ) {

						$epsd_values = get_post_meta( $the_id, 'epsd_' . $option . '_values', true );
						$epsd_default_value = get_post_meta( $the_id, 'epsd_' . $option . '_default_value', true );
						$type = 'etapes_print_';
						if($epsd_values || $epsd_default_value){
							$type = 'epsd_';	
						}
						$etapes_print_options_values = get_post_meta( $the_id, $type . $option . '_values', true );
						$etapes_print_default_value = get_post_meta( $the_id, $type . $option . '_default_value', true );

						$data[$option] = array( 
							'options_values' => $etapes_print_options_values,
							'default_value' => $current_item ? $current_item["etapes_print_$option"] : $etapes_print_default_value
						);
					}
				}
			}
			if($current_item){
				$data['filesPdf'] = $current_item['etapes_print_pdf'];
			}
			$data['delivery_price'] = get_option('etapes_print_delivery_price');
			wp_localize_script( $this->plugin_name, 'etapes_print_vue_object', $data);
			
		}
	}
			
	public function wh_cartOrderItemsbyNewest() {
		if (WC()->cart->get_cart_contents_count() == 0) {
			return;
		}
		$cart_sort = [];
		foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
			$cart_sort[$cart_item_key] = WC()->cart->cart_contents[$cart_item_key];
		}
		return WC()->cart->cart_contents = array_reverse($cart_sort);
	}

	public function supprimer_description_courte_produit() {
		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
		
	}

	public function custom_sous_domaine_display_ep_val_callback($etapes_print_option_table, $etapes_print_option_value) {
		$format = $this->dataset->get_option_by_code($etapes_print_option_table, $etapes_print_option_value);
		echo $format->name;
	}

	public function get_options_callback(){
		$options = $this->dataset->get_options();
		return $options;
	}

	public function get_cover_by_code_callback($cover_code){
		return $this->dataset->get_cover_by_code($cover_code);
	}

function filter_products() {
    $product_name = $_POST['reference'];
	$args = array(
		'post_type' => 'product',
		'posts_per_page' => -1,
		'name' => $product_name
	);
// $products = new WP_Query($args);
	$products = wc_get_products($args);
	if ($products) {
?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center bg-light" id="example">
                <thead class="table-light">
                    <tr>
                        <th>Réference</th>
                        <th>Format</th>
                        <th>Type d'impression</th>
                        <th>Pelliculage</th>
                        <th>Quantité</th>
                        <th>Aperçu</th>
                        <th>Panier</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $product):;?>                
                    <tr>

                        <td><?php echo $product->get_sku() ?  $product->get_sku() : $product->get_name(); ?></td>
                        <td>
                            <?php 
                                $etapes_print_default_value = get_post_meta( $product->get_id(), 'etapes_print_format_default_value', true );
                                do_action('sous_domaine_display_ep_val', 'etapes_print_format', $etapes_print_default_value); 
                            ?></td>
                        <td>
                            <?php 
                                $etapes_print_default_value = get_post_meta( $product->get_id(), 'etapes_print_colors_default_value', true );
                                do_action('sous_domaine_display_ep_val', 'etapes_print_colors', $etapes_print_default_value); 
                            ?>                      
                        </td>
                        <td>
                            <?php 
                                $etapes_print_default_value = get_post_meta( $product->get_id(), 'etapes_print_refinement_default_value', true );
                                do_action('sous_domaine_display_ep_val', 'etapes_print_refinement', $etapes_print_default_value); 
                            ?>
                        </td>
                        <td>
                            <input type="number" class="form-control" name="quantity" value="1" min="1" step="1">
                        </td>
                        <td>
                            <div class="w-100 m-auto">
                                <?php echo $product->get_image('thumbnail'); ?>
                            </div>
                        </td>                    
                        <td>
                            <form action="" method="post">
                            <div style="width:300px;">
                                <button  type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="button btn btn_epsd text-white "  style="background-color:<?php echo $epsd_fond_site;?>">Ajouter au panier</button>
                            </div>
                            </form>
                        </td>
                    
                    </tr>
                <?php endforeach;?>    
                </tbody>
                </table>
            </div>
<?php		
	}
}

function get_id_product_by_url_page() :int {
	$current_url = home_url($_SERVER['REQUEST_URI']);
	$product_slug = basename($current_url);
	$product = get_page_by_path($product_slug, OBJECT, 'product');
	return $product ? $product->ID : 0;
}

function change_add_to_cart_button_text($text) {
	$id_product = $this->get_id_product_by_url_page();
	$espace_client_page = get_query_var( 'espace_client_page' );
	$designer_active = get_post_meta($id_product, 'designer_active_'.get_current_user_id(), true);
    $text = $espace_client_page && $designer_active ? 'Personnaliser' : 'Ajouter au panier';
    return $text;
}	

}
