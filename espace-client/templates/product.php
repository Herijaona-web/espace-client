<?php
/**
 * Template for displaying the product.
 *
 * @package Ep-sous-domaine-plugin
 * @subpackage Ep-sous-domaine-plugin/studio
 */
include(plugin_dir_path(__FILE__) . 'header-custom.php');
$current_url = home_url($_SERVER['REQUEST_URI']);
$product_slug = basename($current_url);

$args = array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'name' => $product_slug // Utilisez le slug du produit comme paramètre de recherche
);

$products = new WP_Query($args);

if ($products->have_posts()) {
    $products->the_post();
    $product_id = get_the_ID();
}

$attachment_id = get_post_thumbnail_id($product_id);
$image_url_full = get_post_meta($product_id, 'epsd_image_preview', true);
if (!$image_url_full) {
    $image_data = wp_get_attachment_image_src($attachment_id, 'full');
    if ($image_data) {
        $image_url_full = $image_data[0];
    }
}

?>
<div class="container">
    <div class="row mt-2">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo home_url('espace-client'); ?>">Accueil</a></li>
                <?php 
                    if (is_category()):;
                    $cat_obj = get_queried_object();
                ?>
                    <li class="breadcrumb-item"><a href="<?php echo get_category_link($cat_obj->term_id); ?>"><?php echo $cat_obj->name; ?></a></li>
                <?php endif; ?>
                <?php 
                    $chevron_right = '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/></svg>';
                    $product_categories = wp_get_post_terms($product->id, 'product_cat');
                    $separator = ' > ';
                    $output = '';
                    $output .= '<li>&nbsp;' . $chevron_right . '&nbsp;</li>';
                    $domain = home_url('espace-client');
                    if($product_categories){
                        $output .= '<li><a href="'.$domain.'/categories/'.$product_categories[0]->slug.'">'.$product_categories[0]->name.'</a></li>'; 
                    }
                    $output .= '<li>&nbsp;' . $chevron_right . '&nbsp;</li>';
                    $output .= '<li><a>'.$product->get_name().'</a></li>';
                    echo $output;
                ?> 
                <?php ;?>        
            </ol>
        </nav>
    </div>
    <h2 class="text-titre-epsd titre_produit_epsd fw-bold"><?php echo $product->get_name();?></h2>
    <div class="row justify-content-center m-4 fiche_produit_front">
        <div class="col-md-1 col-thumb">
            <div class="thumbnail-container justify-content-center align-items-center">
                <!-- Afficher les miniatures d'images -->
                <?php $gallery_image_ids = $product->get_gallery_image_ids();?>
                <div class="thumbnail-item woocommerce-product-gallery-thumbnail">
                    <img src="<?=esc_url($image_url_full);?>" alt="<?=esc_attr($product->get_name());?>" class="thumbnail-container-img img-fluid">
                </div>
                <?php foreach ($gallery_image_ids as $image_id):;?>
                <div class="thumbnail-item woocommerce-product-gallery-thumbnail">
                    <?php $image_url = wp_get_attachment_image_url($image_id, 'full');?>
                    <img src="<?=esc_url($image_url);?>" alt="<?=esc_attr($product->get_name());?>" class="thumbnail-container-img img-fluid">
                </div>
                <?php endforeach;?>
            </div>
        </div>    
        <div class="col-md-11 col-img">
            <!-- Afficher l'image principale -->
            <div class="image_product">
                <?php if ($image_url_full): ?>
                    <img class="img img-responsive" src="<?=esc_url($image_url_full);?>" alt="<?=esc_attr($product->get_name());?>" style="width: 100%; height: 100%; object-fit: cover;">                    
                <?php else : ?>
                    <img class="img img-responsive" style="width: 100%; height: 100%; object-fit: cover;" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/thumbnail-default.jpg' ; ?>" class="logo" alt="thumbnail-default" style="max-height: 56px;">    
                <?php endif;?>    
            </div>
        </div>
    </div>
    <div id="woocommerce_single_product_summary">
        <?php do_action('woocommerce_single_product_summary');?>
    </div>    
</div>


<?php get_footer();?>
