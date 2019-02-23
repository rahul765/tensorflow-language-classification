<?php

class ect_WPSEO_Frontend {

	var $selectede_lang = '';

	function __construct() {
		global $ect_wpseo_front,$easy_translation_manager_plugin;
		$options = get_wpseo_options();
		$default_languash =  etm_tools_retrive_options('default_language');
		if($easy_translation_manager_plugin->show_lang_status and $easy_translation_manager_plugin->selectede_lang != $default_languash){
			$this->selectede_lang = $easy_translation_manager_plugin->selectede_lang;
		
		}
		
		if($this->selectede_lang != ''){
			add_action( 'wp_head', array( $this, 'head' ), 20, 1 );
			add_filter( 'wp_title', array( $this, 'title' ), 20, 3 );
			add_filter( 'thematic_doctitle', array( $this, 'title' ), 20 );
		}
	}

	function is_home_posts_page() {
		return ( is_home() && 'page' != get_option( 'show_on_front' ) );
	}

	function is_home_static_page() {
		return ( is_front_page() && 'page' == get_option( 'show_on_front' ) && is_page( get_option( 'page_on_front' ) ) );
	}

	function is_posts_page() {
		return ( is_home() && 'page' == get_option( 'show_on_front' ) );
	}

	function get_author_title() {
		$author_id = get_query_var( 'author' );
		$title     = get_the_author_meta( 'wpseo_title', $author_id );

		if ( !empty( $title ) )
			return wpseo_replace_vars( $title, array() );

		return $this->get_title_from_options( 'title-author' );
	}

	function get_title_from_options( $index, $var_source = array() ) {
		$options = get_wpseo_options();

		if ( !isset( $options[$index] ) || empty( $options[$index] ) ) {
			if ( is_singular() )
				return wpseo_replace_vars( '%%title%% %%sep%% %%sitename%%', (array) $var_source );
			else
				return '';
		}

		return wpseo_replace_vars( $options[$index], (array) $var_source );
	}


	function get_default_title( $sep, $seplocation, $title = '' ) {
		if ( 'right' == $seplocation )
			$regex = '/\s*' . preg_quote( trim( $sep ), '/' ) . '\s*/';
		else
			$regex = '/^\s*' . preg_quote( trim( $sep ), '/' ) . '\s*/';
		$title = preg_replace( $regex, '', $title );

		if ( empty( $title ) ) {
			$title = get_bloginfo( 'name' );
			$title = $this->add_paging_to_title( $sep, $seplocation, $title );
			$title = $this->add_to_title( $sep, $seplocation, $title, get_bloginfo( 'description' ) );
			return $title;
		}

		$title = $this->add_paging_to_title( $sep, $seplocation, $title );
		$title = $this->add_to_title( $sep, $seplocation, $title, get_bloginfo( 'name' ) );
		return $title;
	}

	function add_paging_to_title( $sep, $seplocation, $title ) {
		global $wp_query;

		if ( !empty( $wp_query->query_vars['paged'] ) && $wp_query->query_vars['paged'] > 1 )
			return $this->add_to_title( $sep, $seplocation, $title, $wp_query->query_vars['paged'] . '/' . $wp_query->max_num_pages );

		return $title;
	}

	function add_to_title( $sep, $seplocation, $title, $title_part ) {
		if ( 'right' == $seplocation )
			return $title . $sep . $title_part;
		return $title_part . $sep . $title;
	}

	function title( $title, $sepinput = '-', $seplocation = '' ) {
		global $sep,$ect_wpseo_front,$wp_query;
		$sep = $sepinput;
		
		$postid = get_the_ID(); 
		$translatede_title = get_post_meta($postid, '_yoast_wpseo_title_'.$this->selectede_lang, true);
	
		if(!empty($translatede_title)){
			$title = $translatede_title;
		}
		
		
		
		if ( is_feed() )
			return $title;

		$original_title = $title;
		
		if ( '' == trim( $sep ) && '' == $seplocation ) {
			$sep         = '-';
			$seplocation = 'right';
		} else if ( '' == $seplocation )
			$seplocation = ( is_rtl() ) ? 'left' : 'right';

		$sep = ' ' . trim( $sep ) . ' ';

		$modified_title = true;

		$title_part = '';

		$options = get_wpseo_options();

		if ( $this->is_home_static_page() ) {
			global $post;
			$title = wpseo_get_value( 'title_'.$this->selectede_lang, $post->ID );
			if ( '' == $title )
				$title = $this->etm_wpseo_replace_vars($options['title-'.$post->post_type], (array) $post );
		} else if ( $this->is_home_posts_page() ) {
			if ( isset($options['title-home']) && $options['title-home'] != '' )
				$title = $this->etm_wpseo_replace_vars( $options['title-home'], array() );
			else {
				$title = get_bloginfo('name');
				if ( $wp_query->query_vars['paged'] )
					$title .= $sep.$wp_query->query_vars['paged'].'/'.$wp_query->max_num_pages;
				$title .= $sep.get_bloginfo('description');
			}
		} else if ( $this->is_posts_page() ) {
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
			$title = $this->get_title_from_options( 'title-search' );

			if ( empty( $title ) )
				$title_part = sprintf( __( 'Search for "%s"', 'wordpress-seo' ), get_search_query() );
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
			$title = $this->get_author_title();

			if ( empty( $title ) )
				$title_part = get_the_author_meta( 'display_name', get_query_var( 'author' ) );
		} else if ( function_exists( 'is_post_type_archive' ) && is_post_type_archive() ) {
			$post_type = get_post_type();
			$title     = $this->get_title_from_options( 'title-ptarchive-' . $post_type );

			if ( empty( $title ) ) {
				$post_type_obj = get_post_type_object( $post_type );
				if ( isset( $post_type_obj->labels->menu_name ) )
					$title_part = $post_type_obj->labels->menu_name;
				else
					$title_part = $post_type_obj->name;
			}
		} else if ( is_archive() ) {
			$title = $this->get_title_from_options( 'title-archive' );

			if ( empty( $title ) ) {
				if ( is_month() )
					$title_part = sprintf( __( '%s Archives', 'wordpress-seo' ), single_month_title( ' ', false ) );
				else if ( is_year() )
					$title_part = sprintf( __( '%s Archives', 'wordpress-seo' ), get_query_var( 'year' ) );
				else if ( is_day() )
					$title_part = sprintf( __( '%s Archives', 'wordpress-seo' ), get_the_date() );
				else
					$title_part = __( 'Archives', 'wordpress-seo' );
			}
		} else if ( is_404() ) {
			$title = $this->get_title_from_options( 'title-404' );

			if ( empty( $title ) )
				$title_part = __( 'Page not found', 'wordpress-seo' );
		} else {
			$modified_title = false;
		}

		if ( ( $modified_title && empty( $title ) ) || !empty( $title_part ) )
			$title = $this->get_default_title( $sep, $seplocation, $title_part );


		if(!empty($translatede_title)){
			$title = $translatede_title;
		}
		
		return esc_html( strip_tags( stripslashes( apply_filters( 'wpseo_title', $title ) ) ) );
	}

	function head() {
		wp_reset_query();
		$this->metadesc();
		$this->metakeywords();
		$this->author();
		return;
	}


	function author() {
		$gplus   = false;
		$options = get_wpseo_options();

		if ( is_singular() ) {
			global $post;
			$gplus = get_the_author_meta( 'googleplus', $post->post_author );
		} else if ( is_home() ) {
			if ( isset( $options['plus-author'] ) )
				$gplus = get_the_author_meta( 'googleplus', $options['plus-author'] );
		}

		$gplus = apply_filters( 'wpseo_author_link', $gplus );

		if ( $gplus )
			echo '<link rel="author" href="' . $gplus . '"/>' . "\n";

		if ( is_front_page() && isset( $options['plus-publisher'] ) && !empty( $options['plus-publisher'] ) ) {
			echo '<link rel="publisher" href="' . esc_attr( $options['plus-publisher'] ) . '"/>' . "\n";
		}
	}

	function metakeywords() {
		global $wp_query,$ect_wpseo_front;
		
		$options = get_wpseo_options();
		if ( !isset( $options['usemetakeywords'] ) || !$options['usemetakeywords'] )
			return;

		$metakey = '';
		$postid = get_the_ID(); 
		$translatede_metakey = get_post_meta($postid, '_yoast_wpseo_focuskw_'.$this->selectede_lang, true);
		if(!empty($translatede_metakey)){
			$metakey = $translatede_metakey;
		}
		
		if ( is_singular() ) { 
			global $post;
			$metakey = wpseo_get_value('focuskw_'.$this->selectede_lang);
			if ( !$metakey || empty( $metakey ) && isset( $options['metakey-'.$post->post_type] ) ) {
				$metakey = $this->etm_wpseo_replace_vars($options['metakey-'.$post->post_type], (array) $post );
			}
		} else {
			if ( $ect_wpseo_front->is_home_posts_page() && isset($options['metakey-home']) ) {
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

		if(!empty($translatede_metakey)){
			$metakey = $translatede_metakey;
		}
		
		

		$metakey = trim( $metakey );
		if ( !empty( $metakey ) ) 
			echo '<meta name="keywords" content="'.esc_attr( strip_tags( stripslashes( $metakey ) ) ).'"/>'."\n";

	}

	function metadesc( $echo = true ) {
		if ( get_query_var('paged') && get_query_var('paged') > 1 )
			return;
			
		global $post, $wp_query,$ect_wpseo_front;
		$options = get_wpseo_options();

		$metadesc = '';
		
		$postid = get_the_ID(); 
		$translatede_metadesc = get_post_meta($postid, '_yoast_wpseo_metadesc_'.$this->selectede_lang, true);
		if(!empty($translatede_metadesc)){
			$metadesc = $translatede_metadesc;
		}
		
		
		
		if (is_singular()) { 
			$metadesc = wpseo_get_value('metadesc_'.$this->selectede_lang);
			if ($metadesc == '' || !$metadesc) {
				if ( isset($options['metadesc-'.$post->post_type]) && $options['metadesc-'.$post->post_type] != '' )
					$metadesc = $this->etm_wpseo_replace_vars($options['metadesc-'.$post->post_type], (array) $post );
			}
		} else {
			if ( is_search() ) {
				$metadesc = '';
			} else if  ( $ect_wpseo_front->is_home_posts_page() && isset($options['metadesc-home']) ) {
				$metadesc = $this->etm_wpseo_replace_vars($options['metadesc-home'], array() );
			} else if  ( $ect_wpseo_front->is_posts_page() ) {
				$metadesc = $this->etm_wpseo_replace_vars('metadesc_'.$this->selectede_lang, get_option('page_for_posts') );
				if ( ($metadesc == '' || !$metadesc) && isset( $options['metadesc-'.$page->post_type] ) ) {
					$page = get_post( get_option('page_for_posts') );
					$metadesc = $this->etm_wpseo_replace_vars( $options['metadesc-'.$page->post_type], (array) $page );
				}
			} else if ( $ect_wpseo_front->is_home_static_page() ) {
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
		
		if(!empty($translatede_metadesc)){
			$metadesc = $translatede_metadesc;
		}
		$metadesc = apply_filters( 'wpseo_metadesc', trim( $metadesc ) );
		
		if(!empty($translatede_metadesc)){
			$metadesc = $translatede_metadesc;
		}
		
		if ( $echo ) {
			if ( !empty( $metadesc ) )
				echo '<meta name="description" content="'.esc_attr( strip_tags( stripslashes( $metadesc ) ) ).'"/>'."\n";
			else if ( current_user_can('manage_options') && is_singular() )
				echo '<!-- '.__( 'Admin only notice: this page doesn\'t show a meta description because it doesn\'t have one, either write it for this page specifically or go into the SEO -> Titles menu and set up a template.', 'wordpress-seo' ).' -->'."\n";			
		} else {
			return $metadesc;
		}
		
	}

	
	function etm_wpseo_replace_vars($string, $args, $omit = array() ) {
	
		$args = (array) $args;
	
		$string = strip_tags( $string );
	
		// Let's see if we can bail super early.
		if ( strpos( $string, '%%' ) === false )
			return trim( preg_replace( '/\s+/u', ' ', $string ) );
	
		global $sep;
		if ( !isset( $sep ) || empty( $sep ) )
			$sep = '-';
	
		$simple_replacements = array(
			'%%sep%%'                     => $sep,
			'%%sitename%%'                => get_bloginfo( 'name' ),
			'%%sitedesc%%'                => get_bloginfo( 'description' ),
			'%%currenttime%%'             => date( 'H:i' ),
			'%%currentdate%%'             => date( 'M jS Y' ),
			'%%currentmonth%%'            => date( 'F' ),
			'%%currentyear%%'             => date( 'Y' ),
		);
	
		foreach ( $simple_replacements as $var => $repl ) {
			$string = str_replace( $var, $repl, $string );
		}
	
		// Let's see if we can bail early.
		if ( strpos( $string, '%%' ) === false )
			return trim( preg_replace( '/\s+/u', ' ', $string ) );
	
		global $wp_query;
	
		$defaults = array(
			'ID'            => '',
			'name'          => '',
			'post_author'   => '',
			'post_content'  => '',
			'post_date'     => '',
			'post_excerpt'  => '',
			'post_modified' => '',
			'post_title'    => '',
			'taxonomy'      => '',
			'term_id'       => '',
		);
	
		if ( isset( $args['post_content'] ) )
			$args['post_content'] = wpseo_strip_shortcode( $args['post_content'] );
		if ( isset( $args['post_excerpt'] ) )
			$args['post_excerpt'] = wpseo_strip_shortcode( $args['post_excerpt'] );
	
		$r = (object) wp_parse_args( $args, $defaults );
	
		$max_num_pages = 1;
		if ( !is_single() ) {
			$pagenum = get_query_var( 'paged' );
			if ( $pagenum === 0 )
				$pagenum = 1;
	
			if ( isset( $wp_query->max_num_pages ) && $wp_query->max_num_pages != '' && $wp_query->max_num_pages != 0 )
				$max_num_pages = $wp_query->max_num_pages;
		} else {
			global $post;
			$pagenum       = get_query_var( 'page' );
			$max_num_pages = ( isset( $post->post_content ) ) ? substr_count( $post->post_content, '<!--nextpage-->' ) : 1;
			if ( $max_num_pages >= 1 )
				$max_num_pages++;
		}
	
		// Let's do date first as it's a bit more work to get right.
		if ( $r->post_date != '' ) {
			$date = mysql2date( get_option( 'date_format' ), $r->post_date );
		} else {
			if ( get_query_var( 'day' ) && get_query_var( 'day' ) != '' ) {
				$date = get_the_date();
			} else {
				if ( single_month_title( ' ', false ) && single_month_title( ' ', false ) != '' ) {
					$date = single_month_title( ' ', false );
				} else if ( get_query_var( 'year' ) != '' ) {
					$date = get_query_var( 'year' );
				} else {
					$date = '';
				}
			}
		}
	
		$replacements = array(
			'%%date%%'                      => $date,
			'%%searchphrase%%'              => esc_html( get_query_var( 's' ) ),
			'%%page%%'                      => ( $max_num_pages > 1 && $pagenum > 1 ) ? sprintf( $sep . ' ' . __( 'Page %d of %d', 'wordpress-seo' ), $pagenum, $max_num_pages ) : '',
			'%%pagetotal%%'                 => $max_num_pages,
			'%%pagenumber%%'                => $pagenum,
		);
	
		if ( isset( $r->ID ) ) {
		
			$translatede_title = get_post_meta($r->ID, 'ect_tran_title_'.$this->selectede_lang, true);
	
			if(empty($translatede_title)){
				$translatede_title = $r->post_title ;
			}
		
		
		
			$replacements = array_merge( $replacements, array(
				'%%caption%%'                   => $r->post_excerpt,
				'%%category%%'                  => wpseo_get_terms( $r->ID, 'category' ),
				'%%excerpt%%'                   => ( !empty( $r->post_excerpt ) ) ? strip_tags( $r->post_excerpt ) : utf8_encode( substr( strip_shortcodes( strip_tags( utf8_decode( $r->post_content ) ) ), 0, 155 ) ),
				'%%excerpt_only%%'              => strip_tags( $r->post_excerpt ),
				'%%focuskw%%'                   => wpseo_get_value( 'focuskw', $r->ID ),
				'%%id%%'                        => $r->ID,
				'%%modified%%'                  => mysql2date( get_option( 'date_format' ), $r->post_modified ),
				'%%name%%'                      => get_the_author_meta( 'display_name', !empty( $r->post_author ) ? $r->post_author : get_query_var( 'author' ) ),
				'%%tag%%'                       => wpseo_get_terms( $r->ID, 'post_tag' ),
				'%%title%%'                     => stripslashes( $translatede_title ),
				'%%userid%%'                    => !empty( $r->post_author ) ? $r->post_author : get_query_var( 'author' ),
			) );
		}
	
		if ( !empty( $r->taxonomy ) ) {
			$replacements = array_merge( $replacements, array(
				'%%category_description%%'      => trim( strip_tags( get_term_field( 'description', $r->term_id, $r->taxonomy ) ) ),
				'%%tag_description%%'           => trim( strip_tags( get_term_field( 'description', $r->term_id, $r->taxonomy ) ) ),
				'%%term_description%%'          => trim( strip_tags( get_term_field( 'description', $r->term_id, $r->taxonomy ) ) ),
				'%%term_title%%'                => $r->name,
			) );
		}
	
		foreach ( $replacements as $var => $repl ) {
			if ( !in_array( $var, $omit ) )
				$string = str_replace( $var, $repl, $string );
		}
	
		if ( strpos( $string, '%%' ) === false ) {
			$string = preg_replace( '/\s+/u', ' ', $string );
			return trim( $string );
		}
	
		if ( isset( $wp_query->query_vars['post_type'] ) && preg_match_all( '/%%pt_([^%]+)%%/u', $string, $matches, PREG_SET_ORDER ) ) {
			$pt        = get_post_type_object( $wp_query->query_vars['post_type'] );
			$pt_plural = $pt_singular = $pt->name;
			if ( isset( $pt->labels->singular_name ) )
				$pt_singular = $pt->labels->singular_name;
			if ( isset( $pt->labels->name ) )
				$pt_plural = $pt->labels->name;
			$string = str_replace( '%%pt_single%%', $pt_singular, $string );
			$string = str_replace( '%%pt_plural%%', $pt_plural, $string );
		}
	
		if ( preg_match_all( '/%%cf_([^%]+)%%/u', $string, $matches, PREG_SET_ORDER ) ) {
			global $post;
			foreach ( $matches as $match ) {
				$string = str_replace( $match[0], get_post_meta( $post->ID, $match[1], true ), $string );
			}
		}
	
		if ( preg_match_all( '/%%ct_desc_([^%]+)?%%/u', $string, $matches, PREG_SET_ORDER ) ) {
			global $post;
			foreach ( $matches as $match ) {
				$terms  = get_the_terms( $post->ID, $match[1] );
				$string = str_replace( $match[0], get_term_field( 'description', $terms[0]->term_id, $match[1] ), $string );
			}
		}
	
		if ( preg_match_all( '/%%ct_([^%]+)%%(single%%)?/u', $string, $matches, PREG_SET_ORDER ) ) {
			foreach ( $matches as $match ) {
				$single = false;
				if ( isset( $match[2] ) && $match[2] == 'single%%' )
					$single = true;
				$ct_terms = wpseo_get_terms( $r->ID, $match[1], $single );
	
				$string = str_replace( $match[0], $ct_terms, $string );
			}
		}
	
		$string = preg_replace( '/\s+/u', ' ', $string );
		return trim( $string );
	}

}

global $ect_wpseo_front;
$ect_wpseo_front = new ect_WPSEO_Frontend;