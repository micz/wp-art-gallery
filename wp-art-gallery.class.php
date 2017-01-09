<?
/* Copyright 2016 Mic (email: m@micz.it)
Plugin Info: http://micz.it/wordpress-plugin-art-gallery/

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

if (!class_exists('WPArtGallery')) {

	/**
	 * main class for WP Art Gallery
	 *
	 */

	class WPArtGallery {

	  public $options=array();
	  public $that;
	  public $scripts_loaded;

	  const version='1.0';

	  //URL constants
	  const url_custom_css_info='http://micz.it/wordpress-plugin-art-gallery/custom-css/';
	  const url_donate='http://micz.it/wordpress-plugin-art-gallery/donate/';

	  //Options constants
	  const _pages='_pages';
	  const _only_custom_css='_only_custom_css';

	  // Class Constructor
	  public function __construct(){
	    global $that;
	    $that=$this;
  	    $this->options = $this->sanitizeOptions(get_option('wpmiczartgal_options'));
        add_action('admin_init', array($that,'register_settings'));
        add_action('admin_menu', array($that,'admin_add_page'));
        add_shortcode('mz_artg', array($that,'getShortcode'));
        add_filter('plugin_action_links_'.plugin_basename(___FILE_wpag___),array($that,'add_settings_link'));
        add_filter('plugin_row_meta',array($that,'add_plugin_desc_links'),10,2);
        load_plugin_textdomain('wp-art-gallery',false,basename(dirname(___FILE_wpag___)).'/lang/');
        $this->scripts_loaded=false;
	  }

	  public function activate() {
		//nothing needed at the moment
	  }

	  //Settings page
	  public function register_settings(){
	    global $that;
	    register_setting('wpmiczartgal_options','wpmiczartgal_options',array($that,'options_validate'));
  	  	add_settings_section('wpmiczartgal_main', esc_html__('Main Settings','wp-art-gallery'), array($that,'main_section_text'), 'wpmiczartgal_settings_page');
	    add_settings_field('wpmiczartgal_user',esc_html__('500px User','wp-art-gallery'),null,'wpmiczartgal_settings_page','default');
	  }

	  public function admin_add_page(){
	    global $that;
      add_options_page(esc_html__('WP Art Gallery Settings','wp-art-gallery'),esc_html__('WP Art Gallery','wp-art-gallery'), 'manage_options', 'wpmiczartgal_settings_page', array($that,'output_settings_page'));
    }

    public function main_section_text() {
      $output='<p>';
      $output.='<b>'.esc_html__('How to use this plugin:','wp-art-gallery').'</b><br/>';
      $output.=esc_html__('1. Upload you photos and...','wp-art-gallery').'<br/>';
      $output.=esc_html__('2. Use the [mz_artg] shortcode in the page you want to show the Art Gallery on.','wp-art-gallery').'<br/>';
      $output.=esc_html__('3. Add the needed parameters to the shortcode of that page.','wp-art-gallery').'<br/>';
      $output.='</p>';
      echo $output;
    }

	  public function output_settings_page(){
?><div>
<h2><?_e('WP Art Gallery Settings','wp-art-gallery');?></h2>
<?esc_html_e('Modify here the plugin\'s options.','wp-art-gallery');?><br/>
<br/><?esc_html_e('You can also use a custom CSS file to modify the Art Gallery look and feel.','wp-art-gallery');?> (<a href="<?=self::url_custom_css_info;?>" target="_blank"><?esc_html_e('More info on custom CSS','wp-art-gallery');?></a>)
<form action="options.php" method="post">
<?php settings_fields('wpmiczartgal_options');?>
<?php //$options = get_option('wpmiczartgal_options'); // Using $this->options?>
<?php do_settings_sections('wpmiczartgal_settings_page');?>
<table class="form-table">
   <tr valign="top"><th scope="row"><?esc_html_e('Gallery page','wp-art-gallery');?></th>
        <td><input type="text" name="wpmiczartgal_options[<?=self::_pages?>]" value="<?php echo $this->options[self::_pages]; ?>"/>
        <br/><?esc_html_e('To optimize your website loading times, you could write here the pages id or permalink on which you have activated the art Gallery with the shortcode.','wp-art-gallery');?><br/>
        <?esc_html_e('The ids or permalinks must be comma separated and can be mixed.','wp-art-gallery');?><br/>
        <?esc_html_e('All the styles and scripts needed by this plugin will be loaded only on those pages.','wp-art-gallery');?></td>
    </tr>
   <tr valign="top"><th scope="row"><?esc_html_e('Exclusive custom CSS','wp-art-gallery');?></th>
        <td><input type="checkbox" name="wpmiczartgal_options[<?=self::_only_custom_css?>]" value="1"<?php if($this->options[self::_only_custom_css]==1){echo ' checked="checked"';} ?>"/> <?esc_html_e('Check this option if you want to load only your custom CSS and not the default one before your one.','wp-art-gallery');?><br/>
        <a href="<?=self::url_custom_css_info;?>" target="_blank"><?esc_html_e('More info on custom CSS','wp-art-gallery');?></a></td>
    </tr>
</table>
<input name="Submit" class="button button-primary" type="submit" value="<?php esc_attr_e('Save Changes','wp-art-gallery');?>"/>
</form></div>
	  <?}

  public function options_validate($input) {
    $newinput[self::_pages] = trim(wp_filter_nohtml_kses($input[self::_pages]));
    return $newinput;
  }

  public function sanitizeOptions($options){

    return $options;
  }

//Settings page - END

//Plugin admin page
  function add_settings_link($links){
    $links[] = '<a href="options-general.php?page=wpmiczartgal_settings_page">'.__('Settings','wp-art-gallery').'</a>';
	  return $links;
  }

  function add_plugin_desc_links($links,$file){
    if(strpos($file,plugin_basename(___FILE_wpag___))!==false){
      $links = array_merge($links,array('<a href="'.self::url_donate.'">'.__('Donate','wp-art-gallery').'</a>'));
    }
    return $links;
  }
//Plugin admin page - END

//Output shortcode [mz_artg] - param ids: list of image ids;
	 public function getShortcode($atts){
	    $output='';
	    //get user param
	    extract(shortcode_atts(array('ids'=>0),$atts));
	    $ids=trim(wp_filter_nohtml_kses($ids));
      if($this->scripts_loaded==false){ //the user is not loading the scripts in this page
        if(current_user_can('manage_options')){ //the current user can manage options
          return '<p><span style="color:red;font-weight:bold;">'.esc_html__('You\'ve set the wrong page id or permalink in the plugin settings, so the Art Gallery Plugin scripts are not loaded in this page!','wp-art-gallery').'<span></p>';
        }else{ //the current can NOT manage options
          return '';
        }
      }
      //print here the Art Gallery javascript code
      //see https://core.trac.wordpress.org/browser/tags/4.5.3/src/wp-includes/media.php#L1577
      $output.='gallery ids= '.$ids.'</br/>';
      $attachments = array();

		if ( ! empty( $ids ) ) {
			$_attachments = get_posts( array( 'include' => $ids, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image' ) );

			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		}

		if ( empty( $attachments ) ) {
			$output.='<br/><b>Attachments empty!!</b></br/>';
			return $output;
		}

		$output_hidden_img='<div style="display:none;">';
		$output_img_js_array='<script language="javascript">let wpartg_img_array=[';

		foreach ( $attachments as $id => $attachment ) {
			$img_metadata=wp_get_attachment_image_src($id,'large');
			$img_data=wpmz_get_attachment($id);
			$output.=$img_metadata[0].'<br/>';
			$output_hidden_img.='<img src="'.$img_metadata[0].'"/>';
			$output_img_js_array.='{href:"'.$img_metadata[0].'",title:"'.$img_data['title'].'",alt:"'.$img_data['alt'].'",caption:"'.$img_data['caption'].'",desc:"'.$img_data['description'].'"},';
		}

		$output_hidden_img.='</div>';
		$output_img_js_array=trim($output_img_js_array,',');
		$output_img_js_array.='];</script>';
		$output.=$output_hidden_img;
		$output.=$output_img_js_array;

		$output.='<a href="javascript:wpartg_enter_gallery(wpartg_img_array);">Enter Gallery</a>';

     	return $output;
	  }
//Output shortcode [mz_artg] - END

	public function getJsLang(){
		$jslang=array();
		$jslang['gal_nextLinkText']=esc_attr__('Next &rsaquo;','wp-art-gallery');
		$jslang['gal_prevLinkText']=esc_attr__('&lsaquo; Prev','wp-art-gallery');
		return $jslang;
	}

	public function getJsParams(){
/*		$jsparams=array();
		$jsparams[(self::_thumb_h)]=$this->options[(self::_thumb_h)];
		$jsparams[(self::_thumb_w)]=$this->options[(self::_thumb_w)];
		$jsparams[(self::_image_h)]=$this->options[(self::_image_h)];
		$jsparams[(self::_image_w)]=$this->options[(self::_image_w)];
		//$jsparams[(self::_plugin_img_path)]=plugins_url('img/',___FILE_wpag___);
		return $jsparams;*/
	}

	  /*public function getImageHTML($imgData){
	    $output='<li>
            <a class="thumb" name="optionalCustomIdentifier" href="'.$imgData['thumb_url'].'" title="'.$imgData['title'].'">
                <img src="'.$imgData['thumb_url'].'" alt="'.$imgData['title'].'" />
            </a>
            <div class="caption">'.$imgData['caption'].'</div>
        </li>';
      return $output;
	  }*/

	} //END WPArtGallery

} //END if class_exists('WPArtGallery')
?>
