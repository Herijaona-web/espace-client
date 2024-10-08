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
    <h2 class="hndle ui-sortable-handle">Image produit</h2>
</div>
<div class="inside">
    <p class="inside-prg">
        <a style="width: 100%;" class="block_button_epsd_click">
            <input type="hidden" class="image_preview" name="image_preview" data-value="<?= $image_url_full; ?>" value="<?= esc_url($image_url_full); ?>">
            <img class="image-preview" width="266" height="266" src="<?= esc_url($image_url_full); ?>">
        </a>
    </p>
    <p class="" id="set-post-thumbnail-desc-preview">Cliquez sur l’image pour la modifier ou la mettre à jour.</p>
    <p class="hide-if-no-js"><a id="remove-post-thumbnail-preview">Retirer l’image produit</a></p>
</div>
<div class="block_button_epsd">
    <button type="submit" class="button button-primary button button-large" style="width: 150px;" name="btn_enregistrer_panel">Mettre à jour</button>
</div>