<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://tekonto.com
 * @since      1.0.0
 *
 * @package    Tekonto_Woo_Qtyprice_Updater
 * @subpackage Tekonto_Woo_Qtyprice_Updater/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tekonto_Woo_Qtyprice_Updater
 * @subpackage Tekonto_Woo_Qtyprice_Updater/admin
 * @author     chasehe <support@tekonto.com>
 */
class Tekonto_Woo_Qtyprice_Updater_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tekonto_Woo_Qtyprice_Updater_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tekonto_Woo_Qtyprice_Updater_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tekonto-woo-qtyprice-updater-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tekonto_Woo_Qtyprice_Updater_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tekonto_Woo_Qtyprice_Updater_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tekonto-woo-qtyprice-updater-admin.js', array( 'jquery' ), $this->version, false );

	}
	public function add_menu_page() {
		$this->plugin_screen_hook_suffix = add_submenu_page(
			'woocommerce',
			__( 'Woocommerce Qty and Price Updater', 'tekonto-woo-qtyprice-updater' ),
			__( 'Woocommerce Qty and Price Updater', 'tekonto-woo-qtyprice-updater' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_woocommerce_qty_and_price_updater_page' )
		);

	}
	
	public function display_woocommerce_qty_and_price_updater_page(){
		include_once 'partials/tekonto-woo-qtyprice-updater-admin-display.php';
	}
	/*public function outputCSV($data) {
        $outputBuffer = fopen("php://output", 'w');
        foreach($data as $val) {
            fputcsv($outputBuffer, $val);
        }
        fclose($outputBuffer);
    }*/
	public function convert_to_csv($input_array, $output_file_name, $delimiter){
    /** open raw memory as file, no need for temp files, be careful not to run out of memory though */
    $f = fopen('php://memory', 'w');
    /** loop through array  */
	fputcsv($f, array('ID','SKU','QTY','PRICE','REGULAR_PRICE','SALES_PRICE'));
    foreach ($input_array as $line) {
        /** default php csv handler **/
        fputcsv($f, $line, $delimiter);
    }
    /** rewrind the "file" with the csv lines **/
    fseek($f, 0);
    /** modify header to be downloadable csv file **/
    header('Content-Type: application/csv');
    header('Content-Disposition: attachement; filename="' . $output_file_name . '";');
    /** Send file to browser for download */
    fpassthru($f);
	}
	/**
 * Takes in a filename and an array associative data array and outputs a csv file
 * @param string $fileName
 * @param array $assocDataArray     
 */
	/*public function outputCsv($fileName, $assocDataArray){
    ob_clean();
    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Cache-Control: private', false);
    header('Content-Type: text/csv');
	outputCsv('Content-Description: File Transfer');
    header('Content-Disposition: attachment;filename=' . $fileName);    
    if(isset($assocDataArray['0'])){
        $fp = fopen('php://output', 'w');
        fputcsv($fp, array_keys($assocDataArray['0']));
        foreach($assocDataArray AS $values){
            fputcsv($fp, $values);
        }
        fclose($fp);
    }
    ob_flush();
	}*/
	public function process_export() {
			global $wpdb; 

			$prices=$wpdb->get_results( 
			"SELECT ps.ID,pt.meta_key,pt.meta_value   
			FROM wp_posts AS ps  
			INNER JOIN wp_postmeta AS pt  ON ps.ID=pt.post_id AND pt.meta_key='_price'",ARRAY_A);
			$regularprices=$wpdb->get_results( 
			"SELECT ps.ID,pt.meta_key,pt.meta_value   
			FROM wp_posts AS ps  
			INNER JOIN wp_postmeta AS pt  ON ps.ID=pt.post_id AND pt.meta_key='_regular_price'",ARRAY_A);
			$salesprices=$wpdb->get_results( 
			"SELECT ps.ID,pt.meta_key,pt.meta_value   
			FROM wp_posts AS ps  
			INNER JOIN wp_postmeta AS pt  ON ps.ID=pt.post_id AND pt.meta_key='_sale_price'",ARRAY_A);
			$skus=$wpdb->get_results( 
			"SELECT ps.ID,pt.meta_key,pt.meta_value   
			FROM wp_posts AS ps  
			INNER JOIN wp_postmeta AS pt  ON ps.ID=pt.post_id AND pt.meta_key='_sku'",ARRAY_A);
			$stocks=$wpdb->get_results( 
			"SELECT ps.ID,pt.meta_key,pt.meta_value   
			FROM wp_posts AS ps  
			INNER JOIN wp_postmeta AS pt  ON ps.ID=pt.post_id AND pt.meta_key='_stock'",ARRAY_A);
			$d='';
			$e='';
			$g='';
			$i='';
			$k='';
			$m='';
			foreach($stocks as $stock)
			$d .= $stock["meta_value"].',';
			$c=explode(',',$d,-1);
			foreach($prices as $price)
			$e .= $price["meta_value"].',';
			$f=explode(',',$e,-1);
			foreach($skus as $sku)
			$g .= $sku["meta_value"].',';
			$h=explode(',',$g,-1);
			foreach($skus as $sku)
			$i .= $sku["ID"].',';
			$j=explode(',',$i,-1);
			foreach($regularprices as $regularprice)
			$m .= $regularprice["meta_value"].',';
			$n=explode(',',$m,-1);
			foreach($salesprices as $salesprice)
			$k .= $salesprice["meta_value"].',';
			$l=explode(',',$k,-1);
			
						
			$o= array_map(null, $j, $h, $c, $f, $n, $l);
			
			
			/*$filename = "example";

			header("Content-type: application/csv");
			header("Content-Disposition: attachment; filename={$filename}.csv");
			header("Pragma: no-cache");
			header("Expires: 0");

			$this->outputCSV($k);*/
			$response = $this->convert_to_csv($o, 'report.csv', ',');
			//$this->outputCsv('expenses.csv', $k);

			
 
			// response output
			
			echo $response;


			wp_die(); // this is required to terminate immediately and return a proper response

	}
	public function process_import() {
	global $wpdb;
	
	try {$data=file_get_contents($_FILES['async-upload']['tmp_name']);
	$data =preg_split('/[\s]+/',$data);
	$j=1;
	$i=sizeof($data);
	for($j=1;$j<$i-1;$j++)
    {
     
	$e=explode(',', $data[$j]);
	//var_dump($e);
	$sql = "update wp_postmeta set meta_value=$e[3] where post_id=$e[0] and meta_key='_price'";
	$sql2 = "update wp_postmeta set meta_value=$e[2] where post_id=$e[0] and meta_key='_stock'";
	$sql3 = "update wp_postmeta set meta_value=$e[4] where post_id=$e[0] and meta_key='_regular_price'";
	$sql4 = "update wp_postmeta set meta_value=$e[5] where post_id=$e[0] and meta_key='_sale_price'";
	$wpdb->query($sql);
	$wpdb->query($sql2);
	$wpdb->query($sql3);
	$wpdb->query($sql4);
	}
	
	echo "successfully updated";
	}
	catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
	wp_die( );
	}
	}