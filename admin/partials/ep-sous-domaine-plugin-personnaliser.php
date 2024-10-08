<div class="wrap_ep">
    <div style="display:flex;align-items:center">
        <h1 class="wp-heading-inline" style="margin-right: 15px;">Personnaliser</h1>
        <a href="<?php echo admin_url('admin.php?page=ep-sous-domaine-plugin'); ?>" class="button button-secondary">&#8249; Retour</a>
    </div>            
    <div class="user-info">
    <?php
        $epsd_id = isset($_GET['customer_id']) ? intval($_GET['customer_id']) : 0;

        global $wpdb;
        $table_name = $wpdb->prefix . 'clients_epsd';
        $query = $wpdb->prepare("SELECT * FROM $table_name WHERE fk_id_customer = %d", $epsd_id);
        $user_info = $wpdb->get_row($query, ARRAY_A);
        if ($user_info) {
            $fk_id_customer = $user_info['fk_id_customer'];
            // Récupération des informations de base de l'utilisateur
            $user = get_user_by('ID', $fk_id_customer);

            if ($user) {
                $nom = $user->display_name ? $user->display_name : 'N/A';
                $email = $user->user_email;
                $status = $user_info['status'] == 0 ? 'Désactivé' : 'Activé';
                ?>
                <div style="background-color: #f0f0f0;padding: 15px;">
                    <label style="font-weight:bold;font-size: 14px;color: black;text-transform:uppercase;height:auto;width:100%;">Informations Clients</label>
                    <div class="user-details">
                        <div class="user-field">
                            <span class="label">Nom :</span>
                            <span class="value"><?php echo $nom; ?></span>
                        </div>
                        <div class="user-field">
                            <span class="label">Email :</span>
                            <span class="value"><?php echo $email; ?></span>
                        </div>
                        <div class="user-field">
                            <span class="label">Statut :</span>
                            <span class="value"><?php echo $status; ?></span>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                echo 'Les informations de l\'utilisateur ne sont pas disponibles.';
            }
        } else {
            echo 'Les informations de l\'utilisateur ne sont pas disponibles.';
        }        
        
        if (isset($_POST['submit_test'])) {
            // Obtenez l'ID de l'utilisateur et l'ID de l'article en cours
            $user_id = $epsd_id;
            $post_id = get_the_ID();
            $upload_dir = wp_upload_dir();
            $plugin_dir = $upload_dir['basedir'] . '/Ep-sous-domaine';
            
            if (!file_exists($plugin_dir)) {
                wp_mkdir_p($plugin_dir);
            }

            // Fonction pour mettre à jour les métadonnées utilisateur en effectuant une validation
            function update_user_meta_sanitize($user_id, $meta_key, $value) {
                update_user_meta($user_id, $meta_key, sanitize_text_field($value));
            }

            // Fonction pour déplacer le fichier téléchargé
            function move_uploaded_file_to_dir($file_key, $target_dir) {
                if (!empty($_FILES[$file_key]['tmp_name'])) {
                    $file = $_FILES[$file_key];
                    $filename = uniqid() . '-' . $file['name'];
                    $target_path = $target_dir . '/' . $filename;
                    if (move_uploaded_file($file['tmp_name'], $target_path)) {
                        return $target_path;
                    }
                }
                return false;
            }
            
            // Mettre à jour les métadonnées utilisateur
            update_user_meta_sanitize($user_id, 'epsd_nom_site', $_POST['epsd_nom_site']);
            update_user_meta_sanitize($user_id, 'epsd_titre_site', $_POST['epsd_titre_site']);
            update_user_meta_sanitize($user_id, 'epsd_description_site', $_POST['epsd_description_site']);
            update_user_meta_sanitize($user_id, 'epsd_fond_site', $_POST['epsd_fond_site']);
            update_user_meta_sanitize($user_id, 'access_categories', isset($_POST['access_categories']));
            update_user_meta_sanitize($user_id, 'access_commande', isset($_POST['access_commande']));                          
            update_user_meta_sanitize( $user_id, 'epsd_category_product',  isset($_POST['epsd_category_product']));
            
            // Déplacer les fichiers téléchargés vers le répertoire Ep-sous-domaine et mettre à jour les métadonnées de l'utilisateur

            $logo_path = move_uploaded_file_to_dir('logo', $plugin_dir);
            if ($logo_path) {
                $upload_url = $upload_dir['baseurl'] . '/Ep-sous-domaine/' . basename($logo_path);
                update_user_meta($user_id, 'logo_path', $upload_url);
            }

            $banniere_path = move_uploaded_file_to_dir('banniere', $plugin_dir);
            if ($banniere_path) {
                $upload_url_ban = $upload_dir['baseurl'] . '/Ep-sous-domaine/' . basename($banniere_path);
                update_user_meta($user_id, 'banniere_path', $upload_url_ban);
            }
        
            
        }
        // Les variables $user_id, $epsd_nom_site, $epsd_fond_site, $epsd_logo_site, $epsd_banniere_site, $epsd_access_categories, $epsd_access_commande seront
        $user_id = $epsd_id;
        $epsd_nom_site = get_user_meta($user_id, 'epsd_nom_site', true);
        $epsd_titre_site = get_user_meta($user_id, 'epsd_titre_site', true);
        $epsd_description_site = get_user_meta($user_id, 'epsd_description_site', true);
        $epsd_fond_site = get_user_meta($user_id, 'epsd_fond_site', true);
        $epsd_logo_site = get_user_meta($user_id, 'logo_path', true);       
        $epsd_banniere_site = get_user_meta($user_id, 'banniere_path', true);
        $epsd_access_categories = get_user_meta($user_id, 'access_categories', true);        
        $epsd_id_categories=get_user_meta($user_id,'epsd_category_product',true);
        $epsd_access_commande = get_user_meta($user_id, 'access_commande', true);

?>
        <div class="user-info-content">
            <form class="m-auto" method="post" enctype="multipart/form-data">
                <table class="form-table">
                    <tr>
                        <th><label for="epsd_nom_site">Nom de l'espace client :</label></th>
                        <td><input type="text" id="epsd_nom_site" class="epsd_nom_site" name="epsd_nom_site" value="<?php echo $epsd_nom_site;?>"></td>                    
                    </tr>
                    <tr>
                        <th><label for="epsd_titre_site">Titre de l'espace client :</label></th>
                        <td><input type="text" id="epsd_titre_site" class="epsd_titre_site" name="epsd_titre_site" value="<?php echo $epsd_titre_site;?>"></td>                    
                    </tr>
                    <tr>
                        <th><label for="epsd_description_site">Description de l'espace client :</label></th>
                        <td><input type="text" id="epsd_description_site" class="epsd_description_site" name="epsd_description_site" value="<?php echo $epsd_description_site;?>"></td>                    
                    </tr>
                    <tr>
                        <th><label for="epsd_fond_site">Couleur de l'arrière-plan :</label></th>
                        <td><input type="color" id="epsd_fond_site" name="epsd_fond_site" class="epsd_fond_site" value="<?php echo $epsd_fond_site;?>"></td>
                    </tr>                                
                    <tr>
                        <th rowspan="2"><label for="epsd_banniere_site" colspan="2">Bannière :</label></th>
                        <td>
                            <input type="file" id="epsd_banniere_site" name="banniere" class="epsd_banniere_site" value="<?php echo $epsd_banniere_site;?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <?php
                            if ($epsd_banniere_site) {
                                echo '<img src="' . $epsd_banniere_site . '" alt="Bannière actuelle du site" style="max-width: 150px;">';
                            }
                            ?>                            
                        </td>
                    </tr>                      
                    <tr>
                        <th rowspan="2"><label for="epsd_logo_site" colspan="2">Logo :</label></th>
                        <td>
                            <input type="file" id="epsd_logo_site" name="logo" class="epsd_logo_site" value="<?php echo $epsd_logo_site;?>">                                           
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php
                            if ($epsd_logo_site) {
                                echo '<img src="' . $epsd_logo_site . '" alt="Logo actuel du site" style="max-width: 150px;">';
                            }
                            ?> 
                        </td>
                    </tr>
                    <tr>
                        <th><label>Accès à la page "Commande" :</label></th>
                        <td>
                            <input type="checkbox" id="access_commande" name="access_commande" <?php if ($epsd_access_commande) echo 'checked'; ?>>
                            <label for="access_commande">Autoriser l'accès à la page "Commande"</label>
                        </td>
                    </tr>                                        
                    <tr>
                        <th><label>Accès à la page catégorie :</label></th>
                        <td>
                            <input type="checkbox" id="access_categories" name="access_categories" <?php if ($epsd_access_categories) echo 'checked'; ?>>
                            <label for="access_categories">Autoriser l'accès à la page catégorie</label>
                        </td>
                        
                    </tr>
                </table>
                <div class="submit-button">
                    <input type="submit" name="submit_test" class="button-secondary" value="Mettre à jour" style="background-color:green;color:white;border:none;width:150px">
                </div>
            </form>
        </div>
    </div>
    <div class="user-info">
        <?php
        if (isset($_GET['product_deleted']) && $_GET['product_deleted'] == 1) {
            add_settings_error(
                'product_deleted',
                'product_deleted',
                'Le produit a été supprimé avec succès.',
                'updated'
            );
        }
        // Afficher la notification
        settings_errors('product_deleted');
        ?>
        <div class="wrap" style="margin-bottom: -32px;">
            <form class="" method="POST" style="display:flex;">
                <div class="column" style="text-align: center;cursor: pointer;color: white;margin-right: 5px;">
                    <div class="form-group">
                        <div class="form_search" style="width:100%">
                            <select id="resultat" name="resultat" class="select2_product" style="padding: 10px;font-size: 16px;border-radius: 4px;width: 400px!important;" >
                                <option value="">Sélectionnez un produit</option>
                            </select>
                            <div class="recherche"></div>
                        </div> 
                    </div>
                    <input type="hidden" value="<?php echo $_GET['customer_id'];?>" name="customer_id">                                                                                          
                </div>
                <div class="column" style="text-align: center;cursor: pointer;color: white;">
                    <button class="button button-primary button-large ajout_produit_epsd" name="submit">Ajouter le produit</button>
                </div>                
            </form> 
        </div>
        <?php $product_list_table->display(); ?>   
    </div>
</div>    
