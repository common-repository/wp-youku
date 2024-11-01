<?php
defined( 'ABSPATH' ) or exit;

add_action('init','cwp_youku_shortcode_init');

function cwp_youku_shortcode_init() {
	add_shortcode( 'youku', 'cwp_wp_youku_callback' );
	add_shortcode( 'video', 'cwp_wp_youku_callback2' );
}

function cwp_wp_youku_callback( $atts,$content ) {

		$default_w=cwp_get_option('wp_youku_basics','youku_w','600');
		$default_h=cwp_get_option('wp_youku_basics','youku_h','480');
		$content=trim($content);

		extract( shortcode_atts( array(
			'id' => '',
			'w' => $default_w,
			'h' => $default_h,
		), $atts ) );
		if($content){
				wp_youku_output($content,$id,$w,$h);
		}else{
			wp_youku_output($w,$h,$content,$id);
		}
}

function cwp_wp_youku_callback2( $atts,$content ) {


		$default_w=cwp_get_option('wp_youku_basics','youku_w','600');
		$default_h=cwp_get_option('wp_youku_basics','youku_h','480');
		$content=trim($content);

	extract( shortcode_atts( array(
		'width' => '500',
		'height' => '400',
	), $atts ) );

	global $wpjam_video_id;
	if($wpjam_video_id){
		$wpjam_video_id++;
	}else{
		$wpjam_video_id = 1;
	}
		if($content)
				wp_youku_output($width,$height,$content);

}

function wp_youku_output($w, $h, $content=null, $id=null ) {

	$w=$w?$w:cwp_get_option('wp_youku_basics','youku_w','600');
	$h=$h?$h:cwp_get_option('wp_youku_basics','youku_h','480');
	$youku_video_output = null;
	if($content){
		$preg_match_result = preg_match('#http://v.youku.com/v_show/id_(.*?).html#i',$content,$matches);
		$id=$matches[1];
	}elseif($id){
		$id=trim($id);
	}

	$ipad_height = $h * 0.9;
		$youku_video_output = '
			<p id="cwp_video_'.$id.'" style="text-align: center;"><embed src="http://player.youku.com/player.php/sid/'.$id.'/v.swf" allowFullScreen="true" quality="high" width="'.$w.'" height="'.$h.'" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash"  wmode="transparent"></embed></p>
			<script type="text/javascript">
				var ua = navigator.userAgent.toLowerCase();
				if(ua.match(/ipad/i)){
					document.getElementById(\'cwp_video_'.$id.'\').innerHTML=\'<video id="youku-html5-player-video" width="'.$w.'" height="'.$ipad_height.'" controls="" autoplay="" preload="" src="http://v.youku.com/player/getRealM3U8/vid/'.$id.'/type/mp4/v.m3u8"></video>\';
				}else if(ua.match(/iphone/i)){
					document.getElementById(\'cwp_video_'.$id.'\').innerHTML=\'<video id="youku-html5-player-video" width="'.$w.'" height="'.$h.'" controls="" autoplay="" preload="" src="http://v.youku.com/player/getRealM3U8/vid/'.$id.'/type/mp4/v.m3u8"></video>\';
				}
			</script>';
	echo $youku_video_output;
}



/*function wp_youku_output_2($content=null,$width,$height){
if(!$content) return;

}*/
///////////////////
