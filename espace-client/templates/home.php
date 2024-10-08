<?php
/**
 * Template for displaying the product.
 *
 * @package Ep-sous-domaine-plugin
 * @subpackage Ep-sous-domaine-plugin/espace-client/templates
 */ 
    include(plugin_dir_path(__FILE__) . 'header-custom.php');
    $categories = get_terms( array(
        'taxonomy' => 'product_cat',
        'parent' => 0,
        'hide_empty' => false,
    ) );      
?>    
    <div style="width: 80%;margin: 0 auto;">
        <div class="row m-4 justify-content-md-center">
            <?php if ($epsd_access_commande && $epsd_access_categories):;?>
                <!-- // Afficher la page de commande (priorité) -->
                <?php include(plugin_dir_path(__FILE__) . 'commander.php'); ?>                    
            <?php elseif ($epsd_access_commande):; ?>
                <!-- // Afficher la page de commande -->
                <?php include(plugin_dir_path(__FILE__) . 'commander.php'); ?>    
            <?php elseif ($epsd_access_categories):; ?>
                <!-- // Afficher la page de catégories -->
                <?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :?>
                <!-- // Afficher la page de catégories -->
                <?php
                global $wpdb;
                $table_name = $wpdb->prefix . "product_epsd";
                $resultats = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE fk_id_user = %d", $user_id));
                $terms_unique = array();                
                if($resultats){
                    foreach ($resultats as $result) {
                        $product_id = $result->fk_id_product;
                        $terms = get_the_terms($product_id, 'product_cat');
                        
                        if ($terms && !is_wp_error($terms)) {
                            foreach ($terms as $term) {
                                if ($term->parent != 0 && !in_array($term->term_id, $terms_unique)) {
                                    $terms_unique[] = $term->term_id;
                                    
                                    $parent_category = $term;
                                    $term_id = $parent_category->term_id;
                                    $term_name = $parent_category->name;
                                    $term_slug = $parent_category->slug;
                                    $category_link = home_url('/espace-client/categories/' . $term_slug);
                                    $category_image = get_term_meta($term_id, 'thumbnail_id', true);
                                    $image = '';
                                    
                                    if ($category_image) {
                                        $image = wp_get_attachment_image_src($category_image, 'full')[0];
                                    }
                    ?>
                                    <div class="col-md-3 mb-4">
                                        <div class="category-item mb-4">
                                            <a href="<?php echo esc_url($category_link); ?>" class="text-decoration-none">
                                                <?php if ($image) : ?>
                                                    <img src="<?php echo esc_url($image); ?>" class="img-fluid" alt="<?php echo esc_attr($term_name); ?>">
                                                <?php else : ?>
                                                    <span><?php echo esc_html($term_name); ?></span>
                                                <?php endif; ?>
                                            </a>
                                            <div class="text-left my-4" style="font-family: 'Gotham';font-style: normal;font-weight: 700;font-size: 22px;line-height: 24px;text-transform: uppercase;color: #1A1A1A;">
                                                <h5 class="category-title mt-3 mb-0">
                                                    <a href="<?php echo esc_url($category_link); ?>" class="text-decoration-none text-dark">
                                                        <?php echo esc_html($term_name); ?>
                                                    </a>
                                                </h5>
                                            </div>
                                        </div>
                                        <div style="background-color: <?=$fond_site ? $fond_site : '#ccc' ?>;height:5px;"></div>
                                    </div>
                    <?php
                                }
                            }
                        } else {
                            echo 'Aucune catégorie trouvée.';
                        }
                    }
                    
                }else{?>
                    <h1>Vous avez aucune categorie</h1>
                <?php
                }   ?> 
                <?php endif;?>                 
            <?php else:; ?>
                <!-- // Afficher la page de produit par défaut -->
                <?php include(plugin_dir_path(__FILE__) . 'list-product.php'); ?>                
            <?php endif; ?>            
        </div>    
    </div>
<?php wp_reset_postdata();?>
<?php get_footer(); ?>



