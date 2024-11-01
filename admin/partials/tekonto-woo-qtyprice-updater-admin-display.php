<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://tekonto.com
 * @since      1.0.0
 *
 * @package    Tekonto_Woo_Qtyprice_Updater
 * @subpackage Tekonto_Woo_Qtyprice_Updater/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<script type="text/javascript">
jQuery(document).ready(function($) {

	$('#download').click(function(){	var data = {
			'action': 'process_export',
			
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			response = response;
			var download = function(content, fileName, mimeType) {
			var a = document.createElement('a');
			mimeType = mimeType || 'application/octet-stream';

			if (navigator.msSaveBlob) { // IE10
				return navigator.msSaveBlob(new Blob([content], { type: mimeType }),     fileName);
			} else if ('download' in a) { //html5 A[download]
			a.href = 'data:' + mimeType + ',' + encodeURIComponent(content);
			a.setAttribute('download', fileName);
			document.body.appendChild(a);
			setTimeout(function() {
			a.click();
			document.body.removeChild(a);
			}, 66);
			return true;
			} else { //do iframe dataURL download (old ch+FF):
			var f = document.createElement('iframe');
			document.body.appendChild(f);
			f.src = 'data:' + mimeType + ',' + encodeURIComponent(content);

			setTimeout(function() {
			document.body.removeChild(f);
			}, 333);
			return true;
			}
			}
			download(response, 'dowload.csv', 'text/csv');	
		});
		});
		
		
		});
		
		
	
</script>

<script type="text/javascript">
function upload(){
  var formData = new FormData();
  //formData.append("action", "upload-attachment");
  formData.append("action", "process_import");
	
  var fileInputElement = document.getElementById("file");
  formData.append("async-upload", fileInputElement.files[0]);
  formData.append("name", fileInputElement.files[0].name);
  //alert(fileInputElement.files[0].name);	
  //also available on page from _wpPluploadSettings.defaults.multipart_params._wpnonce
  <?php $my_nonce = wp_create_nonce('media-form'); ?>
  formData.append("_wpnonce", "<?php echo $my_nonce; ?>");
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange=function(){
    if (xhr.readyState==4 && xhr.status==200){
	var result = document.getElementById("result");
	 result.innerHTML = xhr.responseText;
	 //'<span class="confirmation">Congratulations! your file has been processed successfully and your product qty and price have been updated!</span>';
      //xhr.responseText;
    }
	// else {
	// var result = document.getElementById("result");
	// result.innerHTML = '<span>something went wrong!</span>';
	// }
  }
  xhr.open("POST","/wp-admin/admin-ajax.php",true);
  xhr.send(formData);
}
</script>



<div class="wrap">
    <h3><?php echo esc_html( get_admin_page_title() ); ?></h3>
	<p>Woocommerce Qty and Price Updater let you quickly update your woocommerce product quantity and price without any fuss. It is as simple as 123. Just click <b> Download Qty and Price CSV</b> to get your existing product quantity and price file in CSV format. Update this file with your new product quantity and price, save it, then click <b>Upload & Process</b> to upload the modified quantity and price and you are done!</p>
	<p> Please note: if you have thousands of products, you might need to increase your WP_MEMORY_LIMIT.</p>
	<input class="button-primary" type='submit' id='download' name='submit' value='Download Qty and Price CSV'> 
	
	<p> Modified your quantity and price in the CSV file and ready to update? First select the file, then click the <b>Upload & Process</b> button. </p><p>Important! Please click the <b>Upload & Process</b> button only once and wait for the status message to appear. The file is sent in the background and processing is done in the background. Every click will cause the file to be sent again and being processed again, so don't do it. Be patient and wait. Once finished successfully, the status message will tell you. If not, it will be an error message instead.</p>
	
	<form action="" method="post" enctype= 'multipart/form-data'>
	<input type="file" name="file" id="file"><p>
	<input class="button-primary" type="submit" id="submit" name="Upload" value='Upload & Process' onclick="upload();return false;"></p>Status: <div id='result'></div>
	</form>
</div>
	
	