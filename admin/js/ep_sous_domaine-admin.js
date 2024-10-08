(function ($) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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

	$(document).ready(function ($) {
		// Initialiser Select2 sur l'élément select
		$('.select2').select2({
			width: '100%',
			minimumInputLength: 3,
			placeholder: 'Rechercher un client',
			ajax: {
				url: ajax_object.ajax_url,
				method: 'POST',
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						action: 'woocommerce_search_customers',
						search_text: params.term
					};
				},
				processResults: function (data) {
					// Formatage des résultats pour Select2
					let formattedResults = [];
					$.each(data, function (index, item) {
						let result = {
							id: item.id,
							text: item.text + ' (' + item.email + ')'
						};
						formattedResults.push(result);
					});

					return {
						results: formattedResults
					};
				}
			}
		}).on('select2:select', function (e) {
			let data = e.params.data;
			let nom = data.text;
			let email = data.email;

			let affichage = nom + ' - ' + email;

			$('.recherche').text(affichage);
		});


		$('.create_espace_client').on('click', function (e) {
			e.preventDefault();
			let clientID = $('#resultat').val();
			if (clientID) {
				// Effectuer une requête AJAX pour insérer l'ID du client dans la table "clients_epsd"
				$.ajax({
					url: ajax_object.ajax_url,
					method: 'POST',
					data: {
						action: 'insert_client_epsd',
						client_id: clientID
					},
					success: function (response) {
						// Gérer la réponse AJAX réussie
						if (response.success) {
							alert(response.message); // Afficher une alerte avec le message de succès
							location.reload();
						} else {
							alert(response.message); // Afficher une alerte avec le message d'erreur
						}
					},
					error: function (xhr, status, error) {
						console.error(error);
					}
				});
			}
		});

		// desactiver/activer epsd liste client
		$('.action_epsd').click(function (e) {
			e.preventDefault();
			let epsdId = $(this).data('epsd-id');
			let status = $(this).data('status');
			let confirmation;
			if (status == 1) {
				confirmation = confirm("Vous êtes sur le point de désactiver l'espace client de cet utilisateur, êtes-vous sûr ?");
			} else {
				confirmation = confirm("Voulez-vous activer l'espace client de cet utilisateur ?");
			}
			if (confirmation) {
				let url = $(this).attr('href');
				// Requête AJAX pour mettre à jour le statut
				$.ajax({
					url: ajax_object.ajax_url, // L'URL du point de terminaison AJAX de WordPress
					method: 'POST',
					data: {
						action: 'update_status',
						epsd_id: epsdId,
						status: status
					},
					success: function (response) {
						// Réponse de la requête AJAX
						if (response === 'success') {
							window.location.reload(); // Recharger la page pour refléter les modifications
						} else {
							alert(response);
						}
					},
					error: function (xhr, status, error) {
						// Gérer les erreurs de la requête AJAX ici
						alert('Une erreur s\'est produite lors de la mise à jour du statut.');
						console.log(xhr.responseText);
					}
				});
			}
		});


		// Chercher product
		$('.select2_product').select2({
			minimumInputLength: 3, // Nombre minimum de caractères pour déclencher la recherche
			placeholder: 'Rechercher un produit',
			ajax: {
				url: ajax_object.ajax_url,
				type: 'POST',
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						action: 'get_product_data',
						search_text: params.term // Utilisez le terme de recherche saisi par l'utilisateur
					};
				},
				processResults: function (data) {
					return {
						results: data
					};
				}
			}
		}).on('select2:select', function (e) {
			let productId = e.params.data.id;
			let productName = e.params.data.text; // Récupérez le nom du produit sélectionné
			let affichage = productId + ' - ' + productName;
			//je veut creer un input de type hidden pur stocker la productId
			$('.recherche').text(affichage);
		}).on('select2:open', function (e) {
			// Réinitialiser l'état du bouton d'ajout
			$('.ajout_produit_epsd').prop('disabled', true);
		}).on('select2:closing', function (e) {
			let resultsMessage = $('.select2-results__message');
			let addButton = $('.ajout_produit_epsd');
			
			if (resultsMessage.length > 0 && resultsMessage.text() === 'No results found') {
				// Aucun résultat trouvé, désactiver le bouton d'ajout
				addButton.prop('disabled', true);
			} else {
				// Activer le bouton d'ajout
				addButton.prop('disabled', false);
			}
		});


		//Ajouter dans la tableau liste  product
		$('.ajout_produit_epsd').on('click', function (e) {
			e.preventDefault();
			// Récupérer l'ID du produit sélectionne
			let id_product = $('#resultat').val();
			let customer_id = $('input[name="customer_id"]').val();
			let addButton = $(this);
			if (id_product) {
				// Désactiver le bouton et changer son nom
				addButton.prop('disabled', true).text('Ajout en cours...');
				$.ajax({
					url: ajax_object.ajax_url,
					method: 'POST',
					data: {
						action: 'insert_product_epsd',
						id_product: id_product,
						customer_id: customer_id 
					},
					success: function (response) {
						// Gérer la réponse AJAX réussie
						if (response.success) {
							alert(response.message);
							location.reload();
						} else {
							alert(response.message);
						}
					},
					error: function (xhr, status, error) {						
						addButton.prop('disabled', true).addClass('btn-disabled').text('Erreur');
						console.error(error);
					},
					complete: function () {
						// Réactiver le bouton et restaurer son nom
						addButton.prop('disabled', false).text('Ajouter le produit');
					}					
				});
			}
		});

		const baseUrl = window.location.protocol + "//" + window.location.host;
		$('#epsd_produit').css({
			'opacity' : '0.5',
			'pointer-events' : 'none'
		});
		$('#etapes_print_client_category').prop('disabled', true);
		$('#epsd_produit').prop('disabled', true);
		$.get(`${baseUrl}/wp-json/etapes-print/api/categories`, function (products, status) {
			const selectElement = $('#etapes_print_client_category');
			$('#epsd_produit').css({
				'opacity' : '1',
				'pointer-events' : 'inherit'
			});
			$.each(products.categories, function (categoryId, categoryName) {				
				const option = $('<option></option>').val(categoryId).text(categoryName);
				option.prop('disabled', true).css('color', 'gray');
				selectElement.append(option);
			});
			$('#etapes_print_client_category').change(function () {
				let id = $(this).val();
				$('#epsd_produit').css({
					'opacity' : '0.5',
					'pointer-events' : 'none'
				});
				$.get(`${baseUrl}/wp-json/etapes-print/api/categories/${id}/products`, function (products) {
					$('#epsd_produit').css({
						'opacity' : '1',
						'pointer-events' : 'inherit'
					});
					const productEl = $('#epsd_produit');
					productEl.empty();
					products.forEach(product => {
						productEl.append(`<option value="${product['id']}">${product['name']}</option>`);
					})
				});
			});		
		});

		//Modal media
		$(document).on('click','.block_button_epsd_click',function() {
			const mediaFrame = wp.media({
				title: 'Select or Upload an Image',
				button: { text: 'Use this image' },
				multiple: false 
			});
			mediaFrame.on('select', function() {
				const attachment = mediaFrame.state().get('selection').first().toJSON();
				$('.image-preview').attr('src', attachment.url);
				$('.image_preview').attr('value',attachment.url);
				$('.image-preview, #set-post-thumbnail-desc-preview').css('display','block');
				$('.block_button_epsd_click.dfip').replaceWith('<a id="remove-post-thumbnail-preview">Retirer l’image produit</a>');
				$('.inside-prg').css('padding','10px');

			});
			mediaFrame.open();
		});

		$(document).on('click', '#remove-post-thumbnail-preview', function(){
			definir_image(this);
		});

		function definir_image(pthis){
			$('.image_preview').attr({
				'value' : 'image-none'
			});
			setTimeout(() => {
				$('.image-preview, #set-post-thumbnail-desc-preview').css('display','none');
				$(pthis).text('Définir l’image produit');
				$("#remove-post-thumbnail-preview").replaceWith('<a class="block_button_epsd_click dfip">Définir l’image produit</a>');
				$(pthis).css('color','#2271b1');
				$('.inside-prg').css('padding','0px');
			}, 800);
		}

		$(document).ready(function(){
			if( 'image-none' == $('.image_preview').data('value') ) {
				definir_image(this);
			}	
		})

		//Designer
		$('.handlediv').click(function(){
			const insideElement = $(this).parent().parent().find('.inside');
			if (insideElement.is(':visible')) {
				$(this).find('.toggle-indicator').addClass('icon_tog');
				insideElement.hide();
			} else {
				$(this).find('.toggle-indicator').removeClass('icon_tog');
				insideElement.show();
			}
		});
		$('#designer_active').click(function(){
			const checked = $(this).is(':checked');
			$('#wp-submit').attr('disabled', false);
			if(!checked){
				$('#wp-submit').attr('disabled',true);
			}
		});

	});


})(jQuery);
