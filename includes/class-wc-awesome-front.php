<?php
/**
 * Integration Awesome FRONT.
 *
 * @package  Awesome_Base
 * @category awesome
 * @author   WooThemes
 */
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class THE_AWE_DES_Awesome_Front  {	
		
		/**
		* Construct the plugin.
		*/
		public function __construct() {
			
			require_once(THE_AWE_DES_AWESOME_INCLUDES.'/function.php');
			wp_enqueue_style( 'awesome-front-style',THE_AWE_DES_AWESOME_FILES_URL . 'css/awesome-front.css', array(), '1.0.3', true );			
			wp_enqueue_script( 'awesome-modernizr', THE_AWE_DES_AWESOME_FILES_URL . 'js/modernizr.js', array(), '1.0.0', true );
			wp_enqueue_script( 'awesome-front-js',THE_AWE_DES_AWESOME_FILES_URL . 'js/awesome-front.js', array(), '1.0.6', true );			
			
			add_action( 'plugins_loaded', array( $this, 'init' ) );
			
		}
		
		
		/**
		* Initialize the plugin.
		*/
		public function init() {
				
				
				add_filter( 'woocommerce_add_cart_item_data', array( $this,'the_awe_des_awesome_add_cart_item_data'), 10, 2 );
				add_filter( 'woocommerce_get_cart_item_from_session', array( $this,'the_awe_des_awesome_get_cart_item_from_session'), 20, 2 );
				
				//panier
				add_filter( 'woocommerce_cart_item_name', array( $this,'the_awe_des_awesome_render_meta_on_cart_item'), 1, 3 );
				
				add_action('init',array( $this,'the_awe_des_remove_loop_button'));
							
				add_action('woocommerce_after_shop_loop_item',array( $this,'the_awe_des_awesome_replace_add_to_cart'));
				
				add_filter( 'woocommerce_cart_item_thumbnail', array( &$this, 'the_awe_des_awesome_cart_item_thumbnail' ), 10, 3 );
				add_filter( 'woocommerce_in_cart_product_thumbnail', array( &$this, 'the_awe_des_awesome_cart_item_thumbnail' ), 10, 3 );
				add_filter( 'woocommerce_cart_item_permalink', array( &$this, 'the_awe_des_awesome_cart_item_link' ), 10, 3 );	
				
				
				add_filter( 'woocommerce_before_add_to_cart_button', array( $this, 'the_awe_des_add_awesome_input' ),98 );
			
				add_action('woocommerce_single_product_summary',array( $this,'the_awe_des_awesome_replace_add_to_cart_product'));
			
				add_action( 'woocommerce_add_order_item_meta', array( $this,'the_awe_des_awesome_add_order_item_meta_custom'), 10, 2 );
				
						
		}
		
		
	


		
		
		
		public function the_awe_des_awesome_add_to_cart_validation( $true, $product_id, $quantity ) {
			global $woocommerce;
			if (isSet($_POST[ 'the_awe_des_id_groo' ]) and $_POST[ 'the_awe_des_id_groo' ]!='') {
				
				return true;
			} else {
				$url = get_permalink( $product_id );
				wp_redirect( $url);	
				exit();
			}
			
		}
		
		public static function the_awe_des_awesome_cart_item_link( $link,$cart_item, $cart_item_key ) {
			
			if( isset( $cart_item['the_awe_des_awesome_field'] ) ) {
			  
				return false;
			
			} else {
				
				return $link;
				
			}
		
		}
		
		public static function the_awe_des_awesome_cart_item_thumbnail( $get_image, $cart_item, $cart_item_key ) {
        
        $image_tag = $get_image;
		
		
		
	   //Try to get the design id, first from the cart_item and then from the session
        if( isset( $cart_item['the_awe_des_awesome_field'] ) ) {
			
			$path_array  = wp_upload_dir();
			$path_img = $path_array['baseurl'].'/the-awe-des-awesome-commande/'.$cart_item['the_awe_des_awesome_field'].'/rendu-'.$cart_item['the_awe_des_awesome_field'].'-1.png';
		
			
            $new_src = 'src="'.$path_img . '"';			
		    $image_tag = preg_replace( '/src\=".*?"/', $new_src, $get_image );
			$image_tag = preg_replace( '/srcset\=".*?"/', '', $image_tag );
		}    
       
	
        return $image_tag;
    }
		
		
		
		public function the_awe_des_add_awesome_input( $tabs ) {
			echo '<input type="hidden" id="the_awe_des_id_groo" name="the_awe_des_id_groo" value="" /><input type="hidden" id="the_awe_des_coul_groo" name="the_awe_des_coul_groo" value="" />';
					
			return $tabs;	
		}
		
		public function the_awe_des_awesome_add_cart_item_data($cart_item_meta, $product_id){
			global $woocommerce;

			if (isSet($_POST[ 'the_awe_des_id_groo' ]) and $_POST[ 'the_awe_des_id_groo' ]!='') {
				if(empty($cart_item_meta['the_awe_des_awesome_field']))
				$cart_item_meta['the_awe_des_awesome_field'] = array();
				
				$cart_item_meta['the_awe_des_awesome_field'] = $_POST[ 'the_awe_des_id_groo' ];            
				
				if (isSet($_POST[ 'the_awe_des_coul_groo' ]) and $_POST[ 'the_awe_des_coul_groo' ]!='') {
					$cart_item_meta['the_awe_des_awesome_field_2'] = array();				
					$cart_item_meta['the_awe_des_awesome_field_2'] = $_POST[ 'the_awe_des_coul_groo' ];   
				}
				
				return $cart_item_meta;
			} else {
				return $cart_item_meta;
			}
		}
		
		public	function the_awe_des_awesome_get_cart_item_from_session( $cart_item, $values ) {

			if (!empty($values['the_awe_des_awesome_field'])) :
				$cart_item['the_awe_des_awesome_field'] = $values['the_awe_des_awesome_field'];
				if (!empty($values['the_awe_des_awesome_field_2'])) {$cart_item['the_awe_des_awesome_field_2'] = $values['the_awe_des_awesome_field_2'];}
				$cart_item = $this->the_awe_des_woocommerce_add_cart_item_custom( $cart_item );
			endif;

			return $cart_item;

		}
		
		public  function the_awe_des_woocommerce_add_cart_item_custom( $cart_item ) {

			// operation done while item is added to cart.
			
			return $cart_item;

		}
		
		public function the_awe_des_awesome_render_meta_on_cart_item( $title = null, $cart_item = null, $cart_item_key = null ) {
			if( $cart_item_key && is_cart() && $cart_item['the_awe_des_awesome_field']!='') {
				if ($cart_item['the_awe_des_awesome_field_2']!='') $raj = '<dt class="">'.__( 'Product color', 'woocommerce-awesome-designer' ).' : </dt><dd ><div style="margin-top:10px;margin-left:10px;height:10px;width:10px;background-color:'.$cart_item['the_awe_des_awesome_field_2'].';border:1px solid black;"></div></dd> ';
				else $raj='';
				echo $title. '<dl class="variation">
						 <dt class="">ID awesome : </dt><dd><p>'.$cart_item['the_awe_des_awesome_field'].'</p></dd>'.$raj.'  
						<dt class=""> </dt><dd><p><a href="'.site_url().'/index.php?awesome_designer_front_view='.$cart_item['the_awe_des_awesome_field'].'" target="_blank">'.__('See your customization', 'woocommerce-awesome-designer' ).'</a></p></dd>						 
					  </dl>';	
				
			}else {
				echo $title;
			}
		}
		
		
		public 	function the_awe_des_remove_loop_button(){			
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		}
				
		
		public function the_awe_des_awesome_replace_add_to_cart() {
			global $product;
			
		
			if (the_awe_des_is_awesome_product($product)){
				$link = $product->get_permalink();
				echo do_shortcode('<a href="'.$link.'" class="button addtocartbutton">'.__( 'View Product', 'woocommerce-awesome-designer' ).'</a>');
			} else {
					$link = $product->add_to_cart_url();
				echo do_shortcode('<a href="'.$link.'" class="button addtocartbutton">'.__( 'Add to cart', 'woocommerce-awesome-designer' ).'</a>');
			}
		}
		
		public function the_awe_des_awesome_replace_add_to_cart_product() {
			global $product;
				
			echo '<script>var url_site_awe="'.site_url().'";var confirm_text="'.__( "Do you want to make a new customization from scratch ?", 'woocommerce-awesome-designer' ).'";var url_pop_awe="'.site_url().'/index.php?awesome_designer_front_popup=";</script>';			
			
			if (the_awe_des_is_awesome_product($product)){
			
				if ($product->product_type=='simple') {
					
					$prod_fx =  get_post_meta( $product->id, 'the_awe_des_awesome_product', true );
					echo do_shortcode('<form class="cart cart_awesome_perso"><a id="personnalise_awesome_bouton" href="javascript:the_awe_des_awesome(\''.$prod_fx.'\')" class="single_add_to_cart_button button alt">'.__( 'Customize', 'woocommerce-awesome-designer' ).'</a></form>');
					woocommerce_template_single_add_to_cart();
					echo '<script >jQuery(".cart").not(".cart_awesome_perso").css("display","none");</script>';
					
				} else if ($product->product_type=='variable') {
					
					woocommerce_template_single_add_to_cart();					
					echo '<input type="hidden" id="product_groo" name="product_groo" value="" />';		
					
					echo "						
					<script > var lst_var_awesome_des=[];";
					$lst_var_awesome_des = $product->get_available_variations();
					foreach ($lst_var_awesome_des as $prod_variation) {
						$post_id = $prod_variation['variation_id'];
						$post_object = get_post($post_id);						
						echo 'lst_var_awesome_des['.$post_id.'] = {product:"'.get_post_meta( $post_object->ID, '_select_produit', true).'"};';
						
					}
					echo "
						jQuery('.variation_id').change( function(){	
							jQuery('.single_add_to_cart_button,.button,.alt').attr('style','display: none !important');
							jQuery('#the_awe_des_id_groo').val('');;
							jQuery('#the_awe_des_coul_groo').val(''); 
							
							
							if (jQuery('.variation_id').val()!='') {
								var bout_groo = '<form class=\"cart cart_awesome_multi\"><a id=\"personnalise_awesome_bouton\" href=\"javascript:the_awe_des_awesome( jQuery(\'#product_groo\').val())\" class=\"single_add_to_cart_button button alt\"  >".__( 'Customize', 'woocommerce-awesome-designer' )."</a></form>';
								
								jQuery('#product_groo').val(lst_var_awesome_des[jQuery('.variation_id').val()].product);								
								
								if (!jQuery('.cart_awesome_multi').length) jQuery('table.variations').after(bout_groo);
								jQuery('#personnalise_awesome_bouton').css('display','');
							} else {
								jQuery('#personnalise_awesome_bouton').attr('style','display: none !important');	
							}
							
						});
						
						jQuery('.single_add_to_cart_button,.button,.alt').attr('style','display: none !important');	
						
					
						</script>						
						";			
				}
			} else {
				woocommerce_template_single_add_to_cart();
			}
		}
		
		public function the_awe_des_awesome_add_order_item_meta_custom( $item_id, $values ) {

			if ( ! empty( $values['the_awe_des_awesome_field'] ) ) {
				
				woocommerce_add_order_item_meta( $item_id, 'awesome', $values['the_awe_des_awesome_field']); 
				if (isSet($values['the_awe_des_awesome_field_2']) and $values['the_awe_des_awesome_field_2']!='') woocommerce_add_order_item_meta( $item_id, 'color', $values['the_awe_des_awesome_field_2']); 
				
				
			}
		}
		
		
		
	}


	$WC_Front_Awesome = new THE_AWE_DES_Awesome_Front( __FILE__ );

?>