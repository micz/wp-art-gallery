<?
/* Copyright 2017 Mic (email: m@micz.it)
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

	  const version='1.0.1';

	  //URL constants
	  const url_how_to='http://micz.it/wordpress-plugin-art-gallery/how-to/';
	  const url_custom_css_info='http://micz.it/wordpress-plugin-art-gallery/custom-css/';
	  const url_adaptive_colors_info='http://micz.it/wordpress-plugin-art-gallery/adaptive-colors/';
	  const url_donate='http://micz.it/wordpress-plugin-art-gallery/donate/';

	  //Options constants
	  const _pages='_pages';
	  const _subpages='_subpages';
	  const _only_custom_css='_only_custom_css';
	  const _gallery_link_text='_gallery_link_text';

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
	    //add_settings_field('wpmiczartgal_link_text',esc_html__('Gallery link text','wp-art-gallery'),null,'wpmiczartgal_settings_page','default');
	  }

	  public function admin_add_page(){
	    global $that;
      add_options_page(esc_html__('WP Art Gallery Settings','wp-art-gallery'),esc_html__('WP Art Gallery','wp-art-gallery'), 'manage_options', 'wpmiczartgal_settings_page', array($that,'output_settings_page'));
    }

    public function main_section_text() {
      $output='<p>';
      $output.='<b>'.esc_html__('How to use this plugin:','wp-art-gallery').'</b><br/>';
      $output.=esc_html__('1. Use the [mz_artg] shortcode in the page you want to show the Art Gallery on.','wp-art-gallery');
      $output.=' <a href="'.self::url_how_to.'" target="_blank">'.esc_html__('More info','wp-art-gallery').'</a>';
      $output.='<br/>';
      $output.=esc_html__('2. Add the needed parameters to the shortcode of that page. See below for more information.','wp-art-gallery').'<br/>';
      $output.='<br/>';
      $output.='<b>'.esc_html__('Shortcode Options','wp-art-gallery').'</b><br/>';
      $output.='<b>ids</b>: '.esc_html__('List of images ids to be shown.','wp-art-gallery').'<br/>';
      $output.='<b>tag</b>: '.esc_html__('Tag slug to search for the images to show. This is alternative to "ids" option.','wp-art-gallery').'<br/>';
      $output.='<b>link_text</b>: '.esc_html__('Define a link text for the gallery. It overrides the global option.','wp-art-gallery').'<br/>';
      $output.='<b>adaptive_color_force</b>: '.esc_html__('Set to true to use adaptive colors for all photo\'s texts.','wp-art-gallery').'<br/>';
      $output.='<b>adaptive_color_type</b>: '.esc_html__('Choose the adaptive color type. Default "Muted", other available values: "Vibrant", "DarkVibrant", "DarkMuted", "LightMuted".','wp-art-gallery').' <a href="'.self::url_adaptive_colors_info.'" target="_blank">'.esc_html__('More info','wp-art-gallery').'</a><br/>';
      $output.='</p>';
      echo $output;
    }

	  public function output_settings_page(){
?><div>
<h2><?_e('WP Art Gallery Settings','wp-art-gallery');?>&nbsp;&nbsp;&nbsp;<span style="font-size:12px;font-weight:normal;">v<?echo self::version;?></span></h2>
<?esc_html_e('Modify here the plugin\'s options.','wp-art-gallery');?><br/>
<br/><?esc_html_e('You can also use a custom CSS file to modify the Art Gallery look and feel.','wp-art-gallery');?> <a href="<?=self::url_custom_css_info;?>" target="_blank"><?esc_html_e('More info on custom CSS','wp-art-gallery');?></a>
<form action="options.php" method="post">
<?php settings_fields('wpmiczartgal_options');?>
<?php //$options = get_option('wpmiczartgal_options'); // Using $this->options?>
<?php do_settings_sections('wpmiczartgal_settings_page');?>
<table class="form-table">
   <tr valign="top"><th scope="row"><?esc_html_e('Gallery page','wp-art-gallery');?></th>
        <td><input type="text" name="wpmiczartgal_options[<?=self::_pages?>]" value="<?php echo $this->options[self::_pages]; ?>"/>
        <br/><input type="checkbox" name="wpmiczartgal_options[<?=self::_subpages?>]" value="1"<?php echo $this->options[self::_subpages]==1?'checked':'';?>><?esc_html_e('Include subpages.','wp-art-gallery');?>
        <br/><?esc_html_e('To optimize your website loading times, you could write here the pages id or permalink on which you have activated the Art Gallery with the shortcode.','wp-art-gallery');?><br/>
        <?esc_html_e('The ids or permalinks must be comma separated and can be mixed.','wp-art-gallery');?><br/>
        <?esc_html_e('All the styles and scripts needed by this plugin will be loaded only on those pages.','wp-art-gallery');?></td>
    </tr>
    <tr valign="top"><th scope="row"><?esc_html_e('Gallery link text','wp-art-gallery');?></th>
        <td><input name="wpmiczartgal_options[<?=self::_gallery_link_text?>]" type="text" value="<?php echo $this->options[self::_gallery_link_text]; ?>"/>
        <br/><?esc_html_e('If left empty it will be used "Enter Gallery".','wp-art-gallery');?>
        </td>
    </tr>
</table>
<input name="Submit" class="button button-primary" type="submit" value="<?php esc_attr_e('Save Changes','wp-art-gallery');?>"/>
</form></div>
	  <?}

  public function options_validate($input) {
    $newinput[self::_pages] = trim(wp_filter_nohtml_kses($input[self::_pages]));
    $newinput[self::_subpages] = trim(wp_filter_nohtml_kses($input[self::_subpages]));
    $newinput[self::_gallery_link_text] = trim(wp_filter_nohtml_kses($input[self::_gallery_link_text]));
    return $newinput;
  }

  public function sanitizeOptions($options){
	if(is_array($options)){
		if(!array_key_exists(self::_pages,$options)){$options[self::_pages]='';}
		if(!array_key_exists(self::_subpages,$options)){$options[self::_subpages]=0;}
		if(!array_key_exists(self::_gallery_link_text,$options)){$options[self::_gallery_link_text]='';}
	}
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

//Output shortcode [mz_artg]
	 public function getShortcode($atts){
	    $output='';
	    //get user param
	    extract(shortcode_atts(array('ids'=>'','tag'=>'','link_text'=>'','adaptive_color_force'=>1,'adaptive_color_type'=>'Muted'),$atts));
	    $ids=trim(wp_filter_nohtml_kses($ids));
	    $tag=trim(wp_filter_nohtml_kses($tag));
	    $link_text=trim(wp_filter_nohtml_kses($link_text));
	    $adaptive_color_force=trim(wp_filter_nohtml_kses($adaptive_color_force));
	    $adaptive_color_type=trim(wp_filter_nohtml_kses($adaptive_color_type));

      if($this->scripts_loaded==false){ //the user is not loading the scripts in this page
        if(current_user_can('manage_options')){ //the current user can manage options
          return '<p><span style="color:red;font-weight:bold;">'.esc_html__('You\'ve set the wrong page id or permalink in the plugin settings, so the Art Gallery Plugin scripts are not loaded in this page!','wp-art-gallery').'<span></p>';
        }else{ //the current can NOT manage options
          return '';
        }
      }
      //print here the Art Gallery javascript code
      //see https://core.trac.wordpress.org/browser/tags/4.5.3/src/wp-includes/media.php#L1577
      $attachments = array();
      $using_tagged_images=false;

		if ( ! empty( $ids ) ) {
			//$output.='gallery ids= '.$ids.'</br/>';

			$_attachments = get_posts(array(
								'include' => $ids,
								'post_status' => 'inherit',
								'post_type' => 'attachment',
								'post_mime_type' => 'image',
								'orderby'   => 'post__in',
							) );

			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		}elseif( ! empty( $tag ) ) {
			//we are going to extract all the images from the tagged posts (type image). Limit to max 20 at the moment.
			$_tagged_posts = get_posts( array(
							 	'post_type' => 'post',
							 	'tax_query' => array(
									array(
										'taxonomy' => 'post_format',
										'field'    => 'slug',
										'terms'    => array( 'post-format-image' ),
									),
								),
								'tag' => $tag,
								'numberposts' => 20,
							 ) );

			$_tagged_posts_ids_array=array();

			foreach($_tagged_posts as $tagged_post) {
				$_tagged_posts_ids_array[]=$tagged_post->ID;
			}

			$_tagged_posts_ids=join(',',$_tagged_posts_ids_array);

			//$output.='gallery ids from tag= '.$_tagged_posts_ids.'</br/>';

			$_attachments = get_posts( array(
								'post_parent__in' => $_tagged_posts_ids_array,
								'post_status' => 'inherit',
								'post_type' => 'attachment',
								'post_mime_type' => 'image',
								'orderby'   => 'post__in',
							) );

			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}

			$using_tagged_images=true;
		}

		if ( empty( $attachments ) ) {
			$output.='<br/><b>'.esc_html__('No images found!','wp-art-gallery').'</b></br/>';
			return $output;
		}

		$output_js_start='<script language="javascript">';
		$output_js_end='</script>';

		//Javascript options
		$output_options='let wpartg_options=[];';
		//if using tag, force adaptive color
		if($using_tagged_images||$adaptive_color_force){
			$output_options.='wpartg_options["adaptive_color_force"]=1;';
		}

		$output_options.='wpartg_options["adaptive_color_type"]="'.$adaptive_color_type.'";';

		$output_img_js_array='let wpartg_img_array=[';

		foreach ( $attachments as $id => $attachment ) {
			$img_metadata=wp_get_attachment_image_src($id,'large');
			$img_data=wpmz_get_attachment($id);
			//$output.=$img_metadata[0].'<br/>';
			$output_img_js_array.='{href:"'.$img_metadata[0].'",title:"'.$img_data['title'].'",alt:"'.$img_data['alt'].'",caption:"'.$img_data['caption'].'",desc:"'.$img_data['description'].'"},';
		}

		$output_img_js_array=trim($output_img_js_array,',');
		$output_img_js_array.='];';
		//Javascript Output - START
		$output.=$output_js_start;
		$output.=$output_img_js_array;
		$output.=$output_options;
		$output.=$output_js_end;
		//Javascript Output - END

		$gallery_link_text=__('Enter Gallery','wp-art-gallery');
		if($this->options[self::_gallery_link_text]!=''){
			$gallery_link_text=$this->options[self::_gallery_link_text];
		}
		if(!empty($link_text)){
			$gallery_link_text=$link_text;
		}

		$output.='<a href="javascript:wpartg_enter_gallery(wpartg_img_array);">'.$gallery_link_text.'</a>';

     	return $output;
	  }
//Output shortcode [mz_artg] - END

	} //END WPArtGallery

} //END if class_exists('WPArtGallery')
?>
