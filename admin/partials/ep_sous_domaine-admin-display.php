<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       x
 * @since      4.0.0
 *
 * @package    Etapes_Print
 * @subpackage Etapes_Print/admin/partials
 */

  defined( 'ABSPATH' ) || exit;
?>
<!-- Market management display -->
<?php //var_dump($page);?>




<div class="wrap marketplace-list">
  <h2>Gestion des clients</h2>
  <?php
    if (isset($_GET['insert_success']) && $_GET['insert_success'] == 'true') {
        echo '<div class="notice notice-success"><p>Insertion réussie.</p></div>';
    }
  ?>
  
  <!-- <a href="<?php //echo admin_url( "admin.php?page=$page&action=add" ); ?>" class="button button-primary gestion_print_right" id="add_new_customer" >Nouveau client</a> -->
  <!-- Marketplaces List -->
    <div class="mt-50p">    
    <div class="marketplace-item">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Autres</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_customers as $customer) : ?>
                    <?php
                    $user = get_user_by('id', $customer->fk_id_customer);
                    if ($user) {
                        $nom = $user->display_name;
                        $email = $user->user_email;
                    } else {
                        $nom = 'N/A';
                        $email = 'N/A';
                    }
                    ?>
                    <tr>
                        <td data-title="Nom"><a href="<?php echo ("admin.php?page=ep-sous-domaine-plugin-details-produits&nom=$nom&id=$customer->fk_id_customer"); ?>"><?php echo $nom; ?></a></td>
                        <td data-title="Email"><?php echo $email; ?></td>
                        <td><input type="button" value="modifier"><input type="button" value="effacer"></td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>

    </div>  
    
  </div>
</div>