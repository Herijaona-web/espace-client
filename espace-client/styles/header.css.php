<style>
  #body_epsd{
    background-color:#fff;
  }
  #body_epsd .fusion-tb-footer.fusion-footer .fusion-widget-area{
    border:0;
  }
  .custom{
    background-color:#fff;
  }
  #body_epsd h1{
    font-family: 'Gotham bold';
    font-style: normal;
    font-weight: 700;
    font-size: 50px;
    line-height: 55px;
  }
  #body_epsd h2{
    font-family: 'Gotham bold';
    font-style: normal;
    font-weight: 325;
    font-size: 30px;
    line-height: 36px;
  }
    .site_header_epsd {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 90%;
      margin: 0px auto;
      padding:22px 10px;
    }
    .bloc_logo_epsd {
      display: flex;
      margin-left: 12%;
    }
    .header_para_epsd {
      font-family: 'Gotham light';
      font-style: normal;
      font-weight: 700;
      font-size: 24px;
      line-height: 27px;
      text-transform: uppercase;
      margin: 5px;
    } 
    .bloc_title {
      display: flex;
      flex-direction: column;
      width: 70%;
      align-self: center;
    }
    .bloc_title_para {
      font-family: 'Gotham light';
      font-style: normal;
      font-weight: 325;
      font-size: 30px;
      line-height: 36px;
      text-transform: uppercase;
      color: #fff;
    }
    .bloc_title_para2 {
      font-family: 'Gotham bold';
      font-style: normal;
      font-weight: 700;
      font-size: 50px;
      line-height: 55px;
      text-transform: uppercase;
      color: #FFFFFF;
    }
    #body_epsd .single_add_to_cart_button{      
      background-color:<?php echo $epsd_fond_site ? $epsd_fond_site.'A2' : '#beaa8a'; ?>;
      font-family: 'Gotham medium';
      font-style: normal;
      font-size: var(--button_font_size, 14px);
      line-height: 22px;
      text-align: center;
      text-transform: uppercase;
      color: #fff;
      border: 0px;
    }
    #body_epsd .etapes_print_grid_delivery_price_item.active{
      background-color:<?php echo $epsd_fond_site.'A2'; ?>!important;
    }
    #body_epsd button.btn_epsd{
      background-color:<?php  echo $epsd_fond_site ? $epsd_fond_site.'A2' : 'var(--button_gradient_top_color)'; ?>!important;
      border-radius: 0px;
      text-transform: uppercase;
    }
    #body_epsd #etapes_print_product_customization table tr {
      display: flex;
      flex-direction: column;
      width: 100%;
    }
    #body_epsd #etapes_print_product_customization table tr .label{
      margin-bottom: 4px!important;
      font-weight: 400;
      margin-top: 21px;
    }

    #body_epsd .summary-container h1.product_title.entry-title.fusion-responsive-typography-calculated {
      display:none;
    }
    .dataTables_length{
        margin:4px;
    }
  .accordion {
    background-color: #eee;
    color: #444;
    cursor: pointer;
    padding: 18px;
    width: 100%;
    border: none;
    text-align: left;
    outline: none;
    font-size: 15px;
    transition: 0.4s;
  }

  #body_epsd .active, .accordion:hover {
    background-color:1px solid #FFFBF4!important;
    border: <?php echo $epsd_fond_site ? $epsd_fond_site.'A2!important':'#FFFBF4!important'; ?>; 
  }

  .panel {
  padding: 0 18px;
  display: none;
  background-color: white;
  overflow: hidden;
  }
  .banniere_site_default{
    height: 500px;
    display: flex;
    flex-direction: column;
    justify-content: space-evenly;    
    background-color:#ccc;
  }
  .banniere_site{
    
    height: 500px;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: space-evenly;    
  }
 #body_epsd .etapes_print_custom_field_option.active {
    border: 1px solid #80725C85!important;
  }

#body_epsd .quantity{
  border-color: transparent;
}

#body_epsd .scroll_product{
  height: 50vh;
  overflow: overlay;
}
.scroll_product::-webkit-scrollbar {
  width: 7px; 
}
.scroll_product::-webkit-scrollbar-thumb {
  background: #FAFAFA; 
  border-radius: 5px;
}
.scroll_product::-webkit-scrollbar-thumb:hover {
  background: #beaa8a; 
}
.scroll_product::-webkit-scrollbar-track:hover {
  background: #f1f1f1; 
}
.scroll_product::-webkit-scrollbar-corner {
  background: #f1f1f1; 
}

#body_epsd .woocommerce-product-gallery-thumbnail{
  padding: 0;
  cursor: pointer;  
}
#body_epsd .woocommerce-product-gallery-thumbnail:hover {
    border: 1px solid #80725C85;
    opacity: 0.7;
}

.breadcrumb a{
  text-decoration: none;
  font-family: 'Gotham light';
  font-weight: 600;
  font-size: 13px;
}
.notification_panier{
  position: relative;
  background-color: <?php echo $epsd_fond_site;?>;
  padding: 20px;
  color:#fff;
  
}
a.button.wc-forward.panier_epsd {
    color: #fff;
}
.close-notification{
  position: absolute;
  top: -5px;
  right: 10px;
  cursor: pointer;
  font-size: 20px;
  color: #fff;
}
table.dataTable tbody th, table.dataTable tbody td {
    vertical-align: baseline;
}
table.dataTable thead th, table.dataTable thead td {
    font-weight: 400;
    font-size: 15px;
}
.dataTables_wrapper .dataTables_length select {
    height: 35px;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 4px 12px;
}
.pointer_none{
  pointer-events: none;
}
.image_product{
  height: 500px;
  max-height: 500px;

}
.thumbnail-container-img{
  padding:5px;
  max-width: 100%;
  height: auto;
}
@media (max-width: 767px) {
  .fiche_produit_front {
    display: flex;
    flex-direction: column-reverse;
  }
  .thumbnail-container{
    display: flex;
  }
  .image_product{
    height: auto!important;
    min-height:auto!important;
  }
  .bloc_title_para {
    font-size: 24px; /* Taille de police plus petite pour les mobiles */
  }
  h2.text-titre-epsd{
    font-size: 20px!important;
    text-align: center!important;
  }

  .bloc_title_para2 {
    font-size: 40px; /* Taille de police plus petite pour les mobiles */
    text-align: justify;
  }
  h1.titre_produit_epsd{
    font-size:20px!important;
  }
  }
  @media (min-width: 768px) and (max-width:1024px) {
  .col-img {
    flex: 0 0 auto;
    width: 74.666667%
  }

  .col-thumb {
    width: 20%;
  }
}  
.image-wrapper {
  position: relative;
  overflow: visible;
  cursor: pointer;
  width: 100px;
  height: 100px;  
}

.image-wrapper:hover .overlay {
  opacity: 1;
  transition: opacity 0.3s ease;
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  max-width: 100%;
  opacity: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  transition: opacity 0.3s ease;
}

.overlay img {
  max-width: 100px;
  width: 40px;
  height: auto;
}

.overlay span {
  color: white;
  margin-top: 10px;
  font-size: 14px;
}

.hidden-file-input {
  display: none;
}
  </style>
