<?php
/* load a favicon */
add_action('wp_head', 'favicon_link');
function favicon_link() {
  echo '<link rel="shortcut icon" type="image/x-icon" href="' . get_bloginfo('siteurl') . '/favicon.ico" />';
}

/* enable styles for MCE editory */
// add_editor_style();
add_filter('mce_css', 'my_editor_style');
function my_editor_style($url) {
  if ( !empty($url) )
    $url .= ',';
  // Change the path here if using sub-directory
  $url .= get_stylesheet_directory()  . '/editor-style.css';
  return $url;
}

/* enable thumbnails */
add_theme_support('post-thumbnails');


/* Remove gnerator tag (for addthis and google plus button) */
remove_action('wp_head', 'wp_generator');

/* Better caption shortcode. Wordpress adds 10px to the width, we remove that 
 * here. Adds two more attributes to the shortcode:
 *    class=''      // arbitrary classes to add
 *    style=''      // arbitrary css styles to add
 */
add_filter( 'img_caption_shortcode', 'cleaner_caption', 10, 3 );

function cleaner_caption( $output, $attr, $content ) {

	/* We're not worried abut captions in feeds, so just return the output here. */
	if ( is_feed() )
		return $output;

	/* Set up the default arguments. */
	$defaults = array(
		'id' => '',
		'align' => 'alignnone',
		'width' => '',
		'caption' => '',
    'class' => '',
    'style' => ''
	);

	/* Merge the defaults with user input. */
	$attr = shortcode_atts( $defaults, $attr );

	/* If the width is less than 1 or there is no caption, return the content wrapped between the [caption]< tags. */
	if ( 1 > $attr['width'] || empty( $attr['caption'] ) )
		return $content;

	/* Set up the attributes for the caption <div>. */
	$attributes = ( !empty( $attr['id'] ) ? ' id="' . esc_attr( $attr['id'] ) . '"' : '' );

	$attributes .= ' class="wp-caption ' . esc_attr( $attr['align'] );
  /* add other classes if any */
  $attributes .= ( !empty( $attr['class'] ) ?
                   ' ' . esc_attr( $attr['class'] ) . '"' : '"' );

	$attributes .= ' style="width: ' . esc_attr( $attr['width'] ) . 'px;';
  /* add more styles if any */
  $attributes .= ( !empty( $attr['style'] ) ?
                   ' ' . esc_attr( $attr['style'] ) . '"' : '"' );

	/* Open the caption <div>. */
	$output = '<div' . $attributes .'>';

	/* Allow shortcodes for the content the caption was created for. */
	$output .= do_shortcode( $content );

	/* Append the caption text. */
	$output .= '<p class="wp-caption-text">' . $attr['caption'] . '</p>';

	/* Close the caption </div>. */
	$output .= '</div>';

	/* Return the formatted, clean caption. */
	return $output;
}

/* PhotoSwipe Support */

// require_once('mobile_device_detect.php');
require_once('Mobile_Detect.php');
$detect = new Mobile_Detect();
$is_mobile = $detect->isMobile();
$is_tablet = $detect->isTablet();

/* disable fancybox on mobile */
add_action('wp_head', 'remove_fancybox', 1);
function remove_fancybox() {
  global $is_mobile;
  if ($is_mobile) {
    remove_action('wp_head', 'mfbfw_init'); /* disable fancybox */
    remove_action('init', 'Jetpack_Tiled_Gallery');
  }
}

/* PhotoSwipe: jQuery version used so it works on IE */
add_action('wp_head', 'photoswipe_support');
function photoswipe_support() {
  global $is_mobile;
  if ($is_mobile) {
    $photoswipe_dir = get_stylesheet_directory_uri() . '/photoswipe';
    echo "<link href='$photoswipe_dir/photoswipe.css' type='text/css' rel='stylesheet' />" ;
    wp_enqueue_script("jquery");
    echo "<script type='text/javascript' src='$photoswipe_dir/lib/klass.min.js'></script>";
    echo "<script type='text/javascript' src='$photoswipe_dir/code.photoswipe.jquery-3.0.4.min.js'></script>";
    echo "<script type='text/javascript' src='$photoswipe_dir/../ps.js'></script>";
  }
}

/* Set content_width so "large" image format is the right size */
$content_width = 850;

if(function_exists('twenty_eleven_infinite_scroll_init'))
	add_action( 'init', 'twenty_eleven_infinite_scroll_init'); // function provided by jetpack

?>
