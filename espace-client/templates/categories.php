<?php include(plugin_dir_path(__FILE__) . 'header-custom.php'); ?>

<?php
$current_url = home_url($_SERVER['REQUEST_URI']);
$category_slug = basename($current_url);
$category = get_term_by('slug', $category_slug, 'product_cat');

if ($category) {
    // Obtenir l'ID de la catégorie
    $category_id = $category->term_id;
    // Récupérer les produits associés à la catégorie
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $category_id,
            ),
        ),
    );
    $products = new WP_Query($args);
    if ($products->have_posts()) {
        ?>
<div class="container">
    <div class="row">
        <div class="col-md-8 mt-2">
        <!-- breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb"><a href="<?php echo home_url('espace-client'); ?>">Accueil</a></li>
            <?php 
            if (is_category()):;
            $cat_obj = get_queried_object();
            ?>
            <li class="breadcrumb"><a href="<?php echo get_category_link($cat_obj->term_id); ?>"><?php echo $cat_obj->name; ?></a></li>
            <?php endif; ?>
            <?php 
                if (!is_single()) {
                $chevron_right = '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/></svg>'; 
                $categories = get_the_category();
                $output = '';
                if($categories){
                foreach($categories as $category) {
                    $output .= '<li><a href="'.get_category_link($category->term_id).'">'.$category->name.'</a></li>'.$separator;
                } 
                echo trim($output, $separator); 
            ?>
            <?php }};?>   
            <?php
                if ($category) {
                    echo'<li class="breadcrumb"><a>&nbsp;'.$chevron_right.'&nbsp;</a></li>';
                    echo'<li class="breadcrumb"><a>'.$category->name.'</a></li>';
                }                       
            ?>                        
            </ol>
        </nav>
        </div>
    </div>

    <div class="row  justify-content-center">
        <div class="col-md-8 text-center m-4">
            <h2 class="text-titre-epsd">
                <?php echo $category->name; ?>
            </h2>
            <p class="text-center" style="font-family: 'Gotham Gotham light';font-style: normal;font-weight: 325;font-size: 16px;text-align: center;">
                <?php echo category_description($category_id); ?>
            </p>
        </div>
    </div>

    <div class="row  justify-content-center">
    <?php
                    while ($products->have_posts()) {
                        $products->the_post();
                        $product_id = get_the_ID();
                        $user_id = get_current_user_id();
                        global $wpdb;
                        $global_table_name = $wpdb->prefix . 'product_epsd';
                        $query = $wpdb->prepare(
                            "SELECT * FROM $global_table_name WHERE fk_id_user = %d AND fk_id_product = %d",
                            $user_id,
                            $product_id
                        );
                        $result = $wpdb->get_row($query);
                        if ($result !== null) {
                            $id = $result->id;
                            $image_id = $result->image;
                            $fk_id_product = $result->fk_id_product;
                            $fk_id_user = $result->fk_id_user;
                            $product_slug = get_post_field('post_name', $product_id);
                            $permalink = esc_url(home_url('/espace-client/produit/' . $product_slug));
                            // Get the post thumbnail (product image)
                            $image = get_the_post_thumbnail($fk_id_product, 'full', array('class' => 'img-fluid'));
                            ?>       
        <div class="col-md-3 col-sm-6">
            <a href="<?php echo esc_url($permalink); ?>">
                <div class="category-image">
                    <?php echo $image; ?>
                </div>
            </a>            
            <div class="mt-2">
                <h3 class="fs-6">                
                    <a href="<?php echo $permalink; ?>" class="text-decoration-none" style="font-family: 'Gotham medium';font-style: normal;font-weight: 700;font-size: 16px;line-height: 24px;color: #1E1E1E;">
                        <?php the_title(); ?>
                    </a>                
                </h3>
            </div>
        </div>
        <?php
                        }
                    }
                    ?>       
    </div>  
</div>

        <?php
        wp_reset_postdata();
    } else {
        echo 'Aucun produit trouvé dans cette catégorie.';
    }
}
?>

<?php get_footer(); ?>
