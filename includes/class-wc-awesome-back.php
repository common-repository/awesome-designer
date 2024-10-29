<?php
/**
 * Integration Admin Awesome.
 *
 * @package  Awesome_Base
 * @category awesome
 * @author   WooThemes
 */
 
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if( is_admin() ) {
	class THE_AWE_DES_Awesome_Back  {	
		
		private $awesome_liste;
		
		/**
		* Construct the plugin.
		*/
		public function __construct() {
			
			wp_enqueue_style( 'awesome-back-style',THE_AWE_DES_AWESOME_FILES_URL . 'css/awesome-back.css', array(), '1.0.1', false );		
			wp_enqueue_script( 'awesome-modernizr', THE_AWE_DES_AWESOME_FILES_URL . 'js/modernizr.js', array(), '1.0.0', true );
			wp_enqueue_script( 'awesome-back-js',THE_AWE_DES_AWESOME_FILES_URL . 'js/awesome-back.js', array(), '1.0.2', true );
			add_action( 'plugins_loaded', array( $this, 'init' ) );
		}
		
		
		/**
		* Initialize the plugin.
		*/
		public function init() {

			// Checks if WooCommerce is installed.
			if ( class_exists( 'WC_Integration' ) ) {		
				
				
				
				$this->the_awe_des_recup_donne_prod();
				add_filter('plugin_row_meta',   array( $this,'the_awe_des_register_plugins_links'), 10, 2);
				
				// Include awesome admin page.
				add_action( 'admin_menu', array( $this, 'the_awe_des_create_plugin_settings_page' ) );
				
				//add awesome option to product panel 
				add_action( 'woocommerce_product_write_panel_tabs', array( $this, 'the_awe_des_awesome_panel_tab' ) );
				add_action( 'woocommerce_product_write_panels',     array( $this, 'the_awe_des_awesome_write_panel' ) );
				//save awesome option in product panel 
				add_action( 'woocommerce_process_product_meta', array( $this,  'the_awe_des_awesome_save_data'));
				add_action( 'admin_notices',  array( $this, 'the_awe_des_awesome_admin_notices' ) );
				add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'the_awe_des_variable_fields'), 10, 3 );
				add_action( 'woocommerce_product_after_variable_attributes_js', array( $this, 'the_awe_des_variable_fields_js') );
				add_action( 'woocommerce_save_product_variation',array( $this,  'the_awe_des_save_variable_fields'), 10, 1 );
			
				add_action( 'woocommerce_admin_order_item_headers', array( $this,  'the_awe_des_awesome_add_order_item_header'  ));
				
				add_action( 'woocommerce_admin_order_item_values', array( $this,'the_awe_des_awesome_admin_order_item_values' ), 10, 3 ); 
				
			}
		}
	
		
		
		
	
/**
 * register_plugins_links 
 * Direct link to the settings page from the plugin page * @param  array  $links
 * @param  string $file
 * @return array
 */
public function the_awe_des_register_plugins_links ($links, $file){
		
	if ($file == 'awesome-designer/awesome-designer.php') {
		$links[] = '<a href="admin.php?page=awesome_options">' . __('Settings','woocommerce-awesome-designer') . '</a>';
		$links[] = '<a href="https://www.theawesomedesigner.com/t-shirt-designer-plugin/" target="_blank">' . __('FAQ','woocommerce-awesome-designer') . '</a>';
		$links[] = '<a href="https://www.theawesomedesigner.com/product-designer-plugin/" target="_blank">' . __('Support','woocommerce-awesome-designer') . '</a>';
	}
	return $links;
}

	
	
	public function the_awe_des_recup_donne_prod(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'the_awe_des_awesome_product';
		$tab = array();
		$tab['']='none';	
		$list = $wpdb->get_results( 'SELECT id_prod,nom,colors FROM '.$table_name.' WHERE obj_order in (0,1) ORDER BY id_prod ASC,obj_order DESC ', OBJECT );	
		if ($list) {
			foreach ( $list as $result ) {	
				$tab[$result->id_prod] = $result->id_prod.' - '.$result->nom;
			}
		}		
		$this->awesome_liste =  serialize($tab);
	}
	
	public function the_awe_des_awesome_admin_notices() {

			global $woocommerce;

			
			if( function_exists('get_woocommerce_currency') && version_compare($woocommerce->version, '2.1', '<') ): ?>
			<div class="error">
		        <p><?php _e( 'Please update WooCommerce to the latest version! Awesome Designer only works with version 2.1 or newer.', 'woocommerce-awesome-designer' ); ?></p>
		    </div>
			<?php endif;

			if( !extension_loaded('gd') || !function_exists('gd_info') ): ?>
			<div class="error">
		        <p><?php _e( 'GD library is not installed on your web server. If you do not know how to install GD library, please ask your server provider!',  'woocommerce-awesome-designer' ); ?></p>
		    </div>
			<?php endif;

		}
	
		
		/**
		 * Adds a new tab to the Product Data postbox in the admin product interface
		 */
		public function the_awe_des_awesome_panel_tab() {
			echo "<li class=\"product_tabs_lite_tab show_if_simple \"><a href=\"#woocommerce_product_tabs_awesome\">AwesomeDesigner </a></li>";
		}
	
	
		public function the_awe_des_awesome_save_data( $post_id ) {
			
			if (isSet($_POST['the_awe_des_awesome_product'])){
				update_post_meta( $post_id, 'the_awe_des_awesome_product', $_POST['the_awe_des_awesome_product'] );
			
			}			
		}
	
		
		/**
		 * Adds the panel to the Product Data postbox in the product interface
		 */
		public function the_awe_des_variable_fields( $loop, $variation_data, $variation ) {
		?>
			<div class="options">
					<?php
					// Text Field
					woocommerce_wp_select( 
						array( 
						'name'          => '_select_produit['.$loop.']', 
						'label'       => __( 'Awesome product', 'woocommerce-awesome-designer' ).' : ',
						'value'       => get_post_meta( $variation->ID, '_select_produit', true ),
							'class'     =>  'css-awesome-'.$loop,
						'options' => unserialize($this->awesome_liste)	
						)
					);
				
					
				?>
				<div style="clear:both;"></div>
			</div>	
				
		<?php 
		}	
		

		public function the_awe_des_variable_fields_js() {
		?>
			<div class="options">
					<?php
					// Select
					woocommerce_wp_select( 
					array( 
						'name'          => '_select_produit[ + loop + ]', 
						'label'       => __( 'Awesome product', 'woocommerce-awesome-designer' ).' : ',
						'class'     => 'css-awesome-+loop',
						
						'options' => unserialize($this->awesome_liste)							
						)
					);
					
				
				?>
				<div style="clear:both;"></div>
			</div>	
			<?php 
		}
		
		
		public function the_awe_des_save_variable_fields( $post_id ) {
			if (isset( $_POST['variable_sku'] ) ) :
			//	$variable_sku          = $_POST['variable_sku'];
				$variable_post_id      = $_POST['variable_post_id'];
				
				
				foreach ($variable_post_id as $key => $values) {
					if (isSet($_POST['_select_produit'][$key])) update_post_meta( $values, '_select_produit', stripslashes( $_POST['_select_produit'][$key] ) );
					
				}			
				
			endif;
		}
	
		/**
		 * Adds the panel to the Product Data postbox in the product interface
		 */
		public function the_awe_des_awesome_write_panel() {
	
		
			echo '<div id="woocommerce_product_tabs_awesome" class="panel wc-metaboxes-wrapper woocommerce_options_panel">';	
			woocommerce_wp_select( 
					array( 
					'id'      => 'the_awe_des_awesome_product', 
					'label'   => __( 'Awesome product', 'woocommerce-awesome-designer' ).' : ', 
					'class'     => 'css-awesome class-select-awesome',
					'options' => unserialize($this->awesome_liste)
					)
				);
			echo '</div>';
		}
		public function the_awe_des_awesome_add_order_item_header() {
				echo '
					<th class="item-awesome">AwesomeDesigner</th>
				';
			}
			
		     
			
		public function the_awe_des_awesome_admin_order_item_values( $_product, $item, $item_id ) {
				
			$awesome_data = wc_get_order_item_meta( $item_id, 'awesome' );
			$awesome_color = wc_get_order_item_meta( $item_id, 'color' );
			$api_awesome = get_option('api_awesome_designer' );
			if ($api_awesome != '') {
				if (is_ssl()) $url_wtp = 'https://'.substr($api_awesome,0,2).'-awe.com/awesome/web/w2p-credit.php';
				else  $url_wtp = 'http://'.substr($api_awesome,0,2).'-awe.com/awesome/web/w2p-credit.php';
			}
			
			
			$path_array  = wp_upload_dir();
			
			
			echo '   <td class="item-awesome">'; 
			if (isSet($awesome_data) and $awesome_data!=null and  $awesome_data!=''){
					$path_img = $path_array['baseurl'].'/the-awe-des-awesome-commande/'.$awesome_data.'/rendu-'.$awesome_data.'-1.png';		
				 ?>
			<div class="awesome-item">
				<div style="float:left;width:150px;">
					<button type="button" onclick="javascript:the_awe_des_order('<?php echo $awesome_data;?>','<?php echo $api_awesome;?>','<?php echo site_url();?>','','<?php echo substr($awesome_color,1);?>');" class="button"><?php _e( 'Image information', 'woocommerce-awesome-designer' );?></button>
					<?php if (isSet($url_wtp)) { ?>
						<br><br><button type="button" onclick="javascript:the_awe_des_order('<?php echo $awesome_data;?>','<?php echo $api_awesome;?>','<?php echo $url_wtp;?>','<?php echo $path_array['baseurl'];?>','<?php echo substr($awesome_color,1);?>');" class="button"><?php _e( 'Get HD image', 'woocommerce-awesome-designer' );?></button>
					<?php } ?>
				</div>
				<div  style="float:left;width:150px;height:150px;background-color:white;background-position: center;background-size: contain;background-repeat: no-repeat;background-image: url('<?php echo $path_img;?>');">
		 
			</div>
				

			</div> <?php
			}
			echo '</td>';

		}
		
		 public function the_awe_des_create_plugin_settings_page() {

			
			add_menu_page( 'Awesome Designer', 'Awesome Designer', 'manage_options', 'awesome_options', array( $this, 'plugin_settings_page_content' ), THE_AWE_DES_AWESOME_FILES_URL . '/img/groove.png', '56' );
		}
		
		public function plugin_settings_page_content() {
			if( $_POST['updated'] === 'true' ){
				$this->handle_form();
			} 
			
			echo '<script>var url_site_awe="'.site_url().'";var url_pop_awe="'.site_url().'/index.php?awesome_designer_back_popup=accueil";
			
				function change_tab_awesome (value) {
					
					jQuery(".nav-tab").removeClass("nav-tab-active");
					jQuery("#"+value).addClass("nav-tab-active");
					jQuery(".awesome_talbe").css("display","none");
					jQuery("#tab_"+value).css("display","block");
				}
			
			
			</script>';
			
		
			
			?>
			
			<div class="wrap">
				<h2>My Awesome Settings Page</h2>
				<form method="POST">
					<input type="hidden" name="updated" value="true" />
					<?php wp_nonce_field( 'test_update', 'test_form' ); ?>
					
					<div style="float:left;display:block; width:100%;text-align:center;">
						<img src="<?php echo THE_AWE_DES_AWESOME_URL;?>/files/img/interface-admin/logo_315x60_admin.png" alt="The Awesome Designer" width="315" height="60">
					</div>
					
					<div style="float:left;display:block; width:40%;">
												
						<div style="margin-left:20%;margin-top:35px">
							<a href="javascript:the_awe_des_awesome('accueil');"><img src="<?php echo  THE_AWE_DES_AWESOME_URL;?>/files/img/interface-admin/main_admin_awesome_designer.png" alt="Main Admin Panel" width="285" height="57"></a>
						</div>
						<br/>
						<br/>
						<br/>
						<br/>
						<br/>
						<br/>
						<br/>						
						<br/>
						<ul>
							<li> <?php _e( 'For more information go to theawesomedesigner.com', 'woocommerce-awesome-designer' ); ?> : <a href="http://www.theawesomedesigner.com/" target="_blank" title="Awesome Designer WebSite">www.theawesomedesigner.com</a></li>
						</ul>
					</div>
					
					
					<div style="float:left;display:block; width:60%;">
						<div style="margin-top:25px">
							<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
								<a id="general" name="general" href="#" class="nav-tab  nav-tab-active"  onclick="javascript:change_tab_awesome('general')">General</a>
								<a id="color" name="color" href="#" class="nav-tab " onclick="javascript:change_tab_awesome('color')">Color</a>
								<a id="display" name="display" href="#" class="nav-tab " onclick="javascript:change_tab_awesome('display')">Front display options</a>
							</nav>
							<table id="tab_general" name="tab_general" class="form-table awesome_talbe" style="">
								<tbody>
									
									<tr>
										<th scope="row">API AWESOME</th>
										<td>
											<input name="api_awesome" id="api_awesome" placeholder="" value="<?php echo get_option('api_awesome_designer'); ?>" type="text">
											<p class="description"> <?php _e( ' Enter your API KEY', 'woocommerce-awesome-designer' ); ?></p>
										</td>
									</tr>
									<tr>
										<th scope="row">MAX UPLOAD SIZE</th>
										<td><input name="max_up_awesome" id="max_up_awesome" placeholder="" value="<?php echo get_option('max_up_awesome_designer'); ?>" type="number"><span class="helper"> ko</span>
											<p class="description"><?php echo  __( "Can't exceed the upload_max_filesize directive in php.ini. For your information", 'woocommerce-awesome-designer' ).' : '.file_upload_max_size().' ko';?></p>
										</td>
									</tr>
									<tr>
										<th scope="row">MIN UPLOAD SIZE</th>
										<td><input name="min_up_awesome" id="min_up_awesome" placeholder="" value="<?php echo get_option('min_up_awesome_designer'); ?>" type="number"><span class="helper"> ko</span></td>
									</tr>									
									
									<tr>
										<th scope="row">SIZE OF PREVIEW RENDER</th>
										<td>
											<input id="size_render_awesome" name="size_render_awesome" type="range" min="300" max="600" value="<?php echo get_option('size_render_awesome'); ?>" step="50" data-rangeslider onchange="jQuery('#render_awesome').empty().append(this.value);"><span id="render_awesome" style="margin-bottom:10px;margin-left:20px"> <?php echo get_option('size_render_awesome'); ?></span> px
											<p class="description"> <?php _e( 'Define the size of the preview render for your customer. The bigger takes more time to generate.', 'woocommerce-awesome-designer' ); ?></p>
										</td>
									</tr>	
									
								</tbody>
							</table>
						
							<table id="tab_color" name="tab_color" class="form-table awesome_talbe" style="display:none;">
								<tbody>
																								
									<tr>
										<th scope="row">COLOR LIST MOBILE</th>
										<td><textarea name="col_mob_awesome" id="col_mob_awesome" placeholder="" rows="4" cols="80"><?php echo get_option('col_mob_awesome_designer'); ?></textarea>
											<p class="description"><?php _e( "The mobile version has no colorpicker but a list of colors", 'woocommerce-awesome-designer' );?></p>
										</td>
									</tr>
									<tr>
									
										<th scope="row">SPECIFIC RANGE OF COLORS </th>
										<td><textarea name="col_lim_awesome_designer" id="col_lim_awesome_designer" placeholder="List of colors in hexadecimal separated by commas" rows="4" cols="80" ><?php echo get_option('col_lim_awesome_designer'); ?></textarea>
											<p class="description"><?php _e( "Only the colors you'll set in this field will be usable in the plugin. If you want to use a full range colorpicker, leave it blank", 'woocommerce-awesome-designer' );?></p>
										</td>
									</tr>
								</tbody>
							</table>
							<table id="tab_display" name="tab_display" class="form-table awesome_talbe" style="display:none;">
								<tbody>
																								
									<tr>
										<th scope="row"><?php _e( "Choose to display or hide tools", 'woocommerce-awesome-designer' );?></th>
										<td>
											<?php _e( "Photo upload", 'woocommerce-awesome-designer' );?> : <input type="checkbox" name="aff_img_awesome_designer" id="aff_img_awesome_designer" <?php if (!get_option('aff_img_awesome_designer') or get_option('aff_img_awesome_designer')=="1") echo 'checked';?>>&nbsp;&nbsp;&nbsp;
											<?php _e( "Text", 'woocommerce-awesome-designer' );?> : <input type="checkbox" name="aff_txt_awesome_designer" id="aff_txt_awesome_designer" <?php if (!get_option('aff_txt_awesome_designer') or get_option('aff_txt_awesome_designer')=="1") echo 'checked';?>>&nbsp;&nbsp;&nbsp;
											<?php _e( "Clipart", 'woocommerce-awesome-designer' );?> : <input type="checkbox" name="aff_clip_awesome_designer" id="aff_clip_awesome_designer" <?php if (!get_option('aff_clip_awesome_designer') or get_option('aff_clip_awesome_designer')=="1") echo 'checked';?>>
										</td>
									</tr>
									<tr>
									<th scope="row"><?php _e( "Choose to display option in waiting screen", 'woocommerce-awesome-designer' );?> </th>
										<td>
											<?php _e( "modify / abort", 'woocommerce-awesome-designer' );?> : <input type="checkbox" name="aff_abort_awesome_designer" id="aff_abort_awesome_designer" <?php if (!get_option('aff_abort_awesome_designer') or get_option('aff_abort_awesome_designer')=="1") echo 'checked';?>>&nbsp;&nbsp;&nbsp;
											<?php _e( "Preview with modify option", 'woocommerce-awesome-designer' );?> : <input type="checkbox" name="aff_rendu_awesome_designer" id="aff_rendu_awesome_designer" <?php if (!get_option('aff_rendu_awesome_designer') or get_option('aff_rendu_awesome_designer')=="1") echo 'checked';?>>&nbsp;&nbsp;&nbsp;
										</td>
									</tr>
									
									<tr>
									<th scope="row"><?php _e( "Quality Allowed", 'woocommerce-awesome-designer' );?> </th>
										<td>
										
										<?php _e( "No rules", 'woocommerce-awesome-designer' );?> : <input type="radio" name="qual_ratio_awesome_designer" id="qual_ratio_awesome_designer" value="4" <?php if ( get_option('qual_ratio_awesome_designer')=="4") echo 'checked="checked"';?>>
										<?php _e( "Permissive", 'woocommerce-awesome-designer' );?> : <input type="radio" name="qual_ratio_awesome_designer" id="qual_ratio_awesome_designer" value="3" <?php if ( get_option('qual_ratio_awesome_designer')=="3" or !get_option('qual_ratio_awesome_designer') ) echo 'checked="checked"';?>>
										<?php _e( "Median", 'woocommerce-awesome-designer' );?> : <input type="radio" name="qual_ratio_awesome_designer" id="qual_ratio_awesome_designer" value="2" <?php if ( get_option('qual_ratio_awesome_designer')=="2") echo 'checked="checked"';?>>
										<?php _e( "Max quality", 'woocommerce-awesome-designer' );?> : <input type="radio" name="qual_ratio_awesome_designer" id="qual_ratio_awesome_designer" value="1" <?php if ( get_option('qual_ratio_awesome_designer')=="1") echo 'checked="checked"';?>>
																				
										</td>
									</tr>
									<tr>
									<th scope="row"><?php _e( "Action requested on low quality warning", 'woocommerce-awesome-designer' );?> </th>
										<td>
										<?php _e( "Do nothing", 'woocommerce-awesome-designer' );?> : <input type="radio" name="qual_action_awesome_designer" id="qual_action_awesome_designer" value="1" <?php if ( get_option('qual_action_awesome_designer')=="1"  ) echo 'checked="checked"';?>>
										<?php _e( "Advise the user", 'woocommerce-awesome-designer' );?> : <input type="radio" name="qual_action_awesome_designer" id="qual_action_awesome_designer" value="2" <?php if ( get_option('qual_action_awesome_designer')=="2" or !get_option('qual_action_awesome_designer')) echo 'checked="checked"';?>>
										<?php _e( "Disallow finishing", 'woocommerce-awesome-designer' );?> : <input type="radio" name="qual_action_awesome_designer" id="qual_action_awesome_designer" value="3" <?php if ( get_option('qual_action_awesome_designer')=="3") echo 'checked="checked"';?>>
																				
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div style="float:right;margin-right:5%;">
							<?php submit_button(); ?>
						</div>	
					</div>
					
				  
				</form>
			</div> 
			<?php
		}
		public function handle_form() {
			if( ! isset( $_POST['test_form'] ) || ! wp_verify_nonce( $_POST['test_form'], 'test_update' ) ){ ?>
			   <div class="error">
				   <p>Sorry, your nonce was not correct. Please try again.</p>
			   </div> <?php
			   exit;
			} else {
			   
				$api_awesome = sanitize_text_field( $_POST['api_awesome'] );
				$max_up_awesome = sanitize_text_field( $_POST['max_up_awesome'] );
				$min_up_awesome = sanitize_text_field( $_POST['min_up_awesome'] );
				$col_mob_awesome = sanitize_text_field( $_POST['col_mob_awesome'] );
				
				$size_render_awesome = sanitize_text_field( $_POST['size_render_awesome'] );
				
				$col_lim_awesome_designer = sanitize_text_field( $_POST['col_lim_awesome_designer'] );
				$aff_img_awesome_designer = sanitize_text_field( $_POST['aff_img_awesome_designer'] );
				$aff_txt_awesome_designer = sanitize_text_field( $_POST['aff_txt_awesome_designer'] );
				$aff_clip_awesome_designer = sanitize_text_field( $_POST['aff_clip_awesome_designer'] );
				
				$aff_abort_awesome_designer = sanitize_text_field( $_POST['aff_abort_awesome_designer'] );
				$aff_rendu_awesome_designer = sanitize_text_field( $_POST['aff_rendu_awesome_designer'] );
				
				$qual_ratio_awesome_designer = sanitize_text_field( $_POST['qual_ratio_awesome_designer'] );
				$qual_action_awesome_designer = sanitize_text_field( $_POST['qual_action_awesome_designer'] );
				
				if ($qual_ratio_awesome_designer!='') update_option( 'qual_ratio_awesome_designer', $qual_ratio_awesome_designer );
				if ($qual_action_awesome_designer!='') update_option( 'qual_action_awesome_designer', $qual_action_awesome_designer );
				if( ($api_awesome=='' or ctype_alnum($api_awesome))) update_option( 'api_awesome_designer', $api_awesome );
				if (is_numeric($max_up_awesome) and $max_up_awesome <= file_upload_max_size() ) update_option( 'max_up_awesome_designer', $max_up_awesome );
				else {
					update_option( 'max_up_awesome_designer', file_upload_max_size());
					$error = 'The max upload size can\'t be greater than '.file_upload_max_size().'. You need to modify post_max_size and upload_max_filesize in your php.ini.' ;	
				
				}if (is_numeric($min_up_awesome)) update_option( 'min_up_awesome_designer', $min_up_awesome );
				if ($col_mob_awesome=='' or preg_match('/^[0-9a-zA-Z,#]*$/i',$col_mob_awesome)) update_option( 'col_mob_awesome_designer', $col_mob_awesome );
				if (is_numeric($size_render_awesome)) update_option( 'size_render_awesome', $size_render_awesome );	
				if ($col_lim_awesome_designer=='' or preg_match('/^[0-9a-zA-Z,#]*$/i',$col_lim_awesome_designer)) update_option( 'col_lim_awesome_designer', $col_lim_awesome_designer );
				
				if ($aff_img_awesome_designer!='')  update_option( 'aff_img_awesome_designer', 1 );
				else  update_option( 'aff_img_awesome_designer', 2 );
				
				if ($aff_txt_awesome_designer!='')  update_option( 'aff_txt_awesome_designer', 1 );
				else  update_option( 'aff_txt_awesome_designer', 2 );
				
				if ($aff_clip_awesome_designer!='')  update_option( 'aff_clip_awesome_designer', 1 );
				else  update_option( 'aff_clip_awesome_designer', 2 );
				
				if ($aff_abort_awesome_designer!='')  update_option( 'aff_abort_awesome_designer', 1 );
				else  update_option( 'aff_abort_awesome_designer', 2 );
				
				if ($aff_rendu_awesome_designer!='')  update_option( 'aff_rendu_awesome_designer', 1 );
				else  update_option( 'aff_rendu_awesome_designer', 2 );
				
			
				
				if (!isSet($error)) {
				?>
				
					<div class="updated">
						
						<p>Data saved.</p>
					</div> <?php
				}else { 
				
				?>
				
					<div class="updated">
						
						<p><?php echo $error;?>.</p>
					</div> <?php
				}
				
			}
		}
		
		
		
		
	}


	$WC_Admin_Awesome = new THE_AWE_DES_Awesome_Back( __FILE__ );

}	
?>