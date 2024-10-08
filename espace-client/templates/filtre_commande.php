
<?php include(plugin_dir_path(__FILE__) . 'header-custom.php'); ?>
<!-- CUSTOMISATION -->
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
<div class="container">
    <div class="row">                          
        <div class="col-md-12 mb-4">
            <div class="mb-4">
                <div class="py-3">
                    <h5 class="mb-0">filtre</h5>
                </div>
                <div class="">
                <!-- <form> -->
                    <!-- 2 column grid layout with text inputs for the first and last names -->
                    <div class="row mb-4">
                        <?php
                        $args = array(
                            'post_type'      => 'product',
                            'posts_per_page' => -1,
                        );

                        $products = get_posts( $args );           
                        ?>
                        <div class="col">
                            <div class="form-outline">
                                <label class="form-label" for="reference-filter">Référence</label>
                                <select class="form-select" id="reference_filter" name="reference-filter" class="test">
                                    <?php foreach($products as $product):;?>
                                        <option value="<?=get_the_title($product->ID);?>">
                                            <?php echo get_the_title($product->ID);?>
                                        </option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-outline">
                                <label class="form-label" for="form6Example2">Format</label>
                                <select class="form-select" id="sel1" name="sellist1">
                                    <option>69X319cm</option>
                                    <option>test</option>
                                </select>                                
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-outline">
                                <label class="form-label" for="form6Example2">type d'impression</label>
                                <select class="form-select" id="sel2" name="sellist1">
                                    <option>RV</option>
                                </select>                                
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-outline">
                                <label class="form-label" for="form6Example2">Pelliculage</label>
                                <select class="form-select" id="sel3" name="sellist1">
                                    <option>sans</option>
                                </select>                                
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-outline">
                                <label class="form-label" for="form6Example2">Quantité</label>
                                <select class="form-select" id="sel4" name="sellist1">
                                    <option>12</option>
                                </select>                                
                            </div>
                        </div>                                                                        
                    </div>
                    <div class="row">
                        <button class="accordion">Section 1</button>
                        <div class="col md-12 panel">
                            <!-- Content accordion -->
                            <div class="row req_ajax">
                            </div>
                            <!-- fin Content accordion -->       
                        </div>                        
                    </div>
                    <hr class="my-4" />
                </div>
            </div>
        </div>                          
    </div>

</div>
<?php get_footer();?>