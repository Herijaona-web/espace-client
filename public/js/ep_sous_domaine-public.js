
(function( $ ) {
	'use strict';
	$(document).on({
		dragover: function() {
			return false;
		},
		drop: function() {
			return false;
		}
	});

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	// Ajoute un événement de clic sur le bouton X
	$( document ).ready(function(){
		let table=$('#example');
		if(table.length){
			table.DataTable({
				language: {
					url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json',
				},
			});
		};
		$(".accordion").click(function() {
			$(this).toggleClass("active");
			const panel = $(this).next();
			if (panel.is(":visible")) {
				panel.hide();
			} else {
				panel.show();
			}
		});
		$('#reference_filter').on('change', function() {
			const selectedValue = $(this).val(); // Obtenez la valeur sélectionnée	
			$.ajax({
				url: ajax_object.ajax_url,
				method: 'POST',
				data: {
					action: 'filter_products', // Action WordPress personnalisée pour gérer la requête
					reference: selectedValue
				},
				success: function(response) {
					// Traitez la réponse de la requête AJAX
					console.log(response); 
					$('.req_ajax').html(response);
				},
				error: function(xhr, status, error) {
					console.error(error);
				}
			});
		});		
		$('#body_epsd .woocommerce-product-gallery-thumbnail .img-fluid').hover(function(){
			const attr_img = $(this).attr('src');
			$('.image_product img').attr('src', attr_img);
		});
		// Fermeture de la notification
		$('.close-notification').click(function() {
			$(this).closest('.woocommerce-message').fadeOut('slow');
		});		
	})

	//code to remove after
	$(document).ready(function(){
		const btn_cart_text = $('#body_epsd .single_add_to_cart_button').text();
		if(btn_cart_text.includes('Personnaliser')){
			$('#body_epsd .single_add_to_cart_button').addClass('pointer_none');
		}
	});

	$(document).ready(function() {
		$('.overlay').click(function() {
			var imageWrapper = $(this).closest('.image-wrapper');
			var fileInput = imageWrapper.find('.hidden-file-input');
			fileInput.click();
		});	
		// Vérifier si le fileInput a une valeur
		$('.hidden-file-input').change(function() {
			var fileInput = $(this);
			var imageWrapper = fileInput.closest('.image-wrapper');
			var addToCartButton = imageWrapper.closest('tr').find('.button');
			var fileName = fileInput.val().split('\\').pop();
	
			if (fileInput.val()) {
				var reader = new FileReader();
				reader.onload = function(e) {
					var fileType = fileInput[0].files[0].type;
					if (fileType === 'application/pdf') {
						imageWrapper.find('.overlay img').attr('src', '/wp-content/plugins/ep-sous-domaine-plugin/espace-client/images/fichier.png');
					} else {
						imageWrapper.find('.overlay img').attr('src', e.target.result);
					}
				};
				reader.readAsDataURL(fileInput[0].files[0]);
	
				addToCartButton.prop('disabled', false);
				imageWrapper.find('.overlay span').text(fileName);
			} else {
				addToCartButton.prop('disabled', true);
				imageWrapper.find('.overlay img').attr('src', '');
				imageWrapper.find('.overlay span').text('Télécharger votre fichier');
			}
		});
	});	
})( jQuery );
