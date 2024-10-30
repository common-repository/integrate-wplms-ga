<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class WPLMS_GA {

	public static $instance;
    
    public static function init(){
        if ( is_null( self::$instance ) )
            self::$instance = new WPLMS_GA;
        return self::$instance;
    }

	private function __construct(){

		add_action('wp_head',array($this,'ga_head'));
		add_action('wp_footer',array($this,'ga_footer'));

		add_action('wplms_unit_header',array($this,'unit_quiz_tracking'),10,2);
		add_action('wp_ajax_quiz_question',array($this,'track_question'));

		add_filter('wplms_take_this_course_button_label',array($this,'trigger_take_this_course_event'),10,2);
		add_filter('lms_general_settings',array($this,'wplms_get_ga_user_tracking_id'),10,1);
	}

	function ga_account_id(){

		if(class_exists('WPLMS_tips')){
			$tips = WPLMS_tips::init();
			if(isset($tips) && isset($tips->settings) && isset($tips->settings['wplms_ga_tracking_id'])){
				return $tips->settings['wplms_ga_tracking_id'];
			}
		}
		return '';
	}

	function ga_head(){
		
		//Check if wplms theme is active
		if(!function_exists('vibe_get_option'))
			return;
		
		$ga_id = $this->ga_account_id();
		if(empty($ga_id))
			return;
		?>
		<script>
			window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
			ga('create', '<?php echo $ga_id ?>', 'none','wplms');
			ga('wplms.send', 'pageview');
		</script>
		<?php
	}

	function ga_footer(){

		//Check if wplms theme is active
		if(!function_exists('vibe_get_option'))
			return;
		?>
		<script async src='https://www.google-analytics.com/analytics.js'></script>
		<?php
	}

	function unit_quiz_tracking($id,$course_id){

		//Check if wplms theme is active
		if(!function_exists('vibe_get_option'))
			return;

		$course_status = vibe_get_option('take_course_page');
		if(function_exists('icl_object_id')){
			$course_status = icl_object_id($course_status);
		}

		$course_slug = get_post_field('post_name',$course_id);
		$slug = get_post_field('post_name',$course_status);

		$site_link = home_url();
		$permalink = get_permalink($id);
		$ref = str_replace($site_link,'',$permalink);
		?>
		<script>
			ga('wplms.send', 'pageview', '<?php echo '/'.$course_slug.'/'.$slug.$ref; ?>');
		</script>
		<?php
	}

	function track_question(){

		$quiz_id = $_POST['quiz_id'];
        $ques_id = $_POST['ques_id'];

		$slug = get_post_field('post_name',$quiz_id);
		$site_link = home_url();
		$permalink = get_permalink($ques_id);
		$ref = str_replace($site_link,'',$permalink);
		?>
		<script>
			ga('wplms.send', 'pageview', '<?php echo '/'.$slug.$ref; ?>');
		</script>
		<?php
	}


	function trigger_take_this_course_event($label,$course_id){
		?>
		<script>
		jQuery(document).ready(function($){
			$('.course_button').on('click',function(){
				ga('wplms.send', 'event', 'take_course', '<?php echo get_the_title($course_id); ?>', '<?php echo $label; ?>', 50);
			});
		});
		</script>
		<?php
		return $label;
	}

	function wplms_get_ga_user_tracking_id($settings){
		$settings[] = array(
			'label'=>__('Google Analytics Settings','wplms-ga' ),
			'type'=> 'heading',
		);

		$settings[] = array(
	            'label' => __('Add G.A. Tracking ID','wplms-ga'),
	            'name' => 'wplms_ga_tracking_id',
	            'desc' => __('Add your Google Analytic tracking ID here.','wplms-ga'),
	            'type' => 'text',
			);
		return $settings;
	}
}

WPLMS_GA::init();
