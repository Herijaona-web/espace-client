<?php 
// $args = array(
//     'post_type' => 'product',
//     'post_status' => 'publish',
//     'posts_per_page' => -1 // Récupérer tous les produits
// );
// $products = new WP_Query($args);

// if ($products->have_posts()) {
//     echo '<ul>';
//     while ($products->have_posts()) {
//         $products->the_post();
//         global $product;

//         echo '<li>';
//         echo 'ID du produit: ' . get_the_ID() . '<br>';
//         echo 'Nom du produit: ' . get_the_title() . '<br>';
//         echo 'Prix: ' . $product->get_price() . '<br>';
//         echo 'Description: ' . get_the_content() . '<br>';
//         echo 'Image: ' . $product->get_image() . '<br>';
//         echo '</li>';
//     }
//     echo '</ul>';

//     wp_reset_postdata();
// }
global $wpdb;
$table_name = $wpdb->prefix . "product_epsd";
$resultats = $wpdb->get_results("SELECT * FROM $table_name WHERE fk_id_user=$user_id");  
?>

<div class="row justify-content-center">
<?php if($resultats):;?>    
    <?php foreach ($resultats as $resultat):?>
            <?php
            $product = wc_get_product($resultat->fk_id_product);
            $permalink = home_url('/espace-client/produit/' . $product->get_slug());
            ?>
            <div class="product col-lg-3 col-md-4 col-sm-6 mb-4">
                <a href="<?php echo $permalink; ?>">
                    <div class="category-image">
                        <?php
                            if ($product) {
                                $image_url = wp_get_attachment_image_url($resultat->image, 'full');
                                $image_url_full = get_post_meta($resultat->fk_id_product, 'epsd_image_preview', true) ?: $image_url;
                                if ($image_url_full && $image_url_full != 'image-none') {
                                    echo '<img src="' . $image_url_full . '" alt="' . $resultat->image . '" id="imageid_' . $resultat->fk_id_product . '">';
                                }
                            }                      
                        ?>  
                    </div>
                </a>
                <div class="product-details my-4">
                    <h3 style="text-align: left;font-family: 'Gotham medium';font-style: normal;font-weight: 700;font-size: 16px;line-height: 24px;color: #1E1E1E;">
                        <a href="<?php echo $permalink; ?>" style="text-decoration:none;">
                            <?php echo $resultat->nom_product; ?>
                        </a>
                    </h3>
                </div>
            </div>
    <?php endforeach;?>
<?php else:;?>
    <h1>Vous avez aucun produit</h1> 
<?php endif;?>      
</div>