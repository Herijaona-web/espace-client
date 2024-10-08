<?php include(plugin_dir_path(__FILE__) . 'header-custom.php'); ?>
<?php
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
?>
<?php
// Get customer's orders
function retrieve_customer_orders($customer_id) {
    $args = array(
        'customer' => $customer_id,
        'status'   => array('completed', 'processing', 'on-hold'), // Include specific order statuses you want to retrieve
    );

    $customer_orders = wc_get_orders($args);

    return $customer_orders;
}

// Example usage
$customer_id = 2811; // Replace with the actual customer ID
$orders = retrieve_customer_orders($customer_id);
?>

<table class="table table-bordered table-hover text-center bg-light">
    <thead>
        <tr>
            <!-- <th>Order ID</th> -->
            <!-- <th>Order Number</th> -->
            <!-- <th>Order Status</th> -->
            <th>Reference</th>
            <th>Total</th>
            <th>Quantity</th>
            <th>Format</th>
            <th>Impression</th>
            <th>Pelliculage</th>
            <th>Image</th>
            <th>Panier</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order) : ?>
            <?php foreach ($order->get_items() as $item_id => $item) : ?>
                <?php $product = $item->get_product(); ?>
                <?php $reference = $product ? $product->get_sku() : ''; ?>
                <?php $meta_data = $item->get_meta_data(); ?>
                <tr>
                    <!-- <td><?php //echo $order->get_id(); ?></td> -->
                    <!-- <td><?php //echo $order->get_order_number(); ?></td> -->
                    <!-- <td><?php //echo $order->get_status(); ?></td> -->
                    <td><?php echo $reference ? $reference : $item->get_name(); ?></td>
                    <td><?php echo $item->get_total(); ?></td>
                    <td><?php echo $item->get_quantity(); ?></td>
                    <td>
                        <?php foreach ($meta_data as $meta) : ?>
                            <?php if ($meta->key == 'Format') echo $meta->value; ?>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?php foreach ($meta_data as $meta) : ?>
                            <?php if ($meta->key == 'Impression') echo $meta->value; ?>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?php foreach ($meta_data as $meta) : ?>
                            <?php if ($meta->key == 'Pelliculage') echo $meta->value; ?>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?php echo $product ? $product->get_image('thumbnail') : ''; ?>
                    </td>
                    <td>
                        <form action="" method="post">
                            <div style="width: 300px;">
                                <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="button btn btn_epsd text-white" style="background-color:<?php echo $epsd_fond_site;?>">Ajouter au panier</button>
                            </div>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
</table>
<?php get_footer();?>