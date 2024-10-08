
<div>
    <?php
        global $wpdb;
        $table_name = $wpdb->prefix . "product_epsd";
        $resultats = $wpdb->get_results("SELECT * FROM $table_name WHERE fk_id_user=$user_id");
        if ($resultats) {
            if (isset($_GET['add'])) {            
                $product_id = $_POST['add-to-cart'];
                $product_title = get_the_title($product_id);
                $notification = sprintf(
                    '<div class="woocommerce-message notification_panier" role="alert">%s %s<a href="%s" class="button wc-forward panier_epsd">%s</a><span class="close-notification">&times;</span></div>',
                    __('Le produit', 'woocommerce'),
                    '"' . $product_title . '" a été bien ajouté',
                    wc_get_cart_url(),
                    __('Voir le panier', 'woocommerce')
                );
                echo $notification;
            }
        };                
    ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover text-center bg-light price_product" id="example">
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
                <?php foreach ($resultats as $resultat):;?>  
                <?php 
                    $product = wc_get_product($resultat->fk_id_product);
                ?>
                <tr id="<?php echo $resultat->fk_id_product ?>">
                    <td><?php echo $product->get_sku() ?  $product->get_sku() : $product->get_name(); ?></td>
                    <td>
                        <?php 
                            $etapes_print_default_value = get_post_meta( $product->get_id(), 'epsd_format_default_value', true );
                            do_action('sous_domaine_display_ep_val', 'etapes_print_format', $etapes_print_default_value); 
                        ?></td>
                    <td>
                        <?php 
                            $etapes_print_default_value = get_post_meta( $product->get_id(), 'epsd_colors_default_value', true );
                            do_action('sous_domaine_display_ep_val', 'etapes_print_colors', $etapes_print_default_value); 
                        ?>                      
                    </td>
                    <td>
                        <?php 
                            $etapes_print_default_value = get_post_meta( $product->get_id(), 'epsd_refinement_default_value', true );
                            do_action('sous_domaine_display_ep_val', 'etapes_print_refinement', $etapes_print_default_value); 
                        ?>
                    </td>
                    <td>
                        <?php 
                            $quantity_default=get_post_meta( $product->get_id(), 'epsd_quantity_default_quantity', true); 
                            $price_array = get_post_meta( $product->get_id(), 'epsd_quantity_price_array', true);
                            $price_array = explode(',', $price_array);
                        ?>                   
                        <select name="mySelect" class="quantity_price">
                            <?php foreach ($price_array as $option): ?>
                                <option value="<?php echo $option ? $option : 250 ; ?>" <?php echo ($option == $quantity_default) ? 'selected' : ''; ?>><?php echo $option ? $option : 250; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                        <form action="" method="post" enctype="multipart/form-data">
                        <td>
                            <div class="w-100 m-auto">
                                <div class="image-wrapper">
                                    <?php
                                    if ($product) {
                                        $image_url = wp_get_attachment_image_url($resultat->image, 'full');
                                        $image_url_full = get_post_meta($resultat->fk_id_product, 'epsd_image_preview', true) ?: $image_url;
                                        if ($image_url_full && $image_url_full != 'image-none') {
                                            echo '<img  class="attachment-thumbnail size-thumbnail lazyautosizes lazyloaded img-fluid" src="' . $image_url_full . '" alt="' . $resultat->image . '" id="imageid_' . $resultat->fk_id_product . '" style="width: 100%;
                                            height: 100%;">';

                                            // Vérifier si une image a été téléchargée                                        
                                            $image_telecharge = get_post_meta($resultat->fk_id_product, 'epsd_file_type', true);                                       
                                            if ($image_telecharge) {
                                                echo '<div class="overlay">';
                                                echo '<img src="' . plugin_dir_url(dirname(__FILE__)) . 'images/telechargement.png" alt="Logo Téléchargement" class="img-fluid">';
                                                echo '<span>Télécharger votre fichier</span>';
                                                echo '</div>';
                                            }
                                        }
                                    }
                                    ?>
                                    <input type="file" id="fileInput" name="etapes_print_file[]" class="hidden-file-input">
                                </div>

                            </div>
                        </td> 
                        <td>
                            <input type="hidden" name="etapes_print_quantity" class="quantity_values">
                            <?php 
                                $options =  apply_filters('get_options', array());
                                foreach ($options as $key => $option) {
                                    $values = get_post_meta($resultat->fk_id_product  , 'epsd_'. $option .'_default_value', true );
                                    $checked = get_post_meta($resultat->fk_id_product  , 'epsd_'. $option, true );
                                    if($checked){
                                        if($option == "cover"){
                                            $values = get_post_meta($resultat->fk_id_product  , 'epsd_'. $option .'_value', true );
                                            if (get_post_meta( $resultat->fk_id_product , 'epsd_cover', true )) {
                                                $cover_code = get_post_meta( $resultat->fk_id_product, 'epsd_cover_value', true );
                                                if ($cover_code) {
                                                    $etapes_print_cover = apply_filters('get_cover_by_code', $cover_code);
                                                    foreach (['format', 'pages', 'paper', 'refinement'] as $cover_option) {
                                                        $etape_print_default_value = $etapes_print_cover['default_'.$cover_option];
                                                        if ($cover_option == 'pages') {
                                                            $etape_print_default_value = 'page_4';
                                                        }
                                                        ?>
                                                            <input type="hidden" data="<?php echo $resultat->fk_id_product; ?>" 
                                                            name="etapes_print_cover_<?php echo $cover_option ?>" class="option-cover-<?php echo $cover_option ?>" 
                                                            value="<?php echo $etape_print_default_value; ?>">
                                                        <?php
                                                    }
                                                }
                                            }
                                        }
                                        if($values){ ?>
                                            <input type="hidden" data="<?php echo $resultat->fk_id_product; ?>" 
                                            name="etapes_print_<?php echo $option ?>" class="option-<?php echo $option ?>" 
                                            value="<?php echo $values; ?>">
                                        <?php }
                                    }
                                }
                            ?>
                            <input type="hidden" name="priceaction" class="price_epsd">
                            <div style="width:300px;">
                                <?php
                                $designer_active = get_post_meta($resultat->fk_id_product, 'designer_active_'.$resultat->fk_id_user, true);
                                $text = $designer_active ? 'Personnaliser' : 'Ajouter au panier';
                                $disabled = $image_telecharge ? 'disabled' : ''; // Désactive le bouton si une image a été téléchargée
                                ?>
                                <button <?php echo $disabled; ?> type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="button btn btn_epsd text-white add-to-cart-button" style="background-color:<?php echo $epsd_fond_site;?>" id="addToCartButton"><?= $text ?></button>
                            </div>
                        </td>
                    </form>
                </tr>
                <?php endforeach;?>    
            </tbody>
        </table>
    </div>
</div>
<?php get_footer();?>
