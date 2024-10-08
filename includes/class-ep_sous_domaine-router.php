<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://takamoastudio.com/
 * @since      1.0.0
 *
 * @package    Ep-sous-domaine-plugin
 * @subpackage Ep-sous-domaine-plugin/studio
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ep-sous-domaine-plugin
 * @subpackage Ep-sous-domaine-plugin/studio
 * @author     Takamoa Studio <responsable@takamoastudio.com>
 */

class Ep_sous_domaine_Router{
	public function include_template( $template )
	{
			//try and get the query var we registered in our query_vars() function
			$espace_client_page = get_query_var( 'espace_client_page' );

			//if the query var has data, we must be on the right page, load our custom template
			if($espace_client_page) {
				global  $wpdb;
				$client_id = get_current_user_id();
				$table_name = $wpdb->prefix . 'clients_epsd';
				$existing_client = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE fk_id_customer = %d", $client_id));
				// if (is_user_logged_in() && if user has espace client access) {
				if ($existing_client && $existing_client->status == 1) {
					if ( $espace_client_page === 'home' ) {
						return plugin_dir_path( dirname( __FILE__ ) ) . "espace-client/templates/home.php";
					} else if ( $espace_client_page === 'product' ) {
						return plugin_dir_path( dirname( __FILE__ ) ) . "espace-client/templates/product.php";
					} else if ( $espace_client_page === 'category' ) {
						return plugin_dir_path( dirname( __FILE__ ) ) . "espace-client/templates/categories.php";
					} else if ( $espace_client_page === 'commander' ) {
						return plugin_dir_path( dirname( __FILE__ ) ) . "espace-client/templates/commander.php";
					} else if ( $espace_client_page === 'filtre_commande' ) {
						return plugin_dir_path( dirname( __FILE__ ) ) . "espace-client/templates/filtre_commande.php";
					}
				} else {
					wp_redirect(home_url().'/mon-compte');
				}
			}
			return $template;
	}

	public function flush_rules()
	{
			$this->rewrite_rules();
			flush_rewrite_rules();
	}

	public function rewrite_rules()
	{
		add_rewrite_rule( 'espace-client/commander', 'index.php?espace_client_page=commander', 'top');
		add_rewrite_rule( 'espace-client/filtre_commande', 'index.php?espace_client_page=filtre_commande', 'top');
		add_rewrite_rule( 'espace-client/produit/(.+?)$', 'index.php?espace_client_page=product&my_product=$matches[1]', 'top');
		add_rewrite_rule( 'espace-client/categories', 'index.php?espace_client_page=category', 'top');
		add_rewrite_rule( 'espace-client', 'index.php?espace_client_page=home', 'top');
		add_rewrite_tag( '%espace_client_page%', '([^&]+)' );
	}
	
}