<?php
    $user_id = get_current_user_id(); 
    $epsd_nom_site = get_user_meta($user_id, 'epsd_nom_site', true);
    $epsd_titre_site = get_user_meta($user_id, 'epsd_titre_site', true);
    $epsd_description_site = get_user_meta($user_id, 'epsd_description_site', true);
    $epsd_fond_site = get_user_meta($user_id, 'epsd_fond_site', true);
    $epsd_logo_site = get_user_meta($user_id, 'logo_path', true);       
    $epsd_banniere_site = get_user_meta($user_id, 'banniere_path', true);
    $epsd_access_categories = get_user_meta($user_id, 'access_categories', true);     
    $epsd_id_categories = get_user_meta($user_id,'epsd_category_product',true);
    $epsd_access_commande = get_user_meta($user_id, 'access_commande', true);

    global $woocommerce;
    $cart_items_number = $woocommerce->cart->cart_contents_count;
    global $wpdb;
    $table_name = $wpdb->prefix . "product_epsd";
    $resultats = $wpdb->get_results("SELECT * FROM $table_name WHERE fk_id_user=$user_id");
    if ($resultats && isset($_POST['add-to-cart'])) {
      $redirect_url = home_url('espace-client?add=1');
      wp_safe_redirect($redirect_url); // Redirection vers l'URL spécifiée
      exit;
  } 
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">  
  <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>  
  <?php include(plugin_dir_path(__FILE__) . '../styles/header.css.php');?>
</head>
<body <?php body_class(); ?> id="body_epsd">
<div class="custom">
    <header id="header_epsd" class="site_header_epsd">
      <a href="<?php echo site_url('/espace-client'); ?>" class="brand">
        <img src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/' . 'ep' . '.png'; ?>" alt="<?php echo 'ep.png'; ?>">
      </a>
      <nav class="navbar navbar-expand">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown m-2">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="glyphicon icon-furnitureuser" aria-hidden="true"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li>
                  <a class="dropdown-item" href="<?php echo home_url().'/panier'; ?>" title="Panier">Mon Panier <span>(<?= $cart_items_number ?>)</span></a>
              </li>
              <li>
                  <a class="dropdown-item" href="<?php echo home_url(); ?>" title="Panier">Etapes Print</span></a>
              </li>
              <li>
                  <a class="dropdown-item" href="<?php echo wp_logout_url(home_url().'/mon-compte'); ?>" title="Déconnexion">Déconnexion</a>
              </li>
            </ul>
          </li>
          <li class="nav-item m-2 d-none">
            <a class="nav-link" href="http://etapes-print-prod.local/panier/">
              <i class="glyphicon icon-furnitureshopping-cart" aria-hidden="true"></i>
            </a>
          </li>
        </ul>
      </nav>
    </header>


    <?php if($epsd_banniere_site):;?>
      <div style="background-image: url('<?php echo $epsd_banniere_site; ?>');" class="banniere_site">
      <?php else:;?>
      <div class="banniere_site_default">
      <?php endif;?>  
          <div class="bloc_logo_epsd" style="align-items: center;">
              <p style="margin: 5px; max-width: 200px;">
                  <img src="<?php echo $epsd_logo_site ? $epsd_logo_site : $epsd_logo_site=plugin_dir_url( dirname( __FILE__ ) ) . 'images/' .'logo_par_defaut'. '.png' ; ?>" class="logo" alt="<?php echo $epsd_logo_site;  ?>" style="max-height: 56px;">
              </p>
              <p class="header_para_epsd text" style="color:#fff">
                <?php echo $epsd_nom_site ? $epsd_nom_site : 'Nom du site' ;?>
              </p>
          </div>
        <div class="bloc_title">
            <p class="bloc_title_para">
              <?php echo $epsd_titre_site ? $epsd_titre_site : 'Titre de votre espace client' ;?>
            </p>
            <p class="bloc_title_para2">
              <?php echo $epsd_description_site ? $epsd_description_site : 'Description de votre espace client' ;?>
            </p>
        </div>
    </div>        
</div>
<?php
$args = array(
  'post_type' => 'product',
  'posts_per_page' => -1
);
$products = new WP_Query($args);


if ($products->have_posts()) {
  $products->the_post();
  $product_id = get_the_ID();
}
?>







 