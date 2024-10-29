<?php
/**
 * @package AWESOME DESIGNER
 * @version 1.4.3
 */
/*
Plugin Name: Awesome Designer
Plugin URI: http://www.theawesomedesigner.com/
Description: The first real web to print product designer plugin
Author: netfxs
Version: 1.4.3
Author URI: http://www.theawesomedesigner.com/
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
global $awesome_db_version;
$awesome_db_version = '1.5';

//define constants
define( 'THE_AWE_DES_AWESOME_PATH', plugin_dir_path( __FILE__ ) );
define( 'THE_AWE_DES_AWESOME_INCLUDES', THE_AWE_DES_AWESOME_PATH . 'includes/' );
define( 'THE_AWE_DES_AWESOME_URL', plugins_url( '/', __FILE__ ) );
define( 'THE_AWE_DES_AWESOME_FILES_URL', THE_AWE_DES_AWESOME_URL . 'files/' );

//includes
register_activation_hook( __FILE__, 'the_awe_des_awesome_install' );

 
//add action to load my plugin files
add_action('plugins_loaded', 'the_awe_des_awesome_load_translation_files');



if( is_admin() ) {
    //---- ADMIN ----//   
    require_once( THE_AWE_DES_AWESOME_INCLUDES . 'class-wc-awesome-back.php' );	
} else {
    //---- FRONT END ----//
    require_once( THE_AWE_DES_AWESOME_INCLUDES . 'class-wc-awesome-front.php' );
}

/*
  * this function loads my plugin translation files
  */
function the_awe_des_awesome_load_translation_files() {
	load_plugin_textdomain('woocommerce-awesome-designer', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
}

function file_upload_max_size() {
  static $max_size = -1;

  if ($max_size < 0) {
    // Start with post_max_size.
    $max_size = parse_size(ini_get('post_max_size'));

    // If upload_max_size is less, then reduce. Except if upload_max_size is
    // zero, which indicates no limit.
    $upload_max = parse_size(ini_get('upload_max_filesize'));
    if ($upload_max > 0 && $upload_max < $max_size) {
      $max_size = $upload_max;
    }
  }
  return $max_size;
}

function check_new(){
	
	if (!get_option( 'size_render_awesome')) add_option( 'size_render_awesome', '590' );
	
}

function parse_size($size) {
  $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
  $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
  if ($unit) {
    // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
    return round(($size * pow(1024, stripos('bkmgtpezy', $unit[0])))/1000);
  }
  else {
    return round($size/1000);
  }
}



function the_awe_des_awesome_query_var($query_vars ){
	$query_vars[] = 'awesome_designer_back_popup';
	$query_vars[] = 'awesome_designer_back_upload_back';
	$query_vars[] = 'awesome_designer_front_popup';
	$query_vars[] = 'awesome_designer_front_upload_back';
	$query_vars[] = 'awesome_designer_front_save_canvas';
	$query_vars[] = 'awesome_designer_back_recup_name';
	$query_vars[] = 'awesome_designer_back_crop_creation';
	$query_vars[] = 'awesome_designer_back_sav_masq';
	$query_vars[] = 'awesome_designer_back_get_text';
	$query_vars[] = 'awesome_designer_front_view';
	return $query_vars;
	
}

function the_awe_des_awesome_parse_request( &$wp ){
	
	if ( array_key_exists( 'awesome_designer_back_popup', $wp->query_vars ) ) {
		$page_dem = $wp->query_vars['awesome_designer_back_popup'];
		if (ctype_alnum($page_dem)) include( THE_AWE_DES_AWESOME_INCLUDES . 'admin/module-'.$page_dem.'.php' );
		
		exit();
	}
	if ( array_key_exists( 'awesome_designer_back_upload_back', $wp->query_vars ) ) {
		$page_dem = $wp->query_vars['awesome_designer_back_upload_back'];		
		if (ctype_alnum($page_dem)) include( THE_AWE_DES_AWESOME_INCLUDES . 'admin/upload-'.$page_dem.'.php' );
		exit();
	}
	if ( array_key_exists( 'awesome_designer_back_recup_name', $wp->query_vars ) ) {
		include( THE_AWE_DES_AWESOME_INCLUDES . 'admin/recup_name.php' );
		exit();
	}
	if ( array_key_exists( 'awesome_designer_back_crop_creation', $wp->query_vars ) ) {
		include( THE_AWE_DES_AWESOME_INCLUDES . 'admin/crop-creation.php' );
		exit();
	}
	if ( array_key_exists( 'awesome_designer_back_get_text', $wp->query_vars ) ) {
		include( THE_AWE_DES_AWESOME_INCLUDES . 'admin/get-texte.php' );
		exit();
	}
	if ( array_key_exists( 'awesome_designer_back_sav_masq', $wp->query_vars ) ) {
		include( THE_AWE_DES_AWESOME_INCLUDES . 'admin/save-masq.php' );
		exit();
	}
	if ( array_key_exists( 'awesome_designer_front_popup', $wp->query_vars ) ) {
		
		$id_type_awesome = $wp->query_vars['awesome_designer_front_popup'];
		include( THE_AWE_DES_AWESOME_INCLUDES . 'front/awesome-master.php' );
		exit();
	}
	if ( array_key_exists( 'awesome_designer_front_upload_back', $wp->query_vars ) ) {
		$id_fold = $wp->query_vars['awesome_designer_front_upload_back'];
		include( THE_AWE_DES_AWESOME_INCLUDES . 'front/upload-back.php' );
		exit();
	}
	if ( array_key_exists( 'awesome_designer_front_save_canvas', $wp->query_vars ) ) {
		include( THE_AWE_DES_AWESOME_INCLUDES . 'front/save-canvas.php' );
		exit();
	}
	if ( array_key_exists( 'awesome_designer_front_view', $wp->query_vars ) ) {
		
		$awesome_designer_id = $wp->query_vars['awesome_designer_front_view'];
		include( THE_AWE_DES_AWESOME_INCLUDES . 'front/dispatch.php' );
		exit();
	}
}

add_action( 'query_vars', 'the_awe_des_awesome_query_var' );
add_action( 'parse_request','the_awe_des_awesome_parse_request' );
check_new();

function the_awe_des_awesome_install() {

	global $wpdb;
	global $awesome_db_version;
 
	//variable par efaut 
	delete_option( 'api_awesome_designer');
	delete_option( 'col_lim_awesome_designer');
    delete_option( 'max_up_awesome_designer');
	delete_option( 'min_up_awesome_designer' );
	delete_option( 'col_mob_awesome_designer');
	delete_option( 'aff_img_awesome_designer');
	delete_option( 'aff_txt_awesome_designer');
	delete_option( 'aff_clip_awesome_designer' );
	delete_option( 'aff_abort_awesome_designer');
	delete_option( 'aff_rendu_awesome_designer' );
	delete_option( 'qual_ratio_awesome_designer');
	delete_option( 'qual_action_awesome_designer' );
	delete_option( 'size_render_awesome' );
	
	add_option( 'api_awesome_designer', 'frAPIKEYDEMO' );
	add_option( 'min_up_awesome_designer', '50' );
	add_option( 'col_lim_awesome_designer','');
	add_option( 'max_up_awesome_designer', file_upload_max_size());
	add_option( 'size_render_awesome', '590' );
	
	add_option( 'aff_img_awesome_designer', 1 );
	add_option( 'aff_txt_awesome_designer', 1 );
	add_option( 'aff_clip_awesome_designer', 1 );
	add_option( 'aff_abort_awesome_designer', 1 );
	add_option( 'aff_rendu_awesome_designer', 1 );	
	
	add_option( 'qual_ratio_awesome_designer', 3 );
	add_option( 'qual_action_awesome_designer', 2 );	
	
	add_option( 'col_mob_awesome_designer', '#000000,#e78e89,#f7a575,#fde8ab,#ccdf7b,#8ecaf2,#d997d8,#da4d45,#f37329,#fbd25d,#b3cf3b,#47a8e9,#c35cc2,#ad2a23,#c14e0b,#f9bc0f,#809525,#1881c8,#983897,#3c1716,#41210e,#544313,#3b4513,#0a2f47,#3e0e3c,#333333,#666666,#999999,#cccccc,#ffffff' );
	
	
	$table_name = $wpdb->prefix . 'the_awe_des_awesome_product';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id_prod mediumint(9) NOT NULL AUTO_INCREMENT,
		id_multi VARCHAR(30) NOT NULL,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		nom tinytext NOT NULL,
		nom_masque tinytext NOT NULL,
		width_cad SMALLINT NOT NULL,
		height_cad SMALLINT NOT NULL,
		image_par VARCHAR(50) DEFAULT '' NOT NULL,
		image_back VARCHAR(50) DEFAULT '' NOT NULL,
		image_masq VARCHAR(50) DEFAULT '' NOT NULL,
		folder VARCHAR(50) DEFAULT '' NOT NULL,
		width_canv SMALLINT NOT NULL,
		height_canv SMALLINT NOT NULL,
		left_canv SMALLINT NOT NULL,
		top_canv SMALLINT NOT NULL,
		width_prod FLOAT NOT NULL,
		height_prod FLOAT NOT NULL,
		obj_order SMALLINT NOT NULL,
		unit_prod VARCHAR(4) DEFAULT '' NOT NULL,
		colors VARCHAR(255) DEFAULT '' NOT NULL,		
		is_visible SMALLINT NOT NULL,
		affich_option VARCHAR(4) DEFAULT '' NOT NULL,
		UNIQUE KEY id_prod (id_prod)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	
	$table_name = $wpdb->prefix . 'the_awe_des_awesome_clipart';
	
	$sql = "CREATE TABLE $table_name (
		ID mediumint(9) NOT NULL AUTO_INCREMENT,
		TIME datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		NOM VARCHAR(250) DEFAULT '' NOT NULL,
		TYPE VARCHAR(10) DEFAULT '' NOT NULL,
		VISIB TINYINT(1) DEFAULT '1' NOT NULL,
		ID_CAT mediumint(9) DEFAULT '0'  NOT NULL,
		UNIQUE KEY ID (ID)
	) $charset_collate;";

	
	dbDelta( $sql );
	$table_name = $wpdb->prefix . 'the_awe_des_awesome_clipart_cat';
	$sql = "CREATE TABLE $table_name (
		ID mediumint(9) NOT NULL AUTO_INCREMENT,
		TIME datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		URL VARCHAR(250) DEFAULT '' NOT NULL,
		NOM VARCHAR(250) DEFAULT '' NOT NULL,
		VISIB TINYINT(1) DEFAULT '1' NOT NULL,
		UNIQUE KEY ID (ID)
	) $charset_collate;";

	dbDelta( $sql );
	
	$table_name = $wpdb->prefix . 'the_awe_des_awesome_font';
	 
	$sql = "CREATE TABLE $table_name (
		ID mediumint(9) NOT NULL AUTO_INCREMENT,
		TIME datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		NOM_URL VARCHAR(250) DEFAULT '' NOT NULL,
		NOM VARCHAR(250) DEFAULT '' NOT NULL,
		DFT  TINYINT(1) DEFAULT '0' NOT NULL,
		UNIQUE KEY ID (ID)
	) $charset_collate;";

	dbDelta( $sql );
	
	add_option( 'awesome_db_version', $awesome_db_version );
	
	require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php' );
	require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php' );
	$wp_fs_d = new WP_Filesystem_Direct( new StdClass() );
	$upload = wp_upload_dir();   
    $upload_dir = $upload['basedir'] . '/the-awe-des-awesome-clipart';
	if ( !$wp_fs_d->is_dir( $upload_dir ) && !$wp_fs_d->mkdir( $upload_dir, 0705 ) ) wp_die( sprintf( __( 'Impossible to create %s directory.' ), $upload_dir ) );
	
	$upload_dir = $upload['basedir'] . '/the-awe-des-awesome-product';
	if ( !$wp_fs_d->is_dir( $upload_dir ) && !$wp_fs_d->mkdir( $upload_dir, 0705 ) ) wp_die( sprintf( __( 'Impossible to create %s directory.' ), $upload_dir ) );
	$upload_dir = $upload['basedir'] . '/the-awe-des-awesome-product/base';
	if ( !$wp_fs_d->is_dir( $upload_dir ) && !$wp_fs_d->mkdir( $upload_dir, 0705 ) ) wp_die( sprintf( __( 'Impossible to create %s directory.' ), $upload_dir ) );
	
	
	
   $upload_dir = $upload['basedir'] . '/the-awe-des-awesome-commande';
	if ( !$wp_fs_d->is_dir( $upload_dir ) && !$wp_fs_d->mkdir( $upload_dir, 0705 ) ) wp_die( sprintf( __( 'Impossible to create %s directory.' ), $upload_dir ) );
	
	 $upload_dir = $upload['basedir'] . '/the-awe-des-awesome-upload';
	if ( !$wp_fs_d->is_dir( $upload_dir ) && !$wp_fs_d->mkdir( $upload_dir, 0705 ) ) wp_die( sprintf( __( 'Impossible to create %s directory.' ), $upload_dir ) );
	
	
	$table_name = $wpdb->prefix . 'the_awe_des_awesome_clipart_cat';

	$wpdb->replace( $table_name, array( 	'ID'=>1,'TIME' => current_time( 'mysql' ),'NOM' => 'Frequently used' ,'URL' => 'Frequently+used'	) 	);
	
	$filelist = $wp_fs_d->dirlist( THE_AWE_DES_AWESOME_PATH."files/img/forme/");
	
	$table_name = $wpdb->prefix . 'the_awe_des_awesome_clipart';
	$compteur= 1;
	foreach ( $filelist as $filelisttemp ){
		
		if (substr(substr($filelisttemp[name],0,-4),-2)!='_2') {
			$wpdb->replace($table_name, array( 	'ID'=>$compteur,'TIME' => current_time( 'mysql' ), 'NOM' => substr($filelisttemp[name],0,-4) ,'TYPE' =>  substr($filelisttemp[name],-3),'VISIB'=>'1', 'ID_CAT'=>'1'));
			$compteur++;
		}
		$wp_fs_d->copy(THE_AWE_DES_AWESOME_PATH."files/img/forme/".$filelisttemp[name],$upload['basedir'] . '/the-awe-des-awesome-clipart/'.$filelisttemp[name]);
		
		
	}
	
	
	$filelist = $wp_fs_d->dirlist( THE_AWE_DES_AWESOME_PATH."files/default-products/");
	foreach ( $filelist as $filelisttemp ){
		$upload_dir = $upload['basedir'] . '/the-awe-des-awesome-product/'.$filelisttemp[name];
		if ( !$wp_fs_d->is_dir( $upload_dir ) && !$wp_fs_d->mkdir( $upload_dir, 0705 ) ) wp_die( sprintf( __( 'Impossible to create %s directory.' ), $upload_dir ) );
		
		$filelist_2 = $wp_fs_d->dirlist( THE_AWE_DES_AWESOME_PATH."files/default-products/".$filelisttemp[name].'/');
		foreach ( $filelist_2 as $filelisttemp_2 ){
			$wp_fs_d->copy(THE_AWE_DES_AWESOME_PATH."files/default-products/".$filelisttemp[name].'/'.$filelisttemp_2[name],$upload['basedir'] . '/the-awe-des-awesome-product/'.$filelisttemp[name].'/'.$filelisttemp_2[name]);
			
		}
	}
	
		//Insertion font par defaut
	$table_name = $wpdb->prefix . 'the_awe_des_awesome_font';
	$wpdb->replace( $table_name, array('ID'=>1,'TIME' => current_time( 'mysql' ),'NOM_URL' => 'Alex+Brush' ,'NOM' => 'Alex Brush' ,'DFT' => 0	) 	);
	$wpdb->replace( $table_name, array('ID'=>2,'TIME' => current_time( 'mysql' ),'NOM_URL' => 'Advent+Pro' ,'NOM' => 'Advent Pro' ,'DFT' => 0	) 	);
	$wpdb->replace( $table_name, array('ID'=>3,'TIME' => current_time( 'mysql' ),'NOM_URL' => 'Aguafina+Script' ,'NOM' => 'Aguafina Script' ,'DFT' => 0	) 	);
	$wpdb->replace( $table_name, array('ID'=>4,'TIME' => current_time( 'mysql' ),'NOM_URL' => 'Archivo+Black' ,'NOM' => 'Archivo Black' ,'DFT' => 0	) 	);
	$wpdb->replace( $table_name, array('ID'=>5,'TIME' => current_time( 'mysql' ),'NOM_URL' => 'Berkshire+Swash' ,'NOM' => 'Berkshire Swash' ,'DFT' => 0	) 	);
	$wpdb->replace( $table_name, array('ID'=>6,'TIME' => current_time( 'mysql' ),'NOM_URL' => 'Bangers' ,'NOM' => 'Bangers' ,'DFT' => 0	) 	);
	$wpdb->replace( $table_name, array('ID'=>7,'TIME' => current_time( 'mysql' ),'NOM_URL' => 'Chango' ,'NOM' => 'Chango' ,'DFT' => 0	) 	);
	$wpdb->replace( $table_name, array('ID'=>8,'TIME' => current_time( 'mysql' ),'NOM_URL' => 'Julius+Sans+One' ,'NOM' => 'Julius Sans One' ,'DFT' => 0	) 	);
	$wpdb->replace( $table_name, array('ID'=>9,'TIME' => current_time( 'mysql' ),'NOM_URL' => 'Love+Ya+Like+A+Sister' ,'NOM' => 'Love Ya Like A Sister' ,'DFT' => 0	) 	);
	$wpdb->replace( $table_name, array('ID'=>10,'TIME' => current_time( 'mysql' ),'NOM_URL' => 'News+Cycle' ,'NOM' => 'News Cycle' ,'DFT' => 1	) 	);
	$wpdb->replace( $table_name, array('ID'=>11,'TIME' => current_time( 'mysql' ),'NOM_URL' => 'Special+Elite' ,'NOM' => 'Special Elite' ,'DFT' => 0	) 	);
	$wpdb->replace( $table_name, array('ID'=>12,'TIME' => current_time( 'mysql' ),'NOM_URL' => 'Spicy+Rice' ,'NOM' => 'Spicy Rice' ,'DFT' => 0	) 	);

	
}



?>