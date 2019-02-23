<?php 

class ect_WPSEO_Frontend {

	var $selectede_lang = '';


	function __construct() {
		global $wpseo_front,$easy_translation_manager_plugin;
		$options = get_wpseo_options();
		$default_languash =  etm_tools_retrive_options('default_language');
		if($easy_translation_manager_plugin->show_lang_status and $easy_translation_manager_plugin->selectede_lang != $default_languash){
			$this->selectede_lang = $easy_translation_manager_plugin->selectede_lang;
		
		}

		if($this->selectede_lang != ''){
			remove_action( 'wp_head', array($wpseo_front, 'head'), 1, 1 );
			remove_filter( 'wp_title', array($wpseo_front, 'title'), 10, 3 );
		
			add_action( 'wp_head', array(&$this, 'head'), 1, 1 );
			add_filter( 'wp_title', array(&$this, 'title'), 10, 3 );
		
			if (isset($options['forcerewritetitle']) && $options['forcerewritetitle']) {
				remove_action('wp_footer', array($wpseo_front, 'flush_cache') );
			
				add_action('wp_footer', array(&$this, 'flush_cache') );			
			}

		}
	}


	
	function title( $title, $sep = '-', $seplocation = '', $postid = '' ) {
		global $wpseo_front;
	
		if ( trim($sep) == '' )
			$sep = '-';
		$sep = ' '.$sep.' ';
		
		global $wp_query;

		if ( is_feed() )
			return $title;
			
		$options = get_wpseo_options();

		if ( $wpseo_front->is_home_static_page() ) {
			global $post;
			$title = wpseo_get_value( 'title_'.$this->selectede_lang, $post->ID );
			if ( '' == $title )
				$title = $this->etm_wpseo_replace_vars($options['title-'.$post->post_type], (array) $post );
		} else if ( $wpseo_front->is_home_posts_page() ) {
			if ( isset($options['title-home']) && $options['title-home'] != '' )
				$title = $this->etm_wpseo_replace_vars( $options['title-home'], array() );
			else {
				$title = get_bloginfo('name');
				if ( $wp_query->query_vars['paged'] )
					$title .= $sep.$wp_query->query_vars['paged'].'/'.$wp_query->max_num_pages;
				$title .= $sep.get_bloginfo('description');
			}
		} else if ( $wpseo_front->is_posts_page() ) {
			$blogpage = get_post( get_option( 'page_for_posts' ) );
			$fixed_title = wpseo_get_value( 'title_'.$this->selectede_lang, $blogpage->ID );
			if ( $fixed_title ) { 
				$title = $this->etm_wpseo_replace_vars( $fixed_title, (array) $blogpage ); 
			} else {
				if (isset($options['title-'.$blogpage->post_type]) && !empty($options['title-'.$blogpage->post_type]) )
					$title = $this->etm_wpseo_replace_vars($options['title-'.$blogpage->post_type], (array) $blogpage );
				else {
					$title = get_bloginfo('name');
					if ( $wp_query->query_vars['paged'] )
						$title .= $sep.$wp_query->query_vars['paged'].'/'.$wp_query->max_num_pages;
					$title .= $sep.get_bloginfo('description');
				}
			}
		} else if ( is_singular() ) {
			global $post;
			if ( empty($post) ) {
				$post = $wp_query->get_queried_object();
			}
			$fixed_title = wpseo_get_value('title_'.$this->selectede_lang);
			if ( $fixed_title ) { 
				$title = $fixed_title; 
			} else {
				if (isset($options['title-'.$post->post_type]) && !empty($options['title-'.$post->post_type]) ) {
					$title = $this->etm_wpseo_replace_vars($options['title-'.$post->post_type], (array) $post );
				} else {
					$title = apply_filters('single_post_title', $title);
					$title = $title.$sep.get_bloginfo('name'); 
				}
			}
		} else if ( is_search() ) {
			if ( isset($options['title-search']) && !empty($options['title-search']) )
				$title = $this->etm_wpseo_replace_vars($options['title-search'], (array) $wp_query->get_queried_object() );	
			else {
				$title = __('Search for "').get_search_query().'"';
				
				if ( isset($wp_query->query_vars['paged']) && $wp_query->query_vars['paged'] )
					$title .= $sep.$wp_query->query_vars['paged'].'/'.$wp_query->max_num_pages;
				$title .= $sep.get_bloginfo('name'); 	
			}
		} else if ( is_category() || is_tag() || is_tax() ) {
			$term = $wp_query->get_queried_object();
			$title = trim( wpseo_get_term_meta( $term, $term->taxonomy, 'title' ) );
			if ( !$title || empty($title) ) {
				if ( isset($options['title-'.$term->taxonomy]) && !empty($options['title-'.$term->taxonomy]) ) {
					$title = $this->etm_wpseo_replace_vars($options['title-'.$term->taxonomy], (array) $term );
				} else {
					if ( is_category() )
						$title = single_cat_title('', false);
					else if ( is_tag() )
						$title = single_tag_title('', false);
					else if ( is_tax() ) {
						if ( function_exists('single_term_title') ) {
							$title = single_term_title('', false);
						} else {
							$term = $wp_query->get_queried_object();
							$title = $term->name;
						}
					} 

					if ( isset($wp_query->query_vars['paged']) && $wp_query->query_vars['paged'] )
						$title .= $sep.$wp_query->query_vars['paged'].'/'.$wp_query->max_num_pages;
					$title .= $sep.get_bloginfo('name'); 
				}
			}
		} else if ( is_author() ) {
			$author_id = get_query_var('author');
			$title = get_the_author_meta('wpseo_title', $author_id);
			if ( empty($title) ) {
				if ( isset($options['title-author']) && !empty($options['title-author']) )
					$title = wpseo_replace_vars($options['title-author'], array() );
				else {
					$title = get_the_author_meta('display_name', $author_id); 
					
					if ( isset($wp_query->query_vars['paged']) && $wp_query->query_vars['paged'] )
						$title .= $sep.$wp_query->query_vars['paged'].'/'.$wp_query->max_num_pages;
					$title .= $sep.get_bloginfo('name'); 		
				}
			}
		} else if ( function_exists('is_post_type_archive') && is_post_type_archive() ) {
			$post_type = get_post_type();
			if ( isset($options['title-ptarchive-'.$post_type]) && '' != $options['title-ptarchive-'.$post_type] ) {
				$title = $options['title-ptarchive-'.$post_type];
			} else {
				$post_type_obj = get_post_type_object( $post_type );
				$title = $post_type_obj->labels->menu_name.$sep.get_bloginfo('name');
			}
		} else if ( is_archive() ) {
		 	if ( isset($options['title-archive']) && !empty($options['title-archive']) )
				$title = wpseo_replace_vars($options['title-archive'], array('post_title' => $title) );
			else {
				if ( is_month() )
					$title = single_month_title(' ', false).' '.__('Archives'); 
				else if ( is_year() )
					$title = get_query_var('year').' '.__('Archives'); 
					
				if ( isset($wp_query->query_vars['paged']) && $wp_query->query_vars['paged'] )
					$title .= $sep.$wp_query->query_vars['paged'].'/'.$wp_query->max_num_pages;
				$title .= $sep.get_bloginfo('name');
			}
		} else if ( is_404() ) {
		 	if ( isset($options['title-404']) && !empty($options['title-404']) )
				$title = wpseo_replace_vars($options['title-404'], array('post_title' => $title) );
			else
				$title = __('Page not found').$sep.get_bloginfo('name');
		} 
		return esc_html( strip_tags( stripslashes( apply_filters( 'wpseo_title', $title ) ) ) );
	}
	
	
	function head() {
		$options = get_wpseo_options();
		global $wp_query,$wpseo_front;
		
		$wpseo_front->promo();
		$this->metadesc();
		$this->metakeywords();
		$wpseo_front->canonical();
		$wpseo_front->adjacent_rel_links();
		$wpseo_front->robots();
		
		if ( is_front_page() ) {
			if (!empty($options['googleverify'])) {
				$google_meta = $options['googleverify'];
				if ( strpos($google_meta, 'content') ) {
					preg_match('/content="([^"]+)"/', $google_meta, $match);
					$google_meta = $match[1];
				}
				echo "<meta name=\"google-site-verification\" content=\"$google_meta\" />\n";
			}
				
			if (!empty($options['msverify'])) {
				$bing_meta = $options['msverify'];
				if ( strpos($bing_meta, 'content') ) {
					preg_match('/content="([^"]+)"/', $bing_meta, $match);
					$bing_meta = $match[1];
				}								
				echo "<meta name=\"msvalidate.01\" content=\"$bing_meta\" />\n";
			}
			
			if (!empty($options['alexaverify'])) {
				echo "<meta name=\"alexaVerifyID\" content=\"".esc_attr($options['alexaverify'])."\" />\n";
			}	
		}

		do_action( 'wpseo_head' );
		
		echo "<!-- / Yoast WordPress SEO plugin. -->\n\n";
	}

	function metakeywords() {
		global $wp_query,$wpseo_front;
		
		$options = get_wpseo_options();
		if ( !isset( $options['usemetakeywords'] ) || !$options['usemetakeywords'] )
			return;

		$metakey = '';
		
		if ( is_singular() ) { 
			global $post;
			$metakey = wpseo_get_value('focuskw_'.$this->selectede_lang);
			if ( !$metakey || empty( $metakey ) && isset( $options['metakey-'.$post->post_type] ) ) {
				$metakey = $this->etm_wpseo_replace_vars($options['metakey-'.$post->post_type], (array) $post );
			}
		} else {
			if ( $wpseo_front->is_home_posts_page() && isset($options['metakey-home']) ) {
				$metakey = $this->etm_wpseo_replace_vars($options['metakey-home'], array() );
			} else if ( $this->is_home_static_page() ) {
				global $post;
				$metakey = wpseo_get_value('focuskw_'.$this->selectede_lang);
				if ( ($metakey == '' || !$metakey) && isset($options['metakey-'.$post->post_type]) )
					$metakey = $this->etm_wpseo_replace_vars($options['metakey-'.$post->post_type], (array) $post );
			} else if ( is_category() || is_tag() || is_tax() ) {
				$term = $wp_query->get_queried_object();

				$metakey = wpseo_get_term_meta( $term, $term->taxonomy, 'metakey' );
				if ( !$metakey && isset($options['metakey-'.$term->taxonomy]))
					$metakey = wpseo_replace_vars($options['metakey-'.$term->taxonomy], (array) $term );
			} else if ( is_author() ) {
				$author_id = get_query_var('author');
				$metakey = get_the_author_meta('metakey', $author_id);
				if ( !$metakey && isset($options['metakey-author']) )
					$metakey = wpseo_replace_vars($options['metakey-author'], (array) $wp_query->get_queried_object() );
			} 
			
		}

		$metakey = trim( $metakey );
		if ( !empty( $metakey ) ) 
			echo '<meta name="keywords" content="'.esc_attr( strip_tags( stripslashes( $metakey ) ) ).'"/>'."\n";

	}
	
	function metadesc( $echo = true ) {
		if ( get_query_var('paged') && get_query_var('paged') > 1 )
			return;
			
		global $post, $wp_query,$wpseo_front;
		$options = get_wpseo_options();

		$metadesc = '';
		if (is_singular()) { 
			$metadesc = wpseo_get_value('metadesc_'.$this->selectede_lang);
			if ($metadesc == '' || !$metadesc) {
				if ( isset($options['metadesc-'.$post->post_type]) && $options['metadesc-'.$post->post_type] != '' )
					$metadesc = $this->etm_wpseo_replace_vars($options['metadesc-'.$post->post_type], (array) $post );
			}
		} else {
			if ( is_search() ) {
				$metadesc = '';
			} else if  ( $wpseo_front->is_home_posts_page() && isset($options['metadesc-home']) ) {
				$metadesc = $this->etm_wpseo_replace_vars($options['metadesc-home'], array() );
			} else if  ( $wpseo_front->is_posts_page() ) {
				$metadesc = $this->etm_wpseo_replace_vars('metadesc_'.$this->selectede_lang, get_option('page_for_posts') );
				if ( ($metadesc == '' || !$metadesc) && isset( $options['metadesc-'.$page->post_type] ) ) {
					$page = get_post( get_option('page_for_posts') );
					$metadesc = $this->etm_wpseo_replace_vars( $options['metadesc-'.$page->post_type], (array) $page );
				}
			} else if ( $wpseo_front->is_home_static_page() ) {
				global $post;
				$metadesc = wpseo_get_value('metadesc_'.$this->selectede_lang);
				if ( ($metadesc == '' || !$metadesc) && isset($options['metadesc-'.$post->post_type]) )
					$metadesc = $this->etm_wpseo_replace_vars($options['metadesc-'.$post->post_type], (array) $post );
			} else if ( is_category() || is_tag() || is_tax() ) {
				$term = $wp_query->get_queried_object();

				$metadesc = wpseo_get_term_meta( $term, $term->taxonomy, 'desc' );
				if ( !$metadesc && isset($options['metadesc-'.$term->taxonomy]))
					$metadesc = wpseo_replace_vars($options['metadesc-'.$term->taxonomy], (array) $term );
			} else if ( is_author() ) {
				$author_id = get_query_var('author');
				$metadesc = get_the_author_meta('wpseo_metadesc', $author_id);
				if ( !$metadesc && isset($options['metadesc-author']))
					$metadesc = wpseo_replace_vars($options['metadesc-author'], (array) $wp_query->get_queried_object() );
			} else if ( function_exists('is_post_type_archive') && is_post_type_archive() ) {
				$post_type = get_post_type();
				if ( isset($options['metadesc-ptarchive-'.$post_type]) && '' != $options['metadesc-ptarchive-'.$post_type] ) {
					$metadesc = $options['metadesc-ptarchive-'.$post_type];
				} 
			}
		}
	
		$metadesc = apply_filters( 'wpseo_metadesc', trim( $metadesc ) );
		
		if ( $echo ) {
			if ( !empty( $metadesc ) )
				echo '<meta name="description" content="'.esc_attr( strip_tags( stripslashes( $metadesc ) ) ).'"/>'."\n";
			else if ( current_user_can('manage_options') && is_singular() )
				echo '<!-- '.__( 'Admin only notice: this page doesn\'t show a meta description because it doesn\'t have one, either write it for this page specifically or go into the SEO -> Titles menu and set up a template.', 'wordpress-seo' ).' -->'."\n";			
		} else {
			return $metadesc;
		}
		
	}
	

	function flush_cache() {
		global $wp_query, $post, $wpseo_ob;

		if ( !$wpseo_ob )
			return;
			
		$content = ob_get_contents();
		$title = $this->title( '' );
		
		$content = preg_replace('/<title>(.*)<\/title>/','<title>'.$title.'</title>', $content);
		ob_end_clean();
		echo $content;
	}
	
	function etm_wpseo_replace_vars($string, $args, $omit = array() ) {
	
		$args = (array) $args;
		$string = strip_tags( $string );

		if ( strpos( $string, '%%' ) === false )
			return trim( preg_replace('/\s+/',' ', $string) );

		$simple_replacements = array(
			'%%sitename%%'				=> get_bloginfo('name'),
			'%%sitedesc%%'				=> get_bloginfo('description'),
			'%%currenttime%%'			=> date('H:i'),
			'%%currentdate%%'			=> date('M jS Y'),
			'%%currentmonth%%'			=> date('F'),
			'%%currentyear%%'			=> date('Y'),
		);

		foreach ($simple_replacements as $var => $repl) {
			$string = str_replace($var, $repl, $string);
		}
	
		// Let's see if we can bail early.
		if ( strpos( $string, '%%' ) === false )
			return trim( preg_replace('/\s+/',' ', $string) );

		global $wp_query;
	
		$defaults = array(
			'ID' => '',
			'name' => '',
			'post_author' => '',
			'post_content' => '',
			'post_date' => '',
			'post_content' => '',
			'post_excerpt' => '',
			'post_modified' => '',
			'post_title' => '',
			'taxonomy' => '',
			'term_id' => '',
		);
	
		$pagenum = get_query_var('paged');
		if ($pagenum === 0) {
			if ($wp_query->max_num_pages > 1)
				$pagenum = 1;
			else
				$pagenum = '';
		}
	
		if ( isset( $args['post_content'] ) )
			$args['post_content'] = wpseo_strip_shortcode( $args['post_content'] );
		if ( isset( $args['post_excerpt'] ) )
			$args['post_excerpt'] = wpseo_strip_shortcode( $args['post_excerpt'] );
		
		$r = (object) wp_parse_args($args, $defaults);

		// Only global $post on single's, otherwise some expressions will return wrong results.
		if ( is_singular() || ( is_front_page() && 'posts' != get_option('show_on_front') ) ) {
			global $post;
		}
	
		// Let's do date first as it's a bit more work to get right.
		if ( $r->post_date != '' ) {
			$date = mysql2date( get_option('date_format'), $r->post_date );
		} else {
			if ( get_query_var('day') && get_query_var('day') != '' ) {
				$date = get_the_date();
			} else {
				if ( single_month_title(' ', false) && single_month_title(' ', false) != '' ) {
					$date = single_month_title(' ', false);
				} else if ( get_query_var('year') != '' ){
					$date = get_query_var('year');
				} else {
					$date = '';
				}
			}
		}
	
		$translatede_title = get_post_meta($r->ID, 'ect_tran_title_'.$this->selectede_lang, true);

		if(empty($translatede_title)){
			$translatede_title = $r->post_title ;
		}
	
	
		$replacements = array(
			'%%date%%'					=> $date,
			'%%title%%'					=> stripslashes( $translatede_title ),
			'%%excerpt%%'				=> ( !empty($r->post_excerpt) ) ? strip_tags( $r->post_excerpt ) : substr( strip_shortcodes( strip_tags( $r->post_content ) ), 0, 155 ),
			'%%excerpt_only%%'			=> strip_tags( $r->post_excerpt ),
			'%%category%%'				=> $this->etm_wpseo_get_terms($r->ID, 'category'),
			'%%category_description%%'	=> !empty($r->taxonomy) ? trim(strip_tags(get_term_field( 'description', $r->term_id, $r->taxonomy ))) : '',
			'%%tag_description%%'		=> !empty($r->taxonomy) ? trim(strip_tags(get_term_field( 'description', $r->term_id, $r->taxonomy ))) : '',
			'%%term_description%%'		=> !empty($r->taxonomy) ? trim(strip_tags(get_term_field( 'description', $r->term_id, $r->taxonomy ))) : '',
			'%%term_title%%'			=> $r->name,
			'%%focuskw%%'				=> wpseo_get_value('focuskw_'.$this->selectede_lang, $r->ID),
			'%%tag%%'					=> $this->etm_wpseo_get_terms($r->ID, 'post_tag'),
			'%%modified%%'				=> mysql2date( get_option('date_format'), $r->post_modified ),
			'%%id%%'					=> $r->ID,
			'%%name%%'					=> get_the_author_meta('display_name', !empty($r->post_author) ? $r->post_author : get_query_var('author')),
			'%%userid%%'				=> !empty($r->post_author) ? $r->post_author : get_query_var('author'),
			'%%searchphrase%%'			=> esc_html(get_query_var('s')),
			'%%page%%'		 			=> ( get_query_var('paged') != 0 ) ? 'Page '.get_query_var('paged').' of '.$wp_query->max_num_pages : '', 
			'%%pagetotal%%'	 			=> ( $wp_query->max_num_pages > 1 ) ? $wp_query->max_num_pages : '', 
			'%%pagenumber%%' 			=> $pagenum,
			'%%caption%%'				=> $r->post_excerpt,
		);
	
		foreach ($replacements as $var => $repl) {
			if ( !in_array($var, $omit) )
				$string = str_replace($var, $repl, $string);
		}
	
		if ( strpos( $string, '%%' ) === false ) {
			$string = preg_replace( '/\s\s+/',' ', $string );
			return trim( $string );
		}

		if ( preg_match_all( '/%%cf_([^%]+)%%/', $string, $matches, PREG_SET_ORDER ) ) {
			global $post;
			foreach ($matches as $match) {
				$string = str_replace( $match[0], get_post_meta( $post->ID, $match[1], true), $string );
			}
		}
	
		$string = preg_replace( '/\s\s+/',' ', $string );
		return trim( $string );
	}
	
	function etm_wpseo_get_terms($id, $taxonomy) {
		// If we're on a specific tag, category or taxonomy page, return that and bail.
		global $easy_translation_manager_plugin;
		$load_translatede_data = $easy_translation_manager_plugin->tran_terms_data;
		
		if ( is_category() || is_tag() || is_tax() ) {
			global $wp_query;
			$term = $wp_query->get_queried_object();
			
			if(!empty($load_translatede_data[$term->term_id])){
				return $load_translatede_data[$term->term_id];
			} else {
				return $term->name;
			}	
		}
	
		$output = '';
		$terms = get_the_terms($id, $taxonomy);
		
		
		if ( $terms ) {
			foreach ($terms as $term) {
				if(!empty($load_translatede_data[$term->term_id])){
					$output .= $load_translatede_data[$term->term_id].', ';
				} else {
					$output .= $term->name.', ';
				}	
			}
			return rtrim( trim($output), ',' );
		}
		return '';
	}
}

$ect_wpseo_front = new ect_WPSEO_Frontend;
