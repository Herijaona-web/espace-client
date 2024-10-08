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
<style>
table{
	
margin: 1rem auto;
text-align: center;
width: 100%;
max-width: 100%;
border-collapse: collapse;
border: 1px solid

}
		
	
thead{

background-color: #8c7c66;
color: white

}
	

th,td{padding:8px 0}

tbody tr:nth-child(even) {
	
background-color: #ddd
	
}
	
@media only screen and (max-width: 800px) {
		
table, 
thead, 
tbody, 
th, 
td, 
tr{ 
	
display: block
	
}
	
thead tr { 
position: absolute;
top: -9999px;
left: -9999px;
}
 
 
 
td { 
	
position: relative;
padding-left: 50%; 
white-space: normal;
text-align: left
	
	}
 
td:before { 
	
position: absolute;
top: 6px;
left: 6px;
width: 45%; 
padding-right: 10px; 
font-weight: bold;
white-space: nowrap;
text-align:left;
content: attr(data-title)
	
	}
}

</style>
<div class="">
        <h1>Details</h1>
            <div style="margin:5px;">
                <p>id:<?php echo $_GET['id'];?></p>
                <p>nom: <?php echo $_GET['nom'];?></p>            
            </div>
            <div style="display:flex;">        
                <div style="margin:5px;flex:8;">
                    <table style="width:100%">
                        <tr>
                            <th>Réference</th>
                            <th>Format</th>
                            <th>Type d'impression</th>
                            <th>Pelliculage</th>
                            <th>Quantité</th>
                            <th>Aperçu</th>
                            <th>Panier</th>
                            <th>options</th>
                        </tr>
                        <tr>
                            <td>Pelliculage</td>
                            <td>Quantité</td>
                            <td>Germany</td>
                            <td>Pelliculage</td>
                            <td>Quantité</td>
                            <td>Pelliculage</td>
                            <td>Quantité</td>
                            <td>
                                <input type="button" value="modifier"/>
                                <input type="button" value="ajouter"/>
                                <input type="button" value="enlever"/>
                            </td>                                                        
                        </tr>
                        <tr>
                            <td>Centro comercial Moctezuma</td>
                            <td>Francisco Chang</td>
                            <td>Mexico</td>
                            <td>Pelliculage</td>
                            <td>Quantité</td>
                            <td>Pelliculage</td>
                            <td>Quantité</td>
                            <td>
                                <input type="button" value="modifier"/>
                                <input type="button" value="ajouter"/>
                                <input type="button" value="enlever"/>
                            </td>                                
                        </tr>
                    </table>
                </div>
                <div style="margin:10px;flex:1;">
                    <form action="/action_page.php">
                        <input type="checkbox" id="vehicle1" name="vehicle1" value="Accueil">
                        <label for="vehicle1"> Accueil</label><br>
                        <input type="checkbox" id="vehicle2" name="vehicle2" value="listes produits">
                        <label for="vehicle2">listes produits</label><br>
                        <input type="checkbox" id="vehicle3" name="vehicle3" value="Categories produits">
                        <label for="vehicle3"> Categories produits</label><br><br>
                        <input type="submit" value="Submit">
                    </form>
                </div>
            </div>                
    </div>