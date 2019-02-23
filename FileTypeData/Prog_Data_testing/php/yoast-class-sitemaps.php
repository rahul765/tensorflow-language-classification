<?php

// This code is the same code used in WordPress SEO by Yoast, but with a small change for the translation used by this plugin. Easy Translation Manager does not have the capability of making SEO and are simply supporting the great WordPress SEO by Yoast for doing the SEO in multiple languages

class etm_WPSEO_Sitemaps {

	/**
	 * Content of the sitemap to output.
	 */
	private $sitemap = '';

	/**
	 * XSL stylesheet for styling a sitemap for web browsers
	 */
	private $stylesheet = '';

	/**
	 * Flag to indicate if this is an invalid or empty sitemap.
	 */
	private $bad_sitemap = false;

	function __construct() {
		add_action( 'template_redirect', array( $this, 'redirect' ) );
	}

	function redirect() {
		global $wpseo_sitemaps;
		$type = get_query_var( 'sitemap' );
		if ( empty( $type ) )
			return;

		$this->build_sitemap( $type );
		// 404 for invalid or emtpy sitemaps
		if ( $this->bad_sitemap ) {
			$GLOBALS['wp_query']->is_404 = true;
			return;
		}

		$wpseo_sitemaps->output();
		die();
	}

	function build_sitemap( $type ) {
		global $wpseo_sitemaps;
		$type = apply_filters('wpseo_build_sitemap_post_type', $type);

		if ( $type == 1 )
			$this->build_root_map();
		else if ( post_type_exists( $type ) )
			$this->build_post_type_map( $type );
		else if ( $tax = get_taxonomy( $type ) )
			$wpseo_sitemaps->build_tax_map( $tax );
		else if ( has_action( 'wpseo_do_sitemap_' . $type ) )
			do_action( 'wpseo_do_sitemap_' . $type );
		else
			$this->bad_sitemap = true;
			$wpseo_sitemaps->set_bad_sitemap(true);
	}

	function build_root_map() {
		global $wpdb,$wpseo_sitemaps;

		$options = get_wpseo_options();

		$this->sitemap = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
		$wpseo_sitemaps->set_sitemap($this->sitemap);
		$base = $GLOBALS['wp_rewrite']->using_index_permalinks() ? 'index.php/' : '';

		// reference post type specific sitemaps
		foreach ( get_post_types( array('public' => true) ) as $post_type ) {
			if ( in_array( $post_type, array('revision','nav_menu_item','attachment') ) )
				continue;
				
			if ( isset($options['post_types-'.$post_type.'-not_in_sitemap']) && $options['post_types-'.$post_type.'-not_in_sitemap'] )
				continue;

			$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type = %s AND post_status = 'publish' LIMIT 1", $post_type ) );
			// don't include post types with no posts
			if ( ! $count )
				continue;

			$n = ($count > 1000) ? (int) ceil($count / 1000) : 1;
			for ( $i = 0; $i < $n; $i++ ) {
				$count = ($n > 1) ? $i + 1 : '';

				if ( empty($count) || $count == $n ) {
					$date = $wpseo_sitemaps->get_last_modified( $post_type );
				} else {
					$date = $wpdb->get_var( $wpdb->prepare( "SELECT post_modified_gmt FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = %s ORDER BY post_modified_gmt ASC LIMIT 1 OFFSET %d", $post_type, $i * 1000 + 999 ) );
					$date = date( 'c', strtotime( $date ) );
				}
				
				$this->sitemap .= '<sitemap>' . "\n";
				$this->sitemap .= '<loc>' . home_url( $base . $post_type . '-sitemap' . $count . '.xml' ) . '</loc>' . "\n";
				$this->sitemap .= '<lastmod>' . htmlspecialchars( $date ) . '</lastmod>' . "\n";
				$this->sitemap .= '</sitemap>' . "\n";
				$wpseo_sitemaps->set_sitemap($this->sitemap);
			}
		}

		// reference taxonomy specific sitemaps
		foreach ( get_taxonomies( array('public' => true) ) as $tax ) {
			if ( in_array( $tax, array('link_category', 'nav_menu', 'post_format') ) )
				continue;
				
			if ( isset($options['taxonomies-'.$tax.'-not_in_sitemap']) && $options['taxonomies-'.$tax.'-not_in_sitemap'] )
				continue;
			// don't include taxonomies with no terms
			if ( ! $wpdb->get_var( $wpdb->prepare( "SELECT term_id FROM $wpdb->term_taxonomy WHERE taxonomy = %s AND count != 0 LIMIT 1", $tax ) ) )
				continue;
			
			// Retrieve the post_types that are registered to this taxonomy and then retrieve last modified date for all of those combined.
			$taxobj = get_taxonomy( $tax );
			$date = $wpseo_sitemaps->get_last_modified( $taxobj->object_type );
			
			$this->sitemap .= '<sitemap>' . "\n";
			$this->sitemap .= '<loc>' . home_url( $base . $tax . '-sitemap.xml' ) . '</loc>' . "\n";
			$this->sitemap .= '<lastmod>' . htmlspecialchars( $date ) . '</lastmod>' . "\n";
			$this->sitemap .= '</sitemap>' . "\n";
			$wpseo_sitemaps->set_sitemap($this->sitemap);
			
		}

		// allow other plugins to add their sitemaps to the index
		$this->sitemap .= apply_filters( 'wpseo_sitemap_index', '' );
		$this->sitemap .= '</sitemapindex>';
		$wpseo_sitemaps->set_sitemap($this->sitemap);
	}

	/**
	 * Build a sub-sitemap for a specific post type -- example.com/post_type-sitemap.xml
	 *
	 * @param string $post_type Registered post type's slug
	 */
	function build_post_type_map( $post_type ) {
		global $wpseo_sitemaps;
		$options = get_wpseo_options();
		
		if ( 
			( isset($options['post_types-'.$post_type.'-not_in_sitemap']) && $options['post_types-'.$post_type.'-not_in_sitemap'] ) 
		 	|| in_array( $post_type, array('revision','nav_menu_item','attachment') ) 
			) {
			$this->bad_sitemap = true;
			$wpseo_sitemaps->set_bad_sitemap(true);
			return;
		}
		
		$output = '';

		$front_id = get_option('page_on_front');
		if ( ! $front_id && $post_type == 'post' ) {
			$output .= $wpseo_sitemaps->sitemap_url( array(
				'loc' => home_url('/'),
				'pri' => 1,
				'chf' => 'daily',
			) );
		} else if ( $front_id && $post_type == 'post' ) {
			$page_for_posts = get_option('page_for_posts');
			if ( $page_for_posts ) {
				$output .= $wpseo_sitemaps->sitemap_url( array(
					'loc' => get_permalink( $page_for_posts ),
					'pri' => 1,
					'chf' => 'daily',
				) );
			}
		}

		if ( function_exists('get_post_type_archive_link') ) {
			$archive = get_post_type_archive_link( $post_type );
			if ( $archive ) {
				$output .= $wpseo_sitemaps->sitemap_url( array(
					'loc' => $archive,
					'pri' => 0.8,
					'chf' => 'weekly',
					'mod' => $wpseo_sitemaps->get_last_modified( $post_type ) // get_lastpostmodified( 'gmt', $post_type ) #17455
				) );
			}
		}

		global $wpdb;
		
		$join_filter = '';
		$join_filter = apply_filters('wpseo_typecount_join', $join_filter, $post_type);
		$where_filter = '';
		$where_filter = apply_filters('wpseo_typecount_where', $where_filter, $post_type);
		$typecount = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->posts {$join_filter} WHERE post_status = 'publish' AND post_password = '' AND post_type = '$post_type' {$where_filter}");
		
		if ( $typecount == 0 && empty( $archive ) ) {
			$this->bad_sitemap = true;
			$wpseo_sitemaps->set_bad_sitemap(true);
			
			return;
		}

		// Let's flush the object cache so we're not left with garbage from other plugins
		wp_cache_flush();
		
		$stackedurls = array();

		$steps = 25;
		$n = (int) get_query_var( 'sitemap_n' );
		$offset = ($n > 1) ? ($n - 1) * 1000 : 0;
		$total = $offset + 1000;
		if ( $total > $typecount )
			$total = $typecount;

		// We grab post_date, post_name, post_author and post_status too so we can throw these objects into get_permalink, which saves a get_post call for each permalink.
		while( $total > $offset ) {
			
			$join_filter = '';
			$join_filter = apply_filters('wpseo_posts_join', $join_filter, $post_type);
			$where_filter = '';
			$where_filter = apply_filters('wpseo_posts_where', $where_filter, $post_type);
			
			$posts = $wpdb->get_results("SELECT ID, post_content, post_name, post_author, post_parent, post_modified_gmt, post_date, post_date_gmt
			FROM $wpdb->posts {$join_filter}
			WHERE post_status = 'publish'
			AND	post_password = ''
			AND post_type = '$post_type'
			{$where_filter}
			ORDER BY post_modified ASC
			LIMIT $steps OFFSET $offset");
			
			$offset = $offset + $steps;

			$etm_current_data = etm_tools_retrive_aktiv_languages();

        	$etm_tag_string = etm_tools_retrive_options('GP_name');
            $etm_permalinktilladelse = etm_tools_retrive_options('use_permalink');
            $etm_default_lang = etm_tools_retrive_options('default_language');
            
            
			$etm_tag_array = '';
			
        	if(!empty($etm_tag_string)){
           	 	$etm_tag_array = explode('|',$etm_tag_string);
           	 	$etm_tag_string = '';
        	}

			foreach ( $posts as $p ) {
				$p->post_type 	= $post_type;
				$p->post_status = 'publish';
				$p->filter		= 'sample';
				
				$runethrowlang = array();
				$runethrowlang[] = '';
                
				if($post_type == 'post' || 	$post_type == 'page'){
				    $tmp_meta_data = '';
                    $tmp_meta_data = get_post_custom($p->ID);
					foreach($etm_current_data as $etm_lang_key => $etm_lang_data){
    					$_tran_focuskw = '';
                        $_tran_title = '';
                        $_tran_metadesc = '';
                        $translations_header = '';
                        $translations_body = '';
                        $translations_permalink = '';
                        
                        $_tran_focuskw = $tmp_meta_data['_yoast_wpseo_focuskw_'.$etm_lang_key][0];
						$_tran_title = $tmp_meta_data['_yoast_wpseo_title_'.$etm_lang_key][0]; 	
						$_tran_metadesc = $tmp_meta_data['_yoast_wpseo_metadesc_'.$etm_lang_key][0]; 
                        
						$translations_header = $tmp_meta_data['ect_tran_title_'.$etm_lang_key][0]; 
						$translations_body = $tmp_meta_data['ect_tran_content_'.$etm_lang_key][0]; 
                        $translations_permalink = $tmp_meta_data['ect_tran_permalink_'.$etm_lang_key][0]; 
    						
                        if($translations_permalink == '/' || !$etm_permalinktilladelse){
                            $translations_permalink = '';    
                        } 
                        
                        

                       
                        
    					if(!empty($_tran_focuskw) || !empty($_tran_title)|| !empty($_tran_metadesc)|| !empty($translations_header)|| !empty($translations_body)){
    						$etm_tag_string = '';
                            $etm_permalin_string = '';
                        
                            
                            
           	 				if(!empty($etm_tag_array) && empty($translations_permalink)){
           	 					foreach($etm_tag_array as $temp_dat){
 									if(!empty($etm_tag_string)){
           	 							$etm_tag_string .='&';
           	 						}
           	 						$etm_tag_string .= $temp_dat . '=' . $etm_lang_key;
           	 					}
           	 				} else if(!empty($translations_permalink)){
           	 				   $etm_permalin_string = $translations_permalink;
           	 				}
           	 				
           	 				if(!empty($etm_tag_string)){
           	 					$runethrowlang[$etm_lang_key]->url = $etm_tag_string;
                                $runethrowlang[$etm_lang_key]->permalink = 'false';
           	 				} else if(!empty($etm_permalin_string)){
           	 				    $runethrowlang[$etm_lang_key]->url = $etm_permalin_string;
                                $runethrowlang[$etm_lang_key]->permalink = 'true';
           	 				}
    					}
    					
					}
				}
                
                
                unset($tmp_meta_data);
                unset($_tran_focuskw);                
                unset($_tran_title);                
                unset($_tran_metadesc);
                unset($translations_header);                
                unset($translations_body);                
                unset($translations_permalink); 
                		
                        
                            	
				foreach($runethrowlang as $etm_translatede_key => $etm_translatede_data){
					if ( wpseo_get_value('meta-robots-noindex', $p->ID) && wpseo_get_value('sitemap-include', $p->ID) != 'always' )
						continue;
					if ( wpseo_get_value('sitemap-include', $p->ID) == 'never')
						continue;
					if ( wpseo_get_value('redirect', $p->ID) && strlen( wpseo_get_value('redirect', $p->ID) ) > 0 )
						continue;

					$url = array();

					$url['mod']	= ( isset( $p->post_modified_gmt ) && $p->post_modified_gmt != '0000-00-00 00:00:00' ) ? $p->post_modified_gmt : $p->post_date_gmt ;
					$url['chf'] = 'weekly';
					$url['loc'] = get_permalink( $p );
					
					if(!empty($etm_translatede_data) && $etm_translatede_data->permalink == 'true'){
                        if(!empty($etm_translatede_data->url)){
                            $url['loc'] = trailingslashit(get_option('siteurl')). $etm_translatede_data->url;
                        }
                    } else if(!empty($etm_translatede_data)){
						$pieces = explode("?", $url['loc'] );
						if(count($pieces)> 1){
							$url['loc'] = $url['loc'] .'&'.$etm_translatede_data->url;
						} else {
							$url['loc'] = $url['loc'] .'?'.$etm_translatede_data->url;
						}  
					}

					$canonical = wpseo_get_value('canonical', $p->ID);
					if ( $canonical && $canonical != '' && $canonical != $url['loc']) {
						continue;
					} else {

						if ( isset($options['trailingslash']) && $options['trailingslash'] && $p->post_type != 'post' )
							$url['loc'] = trailingslashit( $url['loc'] );
					}

					$pri = wpseo_get_value('sitemap-prio', $p->ID);
					if (is_numeric($pri))
						$url['pri'] = $pri;
					elseif ($p->post_parent == 0 && $p->post_type == 'page')
						$url['pri'] = 0.8;
					else
						$url['pri'] = 0.6;

					if ( $p->ID == $front_id )
						$url['pri'] = 1.0;

					$url['images'] = array();
					if ( preg_match_all( '/<img [^>]+>/', $p->post_content, $matches ) ) {
						foreach ( $matches[0] as $img ) {
							// FIXME: get true caption instead of alt / title
							if ( preg_match( '/src=("|\')([^"|\']+)("|\')/', $img, $match ) ) {
								$src = $match[2];
								if ( strpos($src, 'http') !== 0 ) {
									if ( $src[0] != '/' )
										continue;
									$src = get_bloginfo('url') . $src;
								}

								if ( $src != esc_url( $src ) )
									continue;

								if ( isset( $url['images'][$src] ) )
									continue;

								$image = array();
								if ( preg_match( '/title=("|\')([^"\']+)("|\')/', $img, $match ) )
									$image['title'] = str_replace( array('-','_'), ' ', $match[2] );

								if ( preg_match( '/alt=("|\')([^"\']+)("|\')/', $img, $match ) )
									$image['alt'] = str_replace( array('-','_'), ' ', $match[2] );

								$url['images'][$src] = $image;
							}
						}
					}
					if ( preg_match_all( '/\[gallery/', $p->post_content, $matches ) ) {
						$attachments = get_children( array('post_parent' => $p->ID, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image' ) );
						foreach( $attachments as $att_id => $attachment ) {
							$src = wp_get_attachment_image_src( $att_id, 'large', false );
							$src = $src[0];
							$image = array();

							if ( $alt = get_post_meta( $att_id, '_wp_attachment_image_alt', true) )
								$image['alt'] = $alt;
						
							$image['title'] = $attachment->post_title;

							$url['images'][$src] = $image;
						}
					}

					$url['images'] = apply_filters( 'wpseo_sitemap_urlimages', $url['images'], $p->ID );

					if ( !in_array( $url['loc'], $stackedurls ) ) {
						$output .= $wpseo_sitemaps->sitemap_url( $url );
						$stackedurls[] = $url['loc'];
					}

				}	

				// Clear the post_meta and the term cache for the post, as we no longer need it now.
				wp_cache_delete( $p->ID, 'post_meta' );
				// clean_object_term_cache( $p->ID, $post_type );
	
			}
		}
        
        
		if ( empty( $output ) ) {
			$this->bad_sitemap = true;
			$wpseo_sitemaps->set_bad_sitemap(true);
			return;
		}
        

		$this->sitemap = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" ';
		$this->sitemap .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" ';
		$this->sitemap .= 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
		$this->sitemap .= $output . '</urlset>';
		$wpseo_sitemaps->set_sitemap($this->sitemap);
	}
}

$etm_wpseo_sitemaps = new etm_WPSEO_Sitemaps();
