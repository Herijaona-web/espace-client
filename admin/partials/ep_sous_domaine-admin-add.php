<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       x
 * @since      1.0.0
 *
 * @package    Ep-sous-domaine plugin
 * @subpackage Ep-sous-domaine plugin/admin/partials
 */

  defined( 'ABSPATH' ) || exit;

?>

<!-- Add client form-->
<div class="wrap edit_information">
  <h2>Ajouter une nouvelle clients</h2>
  <form method="post">
    <table class="form-table">
        <tr>
            <th scope="row"><label for="nom">Nom</label></th>
            <td><input type="text" name="nom" class="regular-text" placeholder="Nom" required></td>
        </tr>
        <tr>
            <th scope="row"><label for="email">email</label></th>
            <td><input type="email" name="email" class="regular-text" placeholder="email" required></td>
        </tr>
        <tr>
            <th scope="row"><label for="mot_de_passe">mots de passe</label></th>
            <td><input type="password" name="mot_de_passe" class="regular-text" placeholder="mots de passe" required></td>
        </tr>
        <tr>
            <th scope="row"><label for="adresse">Adresse</label></th>
            <td><input type="text" name="adresse" class="regular-text" placeholder="Adresse" required></td>
        </tr>
    </table>
    <p class="submit">   
        <a href="<?php echo admin_url( "admin.php?page=$page" ); ?>" class="button button-secondary">Retour</a>
        <input type="submit" name="enregistre_client" id="submit" class="button button-primary" value="Enregistrer">
    </p>
  </form>
</div>

