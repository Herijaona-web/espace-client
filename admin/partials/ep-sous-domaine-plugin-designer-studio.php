<?php
$attachment_id = get_post_thumbnail_id($product_id);
$image_url_full = get_post_meta($get_the_ID, 'epsd_image_preview', true);
if (!$image_url_full) {
    $image_data = wp_get_attachment_image_src($attachment_id, 'full');
    if ($image_data) {
        $image_url_full = $image_data[0];
    }
}
?>
<div class="postbox-header sdph">
    <h2 class="hndle ui-sortable-handle">Designer</h2>
    <button type="button" class="handlediv" aria-expanded="false"><span class="screen-reader-text">Ouvrir/fermer la section Galerie produit</span><span class="toggle-indicator" aria-hidden="true"></span></button>
    <div class="line">
        <?php
            woocommerce_wp_checkbox(array(
                'id'    => 'designer_active',
                'name'  => 'designer_active',
                'value' => get_post_meta($get_the_ID, 'designer_active_'.$idUser, true) ?: '',
                'class' => 'my-checkbox',
			));
        ?>
    </div>
</div>
<div class="inside">
    <p class="inside-prg">
        <a style="width: 100%;" class="block_button_epsd_click">
            <input type="hidden" class="image_preview" name="image_preview" data-value="<?= $image_url_full; ?>" value="<?= esc_url($image_url_full); ?>">
            <img class="image-preview" width="266" height="266" src="<?= esc_url($image_url_full); ?>">
        </a>
    </p>
</div>
<div class="block_button_epsd">
    <a target='_blank' href="<?php echo '/app/studio/?customer_id=' . $_GET['customer_id'] . '&idproduct=' . $_GET['idproduct']; ?>"type="submit" class="button button-primary button button-large" id="wp-submit" style="width: 150px;" name="btn_enregistrer_panel">Ouvrir studio
        <span class="icon_right" aria-hidden="true"></span>
    </a>
</div>