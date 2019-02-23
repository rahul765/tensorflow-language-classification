<?php
/**
 * @package Frontend
 *
 * Main frontend code.
 */


if ( ! defined( 'WPSEO_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! class_exists( 'WPSEO_Frontend_etm' ) ) {
	class WPSEO_Frontend_etm {
		var $options = array();
		var $selectede_lang = '';
		
		function __construct() {

			$this->options = WPSEO_Options::get_all();
			global $ect_wpseo_front,$easy_translation_manager_plugin;
			$default_languash =  etm_tools_retrive_options('default_language');
			if($easy_translation_manager_plugin->show_lang_status and $easy_translation_manager_plugin->selectede_lang != $default_languash){
				$this->selectede_lang = $easy_translation_manager_plugin->selectede_lang;
			
			}
			
		
			if($this->selectede_lang != ''){
				
				add_action( 'wp_head', array( $this, 'head' ), 1 );
				add_filter( 'wp_title', array( $this, 'title' ), 20, 3 );
				add_filter( 'thematic_doctitle', array( $this, 'title' ), 20 );
			}
		}

		/**
		 * Determine whether the current page is the homepage and shows posts.
		 *
		 * @return bool
		 */
		function is_home_posts_page() {
			return ( is_home() && 'page' != get_option( 'show_on_front' ) );
		}

		/**
		 * Determine whether the current page is a static homepage.
		 *
		 * @return bool
		 */
		function is_home_static_page() {
			return ( is_front_page() && 'page' == get_option( 'show_on_front' ) && is_page( get_option( 'page_on_front' ) ) );
		}

		/**
		 * Determine whether this is the posts page, regardless of whether it's the frontpage or not.
		 *
		 * @return bool
		 */
		function is_posts_page() {
			return ( is_home() && 'page' == get_option( 'show_on_front' ) );
		}

		/**
		 * Used for static home and posts pages as well as singular titles.
		 *
		 * @param object|null $object if filled, object to get the title for
		 *
		 * @return string
		 */
		function get_content_title( $object = null ) {
			if ( is_null( $object ) ) {
				global $wp_query;
				$object = $wp_query->get_queried_object();
			}

			$title = WPSEO_Meta::get_value( 'title_'.$this->selectede_lang, $object->ID );

			if ( $title !== '' ) {
				return $this->etm_wpseo_replace_vars( $title, (array) $object );
			}

			$post_type = ( isset( $object->post_type ) ? $object->post_type : $object->query_var );

			return $this->get_title_from_options( 'title-' . $post_type, $object );
		}

		/**
		 * Used for category, tag, and tax titles.
		 *
		 * @return string
		 */
		function get_taxonomy_title() {
			global $wp_query;
			$object = $wp_query->get_queried_object();

			$title = WPSEO_Taxonomy_Meta::get_term_meta( $object, $object->taxonomy, 'title' );

			if ( is_string( $title ) && $title !== '' ) {
				return $this->etm_wpseo_replace_vars( $title, (array) $object );
			} else {
				return $this->get_title_from_options( 'title-tax-' . $object->taxonomy, $object );
			}
		}

		/**
		 * Used for author titles.
		 *
		 * @return string
		 */
		function get_author_title() {
			$author_id = get_query_var( 'author' );
			$title     = trim( get_the_author_meta( 'wpseo_title', $author_id ) );

			if ( $title !== '' ) {
				return $this->etm_wpseo_replace_vars( $title, array() );
			}

			return $this->get_title_from_options( 'title-author-wpseo' );
		}

		/**
		 * Simple function to use to pull data from $options.
		 *
		 * All titles pulled from options will be run through the $this->etm_wpseo_replace_vars function.
		 *
		 * @param string       $index      name of the page to get the title from the settings for.
		 * @param object|array $var_source possible object to pull variables from.
		 *
		 * @return string
		 */
		function get_title_from_options( $index, $var_source = array() ) {
			if ( ! isset( $this->options[$index] ) || $this->options[$index] === '' ) {
				if ( is_singular() ) {
					return $this->etm_wpseo_replace_vars( '%%title%% %%sep%% %%sitename%%', (array) $var_source );
				} else {
					return '';
				}
			} else {
				return $this->etm_wpseo_replace_vars( $this->options[$index], (array) $var_source );
			}
		}

		/**
		 * Get the default title for the current page.
		 *
		 * This is the fallback title generator used when a title hasn't been set for the specific content, taxonomy, author
		 * details, or in the options. It scrubs off any present prefix before or after the title (based on $seplocation) in
		 * order to prevent duplicate seperations from appearing in the title (this happens when a prefix is supplied to the
		 * wp_title call on singular pages).
		 *
		 * @param string $sep         the separator used between variables
		 * @param string $seplocation Whether the separator should be left or right.
		 * @param string $title       possible title that's already set
		 *
		 * @return string
		 */
		function get_default_title( $sep, $seplocation, $title = '' ) {
			if ( 'right' == $seplocation ) {
				$regex = '`\s*' . preg_quote( trim( $sep ), '`' ) . '\s*`u';
			} else {
				$regex = '`^\s*' . preg_quote( trim( $sep ), '`' ) . '\s*`u';
			}
			$title = preg_replace( $regex, '', $title );

			if ( ! is_string( $title ) || ( is_string( $title ) && $title === '' ) ) {
				$title = get_bloginfo( 'name' );
				$title = $this->add_paging_to_title( $sep, $seplocation, $title );
				$title = $this->add_to_title( $sep, $seplocation, $title, get_bloginfo( 'description' ) );

				return $title;
			}

			$title = $this->add_paging_to_title( $sep, $seplocation, $title );
			$title = $this->add_to_title( $sep, $seplocation, $title, get_bloginfo( 'name' ) );

			return $title;
		}

		/**
		 * This function adds paging details to the title.
		 *
		 * @param string $sep         separator used in the title
		 * @param string $seplocation Whether the separator should be left or right.
		 * @param string $title       the title to append the paging info to
		 *
		 * @return string
		 */
		function add_paging_to_title( $sep, $seplocation, $title ) {
			global $wp_query;

			if ( ! empty( $wp_query->query_vars['paged'] ) && $wp_query->query_vars['paged'] > 1 ) {
				return $this->add_to_title( $sep, $seplocation, $title, $wp_query->query_vars['paged'] . '/' . $wp_query->max_num_pages );
			}

			return $title;
		}

		/**
		 * Add part to title, while ensuring that the $seplocation variable is respected.
		 *
		 * @param string $sep         separator used in the title
		 * @param string $seplocation Whether the separator should be left or right.
		 * @param string $title       the title to append the title_part to
		 * @param string $title_part  the part to append to the title
		 *
		 * @return string
		 */
		function add_to_title( $sep, $seplocation, $title, $title_part ) {
			if ( 'right' === $seplocation ) {
				return $title . $sep . $title_part;
			}

			return $title_part . $sep . $title;
		}

		/**
		 * Main title function.
		 *
		 * @param string $title       Title that might have already been set.
		 * @param string $sepinput    Separator determined in theme.
		 * @param string $seplocation Whether the separator should be left or right.
		 *
		 * @return string
		 */
		function title( $title, $sepinput = '-', $seplocation = '' ) {
			global $sep,$wp_query,$post;

			$sep = $sepinput;
			$postid = get_the_ID(); 
			$translatede_title = get_post_meta($postid, '_yoast_wpseo_title_'.$this->selectede_lang, true);
		
			if(!empty($translatede_title)){
				$title = $translatede_title;
			}

			if ( is_feed() )
				return $title;

			// This needs to be kept track of in order to generate
			// default titles for singular pages.
			$original_title = $title;

			// This conditional ensures that sites that use of wp_title(''); as the plugin
			// used to suggest will still work properly with these changes.
			if ( '' === trim( $sep ) && '' === $seplocation ) {
				$sep         = '-';
				$seplocation = 'right';
			} // In the event that $seplocation is left empty, the direction will be
			// determined by whether the site is in rtl mode or not. This is based
			// upon my findings that rtl sites tend to reverse the flow of the site titles.
			elseif ( '' === $seplocation ) {
				$seplocation = ( is_rtl() ) ? 'left' : 'right';
			}

			$sep = ' ' . trim( $sep ) . ' ';

			// This flag is used to determine if any additional
			// processing should be done to the title after the
			// main section of title generation completes.
			$modified_title = true;

			// This variable holds the page-specific title part
			// that is used to generate default titles.
			$title_part = '';

			if ( $this->is_home_static_page() ) {
				$title = $this->get_content_title();
			} elseif ( $this->is_home_posts_page() ) {
				$title = $this->get_title_from_options( 'title-home-wpseo' );
			} elseif ( $this->is_posts_page() ) {
				$title = $this->get_content_title( get_post( get_option( 'page_for_posts' ) ) );
			} elseif ( is_singular() ) {
				$title = $this->get_content_title();

				if ( ! is_string( $title ) || '' === $title ) {
					$title_part = $original_title;
				}
			} elseif ( is_search() ) {
				$title = $this->get_title_from_options( 'title-search-wpseo' );

				if ( ! is_string( $title ) || '' === $title ) {
					$title_part = sprintf( __( 'Search for "%s"', 'wordpress-seo' ), esc_html( get_search_query() ) );
				}
			} elseif ( is_category() || is_tag() || is_tax() ) {
				$title = $this->get_taxonomy_title();

				if ( ! is_string( $title ) || '' === $title ) {
					if ( is_category() ) {
						$title_part = single_cat_title( '', false );
					} elseif ( is_tag() ) {
						$title_part = single_tag_title( '', false );
					} else {
						$title_part = single_term_title( '', false );
						if ( $title_part === '' ) {
							global $wp_query;
							$term       = $wp_query->get_queried_object();
							$title_part = $term->name;
						}
					}
				}
			} elseif ( is_author() ) {
				$title = $this->get_author_title();

				if ( ! is_string( $title ) || '' === $title ) {
					$title_part = get_the_author_meta( 'display_name', get_query_var( 'author' ) );
				}
			} elseif ( is_post_type_archive() ) {
				$post_type = get_query_var( 'post_type' );
				$title     = $this->get_title_from_options( 'title-ptarchive-' . $post_type );

				if ( ! is_string( $title ) || '' === $title ) {
					$post_type_obj = get_post_type_object( $post_type );
					if ( isset( $post_type_obj->labels->menu_name ) ) {
						$title_part = $post_type_obj->labels->menu_name;
					} elseif ( isset( $post_type_obj->name ) ) {
						$title_part = $post_type_obj->name;
					} else {
						$title_part = ''; //To be determined what this should be
					}
				}
			} elseif ( is_archive() ) {
				$title = $this->get_title_from_options( 'title-archive-wpseo' );

				// @todo [JRF => Yoast] Should these not use the archive default if no title found ?
				// WPSEO_Options::get_default( 'wpseo_titles', 'title-archive-wpseo' )
				// Replacement would be needed!
				if ( empty( $title ) ) {
					if ( is_month() ) {
						$title_part = sprintf( __( '%s Archives', 'wordpress-seo' ), single_month_title( ' ', false ) );
					} elseif ( is_year() ) {
						$title_part = sprintf( __( '%s Archives', 'wordpress-seo' ), get_query_var( 'year' ) );
					} elseif ( is_day() ) {
						$title_part = sprintf( __( '%s Archives', 'wordpress-seo' ), get_the_date() );
					} else {
						$title_part = __( 'Archives', 'wordpress-seo' );
					}
				}
			} elseif ( is_404() ) {

				if ( 0 !== get_query_var( 'year' ) || ( 0 !== get_query_var( 'monthnum' ) || 0 !== get_query_var( 'day' ) ) ) {
					// @todo [JRF => Yoast] Should these not use the archive default if no title found ?
					if ( 0 !== get_query_var( 'day' ) ) {
						$date = sprintf( '%04d-%02d-%02d 00:00:00', get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) );
						$date = mysql2date( get_option( 'date_format' ), $date );
						$date = apply_filters( 'get_the_date', $date, '' );
						$title_part      = sprintf( __( '%s Archives', 'wordpress-seo' ), $date );
					} elseif ( 0 !== get_query_var( 'monthnum' ) ) {
						$title_part = sprintf( __( '%s Archives', 'wordpress-seo' ), single_month_title( ' ', false ) );
					} elseif ( 0 !== get_query_var( 'year' ) ) {
						$title_part = sprintf( __( '%s Archives', 'wordpress-seo' ), get_query_var( 'year' ) );
					} else {
						$title_part = __( 'Archives', 'wordpress-seo' );
					}
				} else {
					$title = $this->get_title_from_options( 'title-404-wpseo' );

					// @todo [JRF => Yoast] Should these not use the 404 default if no title found ?
					// WPSEO_Options::get_default( 'wpseo_titles', 'title-404-wpseo' )
					// Replacement would be needed!
					if ( empty( $title ) ) {
						$title_part = __( 'Page not found', 'wordpress-seo' );
					}
				}
			} else {
				// In case the page type is unknown, leave the title alone.
				$modified_title = false;

				// If you would like to generate a default title instead,
				// the following code could be used instead of the line above:
				// $title_part = $title;
			}

			if ( ( $modified_title && empty( $title ) ) || ! empty( $title_part ) ) {
				$title = $this->get_default_title( $sep, $seplocation, $title_part );
			}

			if ( defined( 'ICL_LANGUAGE_CODE' ) && false !== strpos( $title, ICL_LANGUAGE_CODE ) ) {
				$title = str_replace( ' @' . ICL_LANGUAGE_CODE, '', $title );
			}

			if(!empty($translatede_title)){
				$title = $translatede_title;
			}

			return esc_html( strip_tags( stripslashes( apply_filters( 'wpseo_title', $title ) ) ) );
		}

		/**
		 * Function used when title needs to be force overridden.
		 *
		 * @return string
		 */
		function force_wp_title() {
			global $wp_query;
			$old_wp_query = null;

			if ( ! $wp_query->is_main_query() ) {
				$old_wp_query = $wp_query;
				wp_reset_query();
			}

			$title = $this->title( '' );

			if ( ! empty( $old_wp_query ) ) {
				$GLOBALS['wp_query'] = $old_wp_query;
				unset( $old_wp_query );
			}

			return $title;
		}

		/**
		 * Outputs or returns the debug marker, which is also used for title replacement when force rewrite is active.
		 *
		 * @param bool $echo Whether or not to echo the debug marker.
		 *
		 * @return string
		 */
		public function debug_marker( $echo = true ) {
			$marker = '<!-- This site is optimized with the Yoast WordPress SEO plugin v' . WPSEO_VERSION . ' - http://yoast.com/wordpress/seo/ -->';
			if ( $echo === false ) {
				return $marker;
			} else {
				echo "\n${marker}\n";
			}
		}

		/**
		 * Output Webmaster Tools authentication strings
		 */
		public function webmaster_tools_authentication() {
			if ( is_front_page() ) {
				// Alexa
				if ( $this->options['alexaverify'] !== '' ) {
					echo '<meta name="alexaVerifyID" content="' . esc_attr( $this->options['alexaverify'] ) . "\" />\n";
				}

				// Bing
				if ( $this->options['msverify'] !== '' ) {
					echo '<meta name="msvalidate.01" content="' . esc_attr( $this->options['msverify'] ) . "\" />\n";
				}

				// Google
				if ( $this->options['googleverify'] !== '' ) {
					echo '<meta name="google-site-verification" content="' . esc_attr( $this->options['googleverify'] ) . "\" />\n";
				}

				// Pinterest
				if ( $this->options['pinterestverify'] !== '' ) {
					echo '<meta name="p:domain_verify" content="' . esc_attr( $this->options['pinterestverify'] ) . "\" />\n";
				}

				// Yandex
				if ( $this->options['yandexverify'] !== '' ) {
					echo '<meta name=\'yandex-verification\' content=\'' . esc_attr( $this->options['yandexverify'] ) . "' />\n";
				}
			}
		}

		/**
		 * Main wrapper function attached to wp_head. This combines all the output on the frontend of the WP SEO plugin.
		 */
		function head() {
			wp_reset_query();
			$this->metadesc();
			$this->metakeywords();
			return;
		}


		/**
		 * Output the meta robots value.
		 */
		public function robots() {
			global $wp_query;

			$robots           = array();
			$robots['index']  = 'index';
			$robots['follow'] = 'follow';
			$robots['other']  = array();

			if ( is_singular() ) {
				global $post;

				if ( is_object( $post ) && ( isset( $this->options['noindex-' . $post->post_type] ) && $this->options['noindex-' . $post->post_type] === true ) ) {
					$robots['index'] = 'noindex';
				}

				if ( 'private' == $post->post_status ) {
					$robots['index'] = 'noindex';
				}

				$robots = $this->robots_for_single_post( $robots );

			} else {
				if ( is_search() ) {
					$robots['index'] = 'noindex';
				} elseif ( is_tax() || is_tag() || is_category() ) {
					$term = $wp_query->get_queried_object();
					if ( is_object( $term ) && ( isset( $this->options['noindex-tax-' . $term->taxonomy] ) && $this->options['noindex-tax-' . $term->taxonomy] === true ) ) {
						$robots['index'] = 'noindex';
					}

					// Three possible values, index, noindex and default, do nothing for default
					$term_meta = WPSEO_Taxonomy_Meta::get_term_meta( $term, $term->taxonomy, 'noindex' );
					if ( is_string( $term_meta ) && 'default' !== $term_meta ) {
						$robots['index'] = $term_meta;
					}
				} elseif (
						( is_author() && $this->options['noindex-author-wpseo'] === true ) ||
						( is_date() && $this->options['noindex-archive-wpseo'] === true )
				) {
					$robots['index'] = 'noindex';
				} elseif ( is_home() ) {
					if ( get_query_var( 'paged' ) > 1 ) {
						$robots['index'] = 'noindex';
					}
					
					$page_for_posts = get_option( 'page_for_posts' );
					if ( $page_for_posts ) {
						$robots = $this->robots_for_single_post( $robots, $page_for_posts );
					}
					unset( $page_for_posts );

				} elseif ( is_post_type_archive() ) {
					$post_type = get_query_var( 'post_type' );
					if ( isset( $this->options['noindex-ptarchive-' . $post_type] ) && $this->options['noindex-ptarchive-' . $post_type] === true )
						$robots['index'] = 'noindex';
				}

				if ( isset( $wp_query->query_vars['paged'] ) && ( $wp_query->query_vars['paged'] && $wp_query->query_vars['paged'] > 1 ) && ( $this->options['noindex-subpages-wpseo'] === true ) ) {
					$robots['index']  = 'noindex';
					$robots['follow'] = 'follow';
				}

				foreach ( array( 'noodp', 'noydir' ) as $robot ) {
					if ( $this->options[$robot] === true ) {
						$robots['other'][] = $robot;
					}
				}
				unset( $robot );
			}

			// Force override to respect the WP settings
			if ( '0' == get_option( 'blog_public' ) || isset( $_GET['replytocom'] ) ) {
				$robots['index'] = 'noindex';
			}


			$robotsstr = $robots['index'] . ',' . $robots['follow'];

			if ( $robots['other'] !== array() ) {
				$robots['other'] = array_unique( $robots['other'] ); // most likely no longer needed, needs testing
				foreach ( $robots['other'] as $robot ) {
					$robotsstr .= ',' . $robot;
				}
			}

			$robotsstr = preg_replace( '`^index,follow,?`', '', $robotsstr );

			/**
			 * Filter: 'wpseo_robots' - Allows filtering of the meta robots output of WP SEO
			 *
			 * @api string $robotsstr The meta robots directives to be echoed.
			 */
			$robotsstr = apply_filters( 'wpseo_robots', $robotsstr );

			if ( is_string( $robotsstr ) && $robotsstr !== '' ) {
				echo '<meta name="robots" content="' . esc_attr( $robotsstr ) . '"/>' . "\n";
			}
		}
		
		/**
		 * Determine $robots values for a single post
		 *
		 * @param	array		$robots
		 * @param	int|string	$postid	The postid for which to determine the $robots values, defaults to
		 *						the current post
		 *
		 * @return	array
		 */
		function robots_for_single_post( $robots, $postid = 0 ) {
			if ( WPSEO_Meta::get_value( 'meta-robots-noindex', $postid ) === '1' ) {
				$robots['index'] = 'noindex';
			} elseif ( WPSEO_Meta::get_value( 'meta-robots-noindex', $postid ) === '2' ) {
				$robots['index'] = 'index';
			}

			if ( WPSEO_Meta::get_value( 'meta-robots-nofollow', $postid ) === '1' ) {
				$robots['follow'] = 'nofollow';
			}

			$meta_robots_adv = WPSEO_Meta::get_value( 'meta-robots-adv', $postid );
			if ( $meta_robots_adv !== '' && ( $meta_robots_adv !== '-' && $meta_robots_adv !== 'none' ) ) {
				$meta_robots_adv = explode( ',', $meta_robots_adv );
				foreach ( $meta_robots_adv as $robot ) {
					$robots['other'][] = $robot;
				}
				unset( $robot );
			} elseif ( $meta_robots_adv === '' || $meta_robots_adv === '-' ) {
				foreach ( array( 'noodp', 'noydir' ) as $robot ) {
					if ( $this->options[$robot] === true ) {
						$robots['other'][] = $robot;
					}
				}
				unset( $robot );
			}
			unset( $meta_robots_adv );
			
			return $robots;
		}


		/**
		 * This function normally outputs the canonical but is also used in other places to retrieve
		 * the canonical URL for the current page.
		 *
		 * @param bool $echo        Whether or not to output the canonical element.
		 * @param bool $un_paged    Whether or not to return the canonical with or without pagination added to the URL.
		 * @param bool $no_override Whether or not to return a manually overridden canonical
		 *
		 * @return string $canonical
		 */
		public function canonical( $echo = true, $un_paged = false, $no_override = false ) {
			$canonical       = false;
			$skip_pagination = false;

			// Set decent canonicals for homepage, singulars and taxonomy pages
			if ( is_singular() ) {
				$meta_canon = WPSEO_Meta::get_value( 'canonical' );
				if ( $no_override === false && $meta_canon !== '' ) {
					$canonical       = $meta_canon;
					$skip_pagination = true;
				} else {
					$obj       = get_queried_object();
					$canonical = get_permalink( $obj->ID );

					// Fix paginated pages canonical, but only if the page is truly paginated.
					if ( get_query_var( 'page' ) > 1 ) {
						global $wp_rewrite;
						$numpages = substr_count( $obj->post_content, '<!--nextpage-->' ) + 1;
						if ( $numpages && get_query_var( 'page' ) <= $numpages ) {
							if ( ! $wp_rewrite->using_permalinks() ) {
								$canonical = add_query_arg( 'page', get_query_var( 'page' ), $canonical );
							} else {
								$canonical = user_trailingslashit( trailingslashit( $canonical ) . get_query_var( 'page' ) );
							}
						}
					}
				}
				unset( $meta_canon );
			} else {
				if ( is_search() ) {
					$canonical = get_search_link();
				} elseif ( is_front_page() ) {
					$canonical = home_url( '/' );
				} elseif ( $this->is_posts_page() ) {
					$canonical = get_permalink( get_option( 'page_for_posts' ) );
				} elseif ( is_tax() || is_tag() || is_category() ) {
					$term = get_queried_object();
					if ( ! $no_override ) {
						$canonical = WPSEO_Taxonomy_Meta::get_term_meta( $term, $term->taxonomy, 'canonical' );
						if ( is_string( $canonical ) && $canonical !== '' ) {
							$skip_pagination = true;
						}
					}
					if ( ! is_string( $canonical ) || $canonical === '' ) {
						$canonical = get_term_link( $term, $term->taxonomy );
					}
				} elseif ( is_post_type_archive() ) {
					$canonical = get_post_type_archive_link( get_query_var( 'post_type' ) );
				} elseif ( is_author() ) {
					$canonical = get_author_posts_url( get_query_var( 'author' ), get_query_var( 'author_name' ) );
				} elseif ( is_archive() ) {
					if ( is_date() ) {
						if ( is_day() ) {
							$canonical = get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) );
						} elseif ( is_month() ) {
							$canonical = get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) );
						} elseif ( is_year() ) {
							$canonical = get_year_link( get_query_var( 'year' ) );
						}
					}
				}
			}

			if ( $canonical && $un_paged ) {
				return $canonical;
			}

			if ( $canonical && ! $skip_pagination && get_query_var( 'paged' ) > 1 ) {
				global $wp_rewrite;
				if ( ! $wp_rewrite->using_permalinks() ) {
					$canonical = add_query_arg( 'paged', get_query_var( 'paged' ), $canonical );
				} else {
					if ( is_front_page() ) {
						$base      = $wp_rewrite->using_index_permalinks() ? 'index.php/' : '/';
						$canonical = home_url( $base );
					}
					$canonical = user_trailingslashit( trailingslashit( $canonical ) . trailingslashit( $wp_rewrite->pagination_base ) . get_query_var( 'paged' ) );
				}
			}

			if ( $canonical && 'default' !== $this->options['force_transport'] ) {
				$canonical = preg_replace( '`^http[s]?`', $this->options['force_transport'], $canonical );
			}

			/**
			 * Filter: 'wpseo_canonical' - Allow filtering of the canonical URL put out by WP SEO
			 *
			 * @api string $canonical The canonical URL
			 */
			$canonical = apply_filters( 'wpseo_canonical', $canonical );

			if ( is_string( $canonical ) && $canonical !== '' ) {
				if ( $echo !== false ) {
					echo '<link rel="canonical" href="' . esc_url( $canonical, null, 'other' ) . '" />' . "\n";
				} else {
					return $canonical;
				}
			} else {
				return false;
			}
		}

		/**
		 * Adds 'prev' and 'next' links to archives.
		 *
		 * @link  http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
		 * @since 1.0.3
		 */
		public function adjacent_rel_links() {
			// Don't do this for Genesis, as the way Genesis handles homepage functionality is different and causes issues sometimes.
			/**
			 * Filter 'wpseo_genesis_force_adjacent_rel_home' - Allows devs to allow echoing rel="next" / rel="prev" by WP SEO on Genesis installs
			 *
			 * @api bool $unsigned Whether or not to rel=next / rel=prev
			 */
			if ( is_home() && function_exists( 'genesis' ) && apply_filters( 'wpseo_genesis_force_adjacent_rel_home', false ) === false )
				return;

			global $wp_query;

			if ( ! is_singular() ) {
				$url = $this->canonical( false, true, true );

				if ( is_string( $url ) && $url !== '' ) {
					$paged = get_query_var( 'paged' );

					if ( 0 == $paged )
						$paged = 1;

					if ( $paged == 2 )
						$this->adjacent_rel_link( 'prev', $url, $paged - 1, true );

					// Make sure to use index.php when needed, done after paged == 2 check so the prev links to homepage will not have index.php erroneously.
					if ( is_front_page() ) {
						$base = $GLOBALS['wp_rewrite']->using_index_permalinks() ? 'index.php/' : '/';
						$url  = home_url( $base );
					}

					if ( $paged > 2 )
						$this->adjacent_rel_link( 'prev', $url, $paged - 1, true );

					if ( $paged < $wp_query->max_num_pages )
						$this->adjacent_rel_link( 'next', $url, $paged + 1, true );
				}
			} else {
				$numpages = 0;
				if ( isset( $wp_query->post->post_content ) ) {
					$numpages = substr_count( $wp_query->post->post_content, '<!--nextpage-->' ) + 1;
				}
				if ( $numpages > 1 ) {
					$page = get_query_var( 'page' );
					if ( ! $page )
						$page = 1;

					$url = get_permalink( $wp_query->post->ID );

					// If the current page is the frontpage, pagination should use /base/
					if ( $this->is_home_static_page() )
						$usebase = true;
					else
						$usebase = false;

					if ( $page > 1 )
						$this->adjacent_rel_link( 'prev', $url, $page - 1, $usebase, 'single_paged' );
					if ( $page < $numpages )
						$this->adjacent_rel_link( 'next', $url, $page + 1, $usebase, 'single_paged' );
				}
			}
		}

		/**
		 * Get adjacent pages link for archives
		 *
		 * @param string  $rel                  Link relationship, prev or next.
		 * @param string  $url                  the unpaginated URL of the current archive.
		 * @param string  $page                 the page number to add on to $url for the $link tag.
		 * @param boolean $incl_pagination_base whether or not to include /page/ or not.
		 *
		 * @return string $link link element
		 *
		 * @since 1.0.2
		 */
		private function adjacent_rel_link( $rel, $url, $page, $incl_pagination_base ) {
			global $wp_rewrite;
			if ( ! $wp_rewrite->using_permalinks() ) {
				if ( $page > 1 )
					$url = add_query_arg( 'paged', $page, $url );
			} else {
				if ( $page > 1 ) {
					$base = '';
					if ( $incl_pagination_base ) {
						$base = trailingslashit( $wp_rewrite->pagination_base );
					}
					$url = user_trailingslashit( trailingslashit( $url ) . $base . $page );
				}
			}
			/**
			 * Filter: 'wpseo_' . $rel . '_rel_link' - Allow changing link rel output by WP SEO
			 *
			 * @api string $unsigned The full `<link` element.
			 */
			$link = apply_filters( 'wpseo_' . $rel . '_rel_link', '<link rel="' . $rel . '" href="' . esc_url( $url ) . "\" />\n" );

			if ( is_string( $link ) && $link !== '' ) {
				echo $link;
			}
		}

		/**
		 * Output the rel=publisher code on every page of the site.
		 */
		public function publisher() {
			if ( $this->options['plus-publisher'] !== '' ) {
				echo '<link rel="publisher" href="' . esc_url( $this->options['plus-publisher'] ) . '"/>' . "\n";
			}
		}

		/**
		 * Outputs the rel=author
		 */
		public function author() {
			global $post;

			$gplus = false;

			if ( is_singular() ) {
				if ( is_object( $post ) ) {
					$have_author = WPSEO_Meta::get_value( 'authorship' );

					switch ( $have_author ) {
						case 'always':
							$gplus = get_the_author_meta( 'googleplus', $post->post_author );
							break;

						case '-':
							// Defer to post_type default
							if ( ! isset( $this->options['noauthorship-' . $post->post_type] ) || $this->options['noauthorship-' . $post->post_type] === false ) {
								$gplus = get_the_author_meta( 'googleplus', $post->post_author );
							}
							break;
					}
				}
			}

			/**
			 * Allow changing the rel=author link being put out by WPSEO
			 *
			 * @api string $gplus The rel=author link for the current URL.
			 */
			$gplus = apply_filters( 'wpseo_author_link', $gplus );

			if ( is_string( $gplus ) && $gplus !== '' ) {
				echo '<link rel="author" href="' . esc_url( $gplus ) . '"/>' . "\n";
			}
		}

		/**
		 * Outputs the meta keywords element.
		 *
		 * @return string
		 */
		public function metakeywords() {
			global $wp_query, $post;

			if ( $this->options['usemetakeywords'] === false )
				return;

			$keywords = '';
			$postid = get_the_ID(); 
			$translatede_metakey = get_post_meta($postid, '_yoast_wpseo_focuskw_'.$this->selectede_lang, true);
			if(!empty($translatede_metakey)){
				$keywords = $translatede_metakey;
			}			
			

			if ( is_singular() ) {
				$keywords = WPSEO_Meta::get_value( 'focuskw_'.$this->selectede_lang );
				if ( $keywords === '' && ( is_object( $post ) && ( ( isset( $this->options['metakey-' . $post->post_type] ) && $this->options['metakey-' . $post->post_type] !== '' ) ) ) ) {
					$keywords = $this->etm_wpseo_replace_vars( $this->options['metakey-' . $post->post_type], (array) $post );
				}
			} else {
				if ( $this->is_home_posts_page() && $this->options['metakey-home-wpseo'] !== '' ) {
					$keywords = $this->etm_wpseo_replace_vars( $this->options['metakey-home-wpseo'], array() );
				} elseif ( $this->is_home_static_page() ) {
					$keywords = WPSEO_Meta::get_value( 'focuskw_'.$this->selectede_lang);
					if ( $keywords === '' && ( is_object( $post ) && ( isset( $this->options['metakey-' . $post->post_type] ) && $this->options['metakey-' . $post->post_type] !== '' ) ) ) {
						$keywords = $this->etm_wpseo_replace_vars( $this->options['metakey-' . $post->post_type], (array) $post );
					}
				} elseif ( is_category() || is_tag() || is_tax() ) {
					$term = $wp_query->get_queried_object();

					if ( is_object( $term ) ) {
						$keywords = WPSEO_Taxonomy_Meta::get_term_meta( $term, $term->taxonomy, 'metakey' );
						if ( ( ! is_string( $keywords ) || $keywords === '' ) && ( isset( $this->options['metakey-tax-' . $term->taxonomy] ) && $this->options['metakey-tax-' . $term->taxonomy] !== '' ) ) {
							$keywords = $this->etm_wpseo_replace_vars( $this->options['metakey-tax-' . $term->taxonomy], (array) $term );
						}
					}
				} elseif ( is_author() ) {
					$author_id = get_query_var( 'author' );
					$keywords  = get_the_author_meta( 'metakey', $author_id );
					if ( ! $keywords && $this->options['metakey-author-wpseo'] !== '' ) {
						$keywords = $this->etm_wpseo_replace_vars( $this->options['metakey-author-wpseo'], (array) $wp_query->get_queried_object() );
					}
				} elseif ( is_post_type_archive() ) {
					$post_type = get_query_var( 'post_type' );
					if ( isset( $this->options['metakey-ptarchive-' . $post_type] ) && $this->options['metakey-ptarchive-' . $post_type] !== '' ) {
						$keywords = $this->etm_wpseo_replace_vars( $this->options['metakey-ptarchive-' . $post_type], (array) $wp_query->get_queried_object() );
					}
				}
			}
			
			if(!empty($translatede_metakey)){
				$keywords = $translatede_metakey;
			}

			$keywords = apply_filters( 'wpseo_metakey', trim( $keywords ) ); // make deprecated


			if(!empty($translatede_metakey)){
				$keywords = $translatede_metakey;
			}

			/**
			 * Filter: 'wpseo_metakeywords' - Allow changing the WP SEO meta keywords
			 *
			 * @api string $keywords The meta keywords to be echoed.
			 */
			$keywords = apply_filters( 'wpseo_metakeywords', trim( $keywords ) ); // more appropriately named

			if(!empty($translatede_metakey)){
				$keywords = $translatede_metakey;
			}

			if ( is_string( $keywords ) && $keywords !== '' ) {
				echo '<meta name="keywords" content="' . esc_attr( strip_tags( stripslashes( $keywords ) ) ) . '"/>' . "\n";
			}
		}

		/**
		 * Outputs the meta description element or returns the description text.
		 *
		 * @param bool $echo Whether or not to echo the description.
		 *
		 * @return string
		 */
		public function metadesc( $echo = true ) {
			if ( get_query_var( 'paged' ) && get_query_var( 'paged' ) > 1 ) {
				return '';
			}

			global $post, $wp_query;

			$metadesc  = '';
			
			$postid = get_the_ID(); 
			$translatede_metadesc = get_post_meta($postid, '_yoast_wpseo_metadesc_'.$this->selectede_lang, true);
			if(!empty($translatede_metadesc)){
				$metadesc = $translatede_metadesc;
			}

			$post_type = '';
			if ( is_object( $post ) && ( isset( $post->post_type ) && $post->post_type !== '' ) ) {
				$post_type = $post->post_type;
			}

			if ( is_singular() ) {
				$metadesc = WPSEO_Meta::get_value( 'metadesc_'.$this->selectede_lang );
				if ( ( $metadesc === '' && $post_type !== '' ) && ( isset( $this->options['metadesc-' . $post_type] ) && $this->options['metadesc-' . $post_type] !== '' ) ) {
					$metadesc = $this->etm_wpseo_replace_vars( $this->options['metadesc-' . $post_type], (array) $post );
				}
			} else {
				if ( is_search() ) {
					$metadesc = '';
				} elseif ( $this->is_home_posts_page() && $this->options['metadesc-home-wpseo'] !== '' ) {
					$metadesc = $this->etm_wpseo_replace_vars( $this->options['metadesc-home-wpseo'], array() );
				} elseif ( $this->is_posts_page() ) {
					$metadesc = WPSEO_Meta::get_value( 'metadesc_'.$this->selectede_lang, get_option( 'page_for_posts' ) );
					if ( ( $metadesc === '' && $post_type !== '' ) && ( isset( $this->options['metadesc-' . $post_type] ) && $this->options['metadesc-' . $post_type] !== '' ) ) {
						$page     = get_post( get_option( 'page_for_posts' ) );
						$metadesc = $this->etm_wpseo_replace_vars( $this->options['metadesc-' . $post_type], (array) $page );
					}
				} elseif ( $this->is_home_static_page() ) {
					$metadesc = WPSEO_Meta::get_value( 'metadesc_'.$this->selectede_lang );
					if ( ( $metadesc === '' && $post_type !== '' ) && ( isset( $this->options['metadesc-' . $post_type] ) && $this->options['metadesc-' . $post_type] !== '' ) ) {
						$metadesc = $this->etm_wpseo_replace_vars( $this->options['metadesc-' . $post_type], (array) $post );
					}
				} elseif ( is_category() || is_tag() || is_tax() ) {
					$term = $wp_query->get_queried_object();
					$metadesc = WPSEO_Taxonomy_Meta::get_term_meta( $term, $term->taxonomy, 'desc' );
					if ( ( ! is_string( $metadesc ) || $metadesc === '' ) && ( ( is_object( $term ) && isset( $term->taxonomy ) ) && ( isset( $this->options['metadesc-tax-' . $term->taxonomy] ) && $this->options['metadesc-tax-' . $term->taxonomy] !== '' ) ) ) {
						$metadesc = $this->etm_wpseo_replace_vars( $this->options['metadesc-tax-' . $term->taxonomy], (array) $term );
					}
				} elseif ( is_author() ) {
					$author_id = get_query_var( 'author' );
					$metadesc  = get_the_author_meta( 'wpseo_metadesc', $author_id );
					if ( ! $metadesc && $this->options['metadesc-author-wpseo'] !== '' ) {
						$metadesc = $this->etm_wpseo_replace_vars( $this->options['metadesc-author-wpseo'], (array) $wp_query->get_queried_object() );
					}
				} elseif ( is_post_type_archive() ) {
					$post_type = get_query_var( 'post_type' );
					if ( isset( $this->options['metadesc-ptarchive-' . $post_type] ) && $this->options['metadesc-ptarchive-' . $post_type] !== '' ) {
						$metadesc = $this->etm_wpseo_replace_vars( $this->options['metadesc-ptarchive-' . $post_type], (array) $wp_query->get_queried_object() );
					}
				} elseif ( is_archive() ) {
					if ( $this->options['metadesc-archive-wpseo'] !== '' ) {
						$metadesc = $this->etm_wpseo_replace_vars( $this->options['metadesc-archive-wpseo'], (array) $wp_query->get_queried_object() );
					}
				}
			}

			/**
			 * Filter: 'wpseo_metadesc' - Allow changing the WP SEO meta description sentence.
			 *
			 * @api string $metadesc The description sentence.
			 */
			if(!empty($translatede_metadesc)){
				$metadesc = $translatede_metadesc;
			}			 
			 
			$metadesc = apply_filters( 'wpseo_metadesc', trim( $metadesc ) );

			if(!empty($translatede_metadesc)){
				$metadesc = $translatede_metadesc;
			}

			if ( $echo !== false ) {
				if ( is_string( $metadesc ) && $metadesc !== '' ) {
					echo '<meta name="description" content="' . esc_attr( strip_tags( stripslashes( $metadesc ) ) ) . '"/>' . "\n";
				} elseif ( current_user_can( 'manage_options' ) && is_singular() ) {
					echo '<!-- ' . __( 'Admin only notice: this page doesn\'t show a meta description because it doesn\'t have one, either write it for this page specifically or go into the SEO -> Titles menu and set up a template.', 'wordpress-seo' ) . ' -->' . "\n";
				}
			} else {
				return $metadesc;
			}
		}

		/**
		 * Based on the redirect meta value, this function determines whether it should redirect the current post / page.
		 *
		 * @return mixed
		 */
		function page_redirect() {
			if ( is_singular() ) {
				global $post;
				if ( ! isset( $post ) || ! is_object( $post ) ) {
					return;
				}
				$redir = WPSEO_Meta::get_value( 'redirect', $post->ID );
				if ( $redir !== '' ) {
					wp_redirect( $redir, 301 );
					exit;
				}
			}
		}

		/**
		 * Outputs noindex values for the current page.
		 */
		public function noindex_page() {
			echo '<meta name="robots" content="noindex" />' . "\n";
		}

		/**
		 * Send a Robots HTTP header preventing URL from being indexed in the search results while allowing search engines
		 * to follow the links in the object at the URL.
		 *
		 * @since 1.1.7
		 */
		public function noindex_feed() {
			if ( ( is_feed() || is_robots() ) && headers_sent() === false ) {
				header( 'X-Robots-Tag: noindex,follow', true );
			}
		}

		/**
		 * Adds rel="nofollow" to a link, only used for login / registration links.
		 *
		 * @param string $input The link element as a string.
		 *
		 * @return string
		 */
		public function nofollow_link( $input ) {
			return str_replace( '<a ', '<a rel="nofollow" ', $input );
		}

		/**
		 * When certain archives are disabled, this redirects those to the homepage.
		 */
		function archive_redirect() {
			global $wp_query;

			if (
					( $this->options['disable-date'] === true && $wp_query->is_date ) ||
					( $this->options['disable-author'] === true && $wp_query->is_author ) ||
					( isset( $this->options['disable-post_formats'] ) && $this->options['disable-post_formats'] && $wp_query->is_tax( 'post_format' ) )
			) {
				wp_safe_redirect( get_bloginfo( 'url' ), 301 );
				exit;
			}
		}

		/**
		 * If the option to redirect attachments to their parent is checked, this performs the redirect.
		 *
		 * An extra check is done for when the attachment has no parent.
		 */
		function attachment_redirect() {
			global $post;
			if ( is_attachment() && ( ( is_object( $post ) && isset( $post->post_parent ) ) && ( is_numeric( $post->post_parent ) && $post->post_parent != 0 ) ) ) {
				wp_safe_redirect( get_permalink( $post->post_parent ), 301 );
				exit;
			}
		}

		/**
		 * Trailing slashes for everything except is_single().
		 *
		 * Thanks to Mark Jaquith for this code.
		 *
		 * @param string $url
		 * @param string $type
		 *
		 * @return string
		 */
		function add_trailingslash( $url, $type ) {
			if ( 'single' === $type || 'single_paged' === $type ) {
				return $url;
			} else {
				return trailingslashit( $url );
			}
		}

		/**
		 * Removes the ?replytocom variable from the link, replacing it with a #comment-<number> anchor.
		 *
		 * @todo Should this function also allow for relative urls ?
		 *
		 * @param string $link The comment link as a string.
		 *
		 * @return string
		 */
		public function remove_reply_to_com( $link ) {
			return preg_replace( '`href=(["\'])(?:.*(?:\?|&|&#038;)replytocom=(\d+)#respond)`', 'href=$1#comment-$2', $link );
		}

		/**
		 * Redirect out the ?replytocom variables when cleanreplytocom is enabled
		 *
		 * @since 1.4.13
		 */
		function replytocom_redirect() {
			if ( $this->options['cleanreplytocom'] !== true ) {
				return;
			}

			if ( isset( $_GET['replytocom'] ) && is_singular() ) {
				global $post;
				$url          = get_permalink( $post->ID );
				$hash         = sanitize_text_field( $_GET['replytocom'] );
				$query_string = remove_query_arg( 'replytocom', $_SERVER['QUERY_STRING'] );
				if ( ! empty( $query_string ) ) {
					$url .= '?' . $query_string;
				}
				$url .= '#comment-' . $hash;
				wp_safe_redirect( $url, 301 );
				exit;
			}
		}

		/**
		 * Removes unneeded query variables from the URL.
		 */
		public function clean_permalink() {
			if ( is_robots() || get_query_var( 'sitemap' ) )
				return;

			global $wp_query;

			// Recreate current URL
			$cururl = 'http';
			if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) {
				$cururl .= 's';
			}
			$cururl .= '://';
			if ( $_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443' ) {
				$cururl .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
			} else {
				$cururl .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			}

			$properurl = '';

			if ( is_singular() ) {
				global $post;
				if ( empty( $post ) ) {
					$post = $wp_query->get_queried_object();
				}

				$properurl = get_permalink( $post->ID );

				$page = get_query_var( 'page' );
				if ( $page && $page != 1 ) {
					$post       = get_post( $post->ID );
					$page_count = substr_count( $post->post_content, '<!--nextpage-->' );
					if ( $page > ( $page_count + 1 ) ) {
						$properurl = user_trailingslashit( trailingslashit( $properurl ) . ( $page_count + 1 ) );
					} else {
						$properurl = user_trailingslashit( trailingslashit( $properurl ) . $page );
					}
				}

				// Fix reply to comment links, whoever decided this should be a GET variable?
				$result = preg_match( '`(\?replytocom=[^&]+)`', $_SERVER['REQUEST_URI'], $matches );
				if ( $result ) {
					$properurl .= str_replace( '?replytocom=', '#comment-', $matches[0] );
				}

				// Prevent cleaning out posts & page previews for people capable of viewing them
				if ( isset( $_GET['preview'] ) && isset( $_GET['preview_nonce'] ) && current_user_can( 'edit_post' ) ) {
					$properurl = '';
				}
			} elseif ( is_front_page() ) {
				if ( $this->is_home_posts_page() ) {
					$properurl = get_bloginfo( 'url' ) . '/';
				} elseif ( $this->is_home_static_page() ) {
					global $post;
					$properurl = get_permalink( $post->ID );
				}
			} elseif ( is_category() || is_tag() || is_tax() ) {
				$term = $wp_query->get_queried_object();
				if ( is_feed() ) {
					$properurl = get_term_feed_link( $term->term_id, $term->taxonomy );
				} else {
					$properurl = get_term_link( $term, $term->taxonomy );
				}
			} elseif ( is_search() ) {
				$s         = preg_replace( '`(%20|\+)`', ' ', get_search_query() );
				$properurl = get_bloginfo( 'url' ) . '/?s=' . rawurlencode( $s );
			} elseif ( is_404() ) {
				if ( function_exists( 'is_multisite' ) && is_multisite() && ! is_subdomain_install() && is_main_site() ) {
					if ( $cururl == get_bloginfo( 'url' ) . '/blog/' || $cururl == get_bloginfo( 'url' ) . '/blog' ) {
						if ( $this->is_home_static_page() ) {
							$properurl = get_permalink( get_option( 'page_for_posts' ) );
						} else {
							$properurl = get_bloginfo( 'url' ) . '/';
						}
					}
				}
			}

			if ( ! empty( $properurl ) && $wp_query->query_vars['paged'] != 0 && $wp_query->post_count != 0 ) {
				if ( is_search() ) {
					$properurl = get_bloginfo( 'url' ) . '/page/' . $wp_query->query_vars['paged'] . '/?s=' . rawurlencode( get_search_query() );
				} else {
					$properurl = user_trailingslashit( trailingslashit( $properurl ) . 'page/' . $wp_query->query_vars['paged'] );
				}
			}

			// Prevent cleaning out the WP Subscription managers interface for everyone
			if ( isset( $_GET['wp-subscription-manager'] ) ) {
				$properurl = '';
			}

			/**
			 * Filter: 'wpseo_whitelist_permalink_vars' - Allow plugins to register their own variables not to clean
			 *
			 * @api array $unsigned Array of permalink variables _not_ to clean. Empty by default.
			 */
			$whitelisted_extravars = apply_filters( 'wpseo_whitelist_permalink_vars', array() );

			if ( $this->options['cleanpermalink-googlesitesearch'] === true ) {
				// Prevent cleaning out Google Site searches
				$whitelisted_extravars = array_merge( $whitelisted_extravars, array( 'q', 'cx', 'debug', 'cof', 'ie', 'sa' ) );
			}

			if ( $this->options['cleanpermalink-googlecampaign'] === true ) {
				// Prevent cleaning out Google Analytics campaign variables
				$whitelisted_extravars = array_merge( $whitelisted_extravars, array( 'utm_campaign', 'utm_medium', 'utm_source', 'utm_content', 'utm_term', 'utm_id', 'gclid' ) );
			}

			if ( $this->options['cleanpermalink-extravars'] !== '' ) {
				$extravars             = explode( ',', $this->options['cleanpermalink-extravars'] );
				$extravars             = array_map( 'trim', $extravars );
				$whitelisted_extravars = array_merge( $whitelisted_extravars, $extravars );
				unset( $extravars );
			}

			foreach ( $whitelisted_extravars as $get ) {
				if ( isset( $_GET[trim( $get )] ) ) {
					$properurl = '';
				}
			}

			if ( ! empty( $properurl ) && $cururl != $properurl ) {
				wp_safe_redirect( $properurl, 301 );
				exit;
			}
		}

		/**
		 * Replaces the possible RSS variables with their actual values.
		 *
		 * @param string $content The RSS content that should have the variables replaced.
		 *
		 * @return string
		 */
		function rss_replace_vars( $content ) {
			global $post;

			/**
			 * Allow the developer to determine whether or not to follow the links in the bits WP SEO adds to the RSS feed, defaults to true.
			 *
			 * @api bool $unsigned Whether or not to follow the links in RSS feed, defaults to true.
			 *
			 * @since 1.4.20
			 */
			$no_follow      = apply_filters( 'nofollow_rss_links', true );
			$no_follow_attr = '';
			if ( $no_follow === true ) {
				$no_follow_attr = 'rel="nofollow" ';
			}

			$author_link = '';
			if ( is_object( $post ) ) {
				$author_link = '<a ' . $no_follow_attr . 'rel="author" href="' . esc_url( get_author_posts_url( $post->post_author ) ) . '">' . get_the_author() . '</a>';
			}

			$post_link      = '<a ' . $no_follow_attr . 'href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a>';
			$blog_link      = '<a ' . $no_follow_attr . 'href="' . esc_url( get_bloginfo( 'url' ) ) . '">' . get_bloginfo( 'name' ) . '</a>';
			$blog_desc_link = '<a ' . $no_follow_attr . 'href="' . esc_url( get_bloginfo( 'url' ) ) . '">' . get_bloginfo( 'name' ) . ' - ' . get_bloginfo( 'description' ) . '</a>';

			$content = stripslashes( trim( $content ) );
			$content = str_replace( '%%AUTHORLINK%%', $author_link, $content );
			$content = str_replace( '%%POSTLINK%%', $post_link, $content );
			$content = str_replace( '%%BLOGLINK%%', $blog_link, $content );
			$content = str_replace( '%%BLOGDESCLINK%%', $blog_desc_link, $content );

			return $content;
		}

		/**
		 * Adds the RSS footer (or header) to the full RSS feed item.
		 *
		 * @param string $content Feed item content.
		 *
		 * @return string
		 */
		function embed_rssfooter( $content ) {
			if ( is_feed() ) {
				$content = $this->embed_rss( $content, 'full' );
			}

			return $content;
		}

		/**
		 * Adds the RSS footer (or header) to the excerpt RSS feed item.
		 *
		 * @param string $content Feed item excerpt.
		 *
		 * @return string
		 */
		function embed_rssfooter_excerpt( $content ) {
			if ( is_feed() ) {
				$content = $this->embed_rss( $content, 'excerpt' );
			}

			return $content;
		}

		/**
		 * Adds the RSS footer and/or header to an RSS feed item.
		 *
		 * @since 1.4.14
		 *
		 * @param string $content Feed item content.
		 * @param string $context Feed item context, either 'excerpt' or 'full'.
		 *
		 * @return string
		 */
		function embed_rss( $content, $context = 'full' ) {
			if ( is_feed() ) {

				$before = '';
				$after  = '';

				if ( $this->options['rssbefore'] !== '' ) {
					$before = wpautop( $this->rss_replace_vars( $this->options['rssbefore'] ) );
				}
				if ( $this->options['rssafter'] !== '' ) {
					$after = wpautop( $this->rss_replace_vars( $this->options['rssafter'] ) );
				}
				if ( $before !== '' || $after !== '' ) {
					if ( ( isset( $context ) && $context === 'excerpt' ) && trim( $content ) !== '' ) {
						$content = wpautop( $content );
					}
					$content = $before . $content . $after;
				}
			}

			return $content;
		}


		/**
		 * Used in the force rewrite functionality this retrieves the output, replaces the title with the proper SEO
		 * title and then flushes the output.
		 */
		function flush_cache() {
			global $wp_query, $wpseo_ob, $sep;

			if ( ! $wpseo_ob )
				return;

			$content = ob_get_contents();

			$old_wp_query = $wp_query;

			wp_reset_query();

			$title = $this->title( '', $sep );

			// Find all titles, strip them out and add the new one in within the debug marker, so it's easily identified whether a site uses force rewrite.
			if ( preg_match_all( '`<title>(.*)?<\/title>`i', $content, $matches ) ) {
				$count = count( $matches[0] );
				if ( $count > 0 ) {
					$i = 0;
					while ( $count > $i ) {
						$content = str_replace( $matches[0][$i], '', $content );
						$i ++;
					}
				}
			}
			$content = str_replace( $this->debug_marker( false ), $this->debug_marker( false ) . "\n" . '<title>' . $title . '</title>', $content );

			ob_end_clean();

			$GLOBALS['wp_query'] = $old_wp_query;

			echo $content;
		}

		/**
		 * Starts the output buffer so it can later be fixed by flush_cache()
		 */
		function force_rewrite_output_buffer() {
			global $wpseo_ob;
			$wpseo_ob = true;
			ob_start();
		}

		/**
		 * Function used in testing whether the title should be force rewritten or not.
		 *
		 * @param string $title
		 *
		 * @return string
		 */
		function title_test_helper( $title ) {
			$wpseo_titles = get_option( 'wpseo_titles' );

			$wpseo_titles['title_test']++;
			update_option( 'wpseo_titles', $wpseo_titles );

			// Prevent this setting from being on forever when something breaks, as it breaks caching.
			if ( $wpseo_titles['title_test'] > 5 ) {
				$wpseo_titles['title_test'] = 0;
				update_option( 'wpseo_titles', $wpseo_titles );

				remove_filter( 'wpseo_title', array( $this, 'title_test_helper' ) );
				return $title;
			}

			if ( ! defined( 'DONOTCACHEPAGE' ) ) {
				define( 'DONOTCACHEPAGE', true );
			}
			if ( ! defined( 'DONOTCACHCEOBJECT' ) ) {
				define( 'DONOTCACHCEOBJECT', true );
			}
			if ( ! defined( 'DONOTMINIFY' ) ) {
				define( 'DONOTMINIFY', true );
			}

			global $wp_version;
			if ( $_SERVER['HTTP_USER_AGENT'] == "WordPress/${wp_version}; " . get_bloginfo( 'url' ) . ' - Yoast' ) {
				return 'This is a Yoast Test Title';
			}

			return $title;
		}
		
		function etm_wpseo_replace_vars( $string, $args, $omit = array() ) {
		
			$args = (array) $args;
		
			$string = strip_tags( $string );
		
			// Let's see if we can bail super early.
			if ( strpos( $string, '%%' ) === false ) {
				return trim( preg_replace( '`\s+`u', ' ', $string ) );
			}
		
			global $sep;
			if ( ! isset( $sep ) || empty( $sep ) ) {
				$sep = '-';
			}
		
			$simple_replacements = array(
				'%%sep%%'          => $sep,
				'%%sitename%%'     => get_bloginfo( 'name' ),
				'%%sitedesc%%'     => get_bloginfo( 'description' ),
				'%%currenttime%%'  => date( get_option( 'time_format' ) ),
				'%%currentdate%%'  => date( get_option( 'date_format' ) ),
				'%%currentday%%'   => date( 'j' ),
				'%%currentmonth%%' => date( 'F' ),
				'%%currentyear%%'  => date( 'Y' ),
			);
		
			foreach ( $simple_replacements as $var => $repl ) {
				$string = str_replace( $var, $repl, $string );
			}
		
			// Let's see if we can bail early.
			if ( strpos( $string, '%%' ) === false ) {
				return trim( preg_replace( '`\s+`u', ' ', $string ) );
			}
		
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
				'term404'		=> '',
			);
		
			if ( isset( $args['post_content'] ) ) {
				$args['post_content'] = wpseo_strip_shortcode( $args['post_content'] );
			}
			if ( isset( $args['post_excerpt'] ) ) {
				$args['post_excerpt'] = wpseo_strip_shortcode( $args['post_excerpt'] );
			}
		
			$r = (object) wp_parse_args( $args, $defaults );
		
			$max_num_pages = 1;
			if ( ! is_singular() ) {
				$pagenum = get_query_var( 'paged' );
				if ( $pagenum === 0 ) {
					$pagenum = 1;
				}
		
				if ( isset( $wp_query->max_num_pages ) && $wp_query->max_num_pages != '' && $wp_query->max_num_pages != 0 ) {
					$max_num_pages = $wp_query->max_num_pages;
				}
			}
			else {
				global $post;
				$pagenum       = get_query_var( 'page' );
				$max_num_pages = ( isset( $post->post_content ) ) ? substr_count( $post->post_content, '<!--nextpage-->' ) : 1;
				if ( $max_num_pages >= 1 ) {
					$max_num_pages++;
				}
			}
		
			// Let's do date first as it's a bit more work to get right.
			if ( $r->post_date != '' ) {
				$date = mysql2date( get_option( 'date_format' ), $r->post_date );
			}
			else {
				if ( get_query_var( 'day' ) && get_query_var( 'day' ) != '' ) {
					$date = get_the_date();
				}
				else {
					if ( single_month_title( ' ', false ) && single_month_title( ' ', false ) != '' ) {
						$date = single_month_title( ' ', false );
					}
					elseif ( get_query_var( 'year' ) != '' ) {
						$date = get_query_var( 'year' );
					}
					else {
						$date = '';
					}
				}
			}
		
			$replacements = array(
				'%%date%%'         => $date,
				'%%searchphrase%%' => esc_html( get_query_var( 's' ) ),
				'%%page%%'         => ( $max_num_pages > 1 && $pagenum > 1 ) ? sprintf( $sep . ' ' . __( 'Page %d of %d', 'wordpress-seo' ), $pagenum, $max_num_pages ) : '',
				'%%pagetotal%%'    => $max_num_pages,
				'%%pagenumber%%'   => $pagenum,
				'%%term404%%'	   => sanitize_text_field( str_replace( '-', ' ', $r->term404 ) ),
			);
		
			if ( isset( $r->ID ) ) {
			
				$translatede_title = get_post_meta($r->ID, 'ect_tran_title_'.$this->selectede_lang, true);
		
				if(empty($translatede_title)){
					$translatede_title = $r->post_title ;
				}
			
			
				$replacements = array_merge(
					$replacements, array(
						'%%caption%%'      => $r->post_excerpt,
						'%%category%%'     => wpseo_get_terms( $r->ID, 'category' ),
						'%%excerpt%%'      => ( ! empty( $r->post_excerpt ) ) ? strip_tags( $r->post_excerpt ) : wp_html_excerpt( strip_shortcodes( $r->post_content ),155 ),
						'%%excerpt_only%%' => strip_tags( $r->post_excerpt ),
						'%%focuskw%%'      => WPSEO_Meta::get_value( 'focuskw', $r->ID ),
						'%%id%%'           => $r->ID,
						'%%modified%%'     => mysql2date( get_option( 'date_format' ), $r->post_modified ),
						'%%name%%'         => get_the_author_meta( 'display_name', ! empty( $r->post_author ) ? $r->post_author : get_query_var( 'author' ) ),
						'%%tag%%'          => wpseo_get_terms( $r->ID, 'post_tag' ),
						'%%title%%'        => stripslashes( $translatede_title ),
						'%%userid%%'       => ! empty( $r->post_author ) ? $r->post_author : get_query_var( 'author' ),
					)
				);
			}
		
			if ( ! empty( $r->taxonomy ) ) {
				$replacements = array_merge(
					$replacements, array(
						'%%category_description%%' => trim( strip_tags( get_term_field( 'description', $r->term_id, $r->taxonomy ) ) ),
						'%%tag_description%%'      => trim( strip_tags( get_term_field( 'description', $r->term_id, $r->taxonomy ) ) ),
						'%%term_description%%'     => trim( strip_tags( get_term_field( 'description', $r->term_id, $r->taxonomy ) ) ),
						'%%term_title%%'           => $r->name,
					)
				);
			}
		
			/**
			* Filter: 'wpseo_replacements' - Allow customization of the replacements before they are applied
			*
			* @api array $replacements The replacements
			*/
			$replacements = apply_filters( "wpseo_replacements", $replacements );
		
			foreach ( $replacements as $var => $repl ) {
				if ( ! in_array( $var, $omit ) ) {
					$string = str_replace( $var, $repl, $string );
				}
			}
		
			if ( strpos( $string, '%%' ) === false ) {
				$string = preg_replace( '`\s+`u', ' ', $string );
				return trim( $string );
			}
		
			if ( isset( $wp_query->query_vars['post_type'] ) && preg_match_all( '`%%pt_([^%]+)%%`u', $string, $matches, PREG_SET_ORDER ) ) {
				$pt        = get_post_type_object( $wp_query->query_vars['post_type'] );
				$pt_plural = $pt_singular = $pt->name;
				if ( isset( $pt->labels->singular_name ) ) {
					$pt_singular = $pt->labels->singular_name;
				}
				if ( isset( $pt->labels->name ) ) {
					$pt_plural = $pt->labels->name;
				}
				$string = str_replace( '%%pt_single%%', $pt_singular, $string );
				$string = str_replace( '%%pt_plural%%', $pt_plural, $string );
			}
		
			if ( is_singular() && preg_match_all( '`%%cf_([^%]+)%%`u', $string, $matches, PREG_SET_ORDER ) ) {
				global $post;
				foreach ( $matches as $match ) {
					$string = str_replace( $match[0], get_post_meta( $post->ID, $match[1], true ), $string );
				}
			}
		
			if ( preg_match_all( '`%%ct_desc_([^%]+)?%%`u', $string, $matches, PREG_SET_ORDER ) ) {
				global $post;
				foreach ( $matches as $match ) {
					$terms = get_the_terms( $post->ID, $match[1] );
					if ( is_array( $terms ) && $terms !== array() ) {
						$term   = current( $terms );
						$string = str_replace( $match[0], get_term_field( 'description', $term->term_id, $match[1] ), $string );
					}
					else {
						// Make sure that the variable is removed ?
						$string = str_replace( $match[0], '', $string );
		
						/* Check for WP_Error object (=invalid taxonomy entered) and if it's an error,
						 notify in admin dashboard */
						if ( is_wp_error( $terms ) && is_admin() ) {
							add_action( 'admin_notices', 'wpseo_invalid_custom_taxonomy' );
						}
					}
				}
			}
		
			if ( preg_match_all( '`%%ct_([^%]+)%%(single%%)?`u', $string, $matches, PREG_SET_ORDER ) ) {
				foreach ( $matches as $match ) {
					$single = false;
					if ( isset( $match[2] ) && $match[2] == 'single%%' ) {
						$single = true;
					}
					$ct_terms = wpseo_get_terms( $r->ID, $match[1], $single );
		
					$string = str_replace( $match[0], $ct_terms, $string );
				}
			}
		
			$string = preg_replace( '`\s+`u', ' ', $string );
			return trim( $string );
		}

		
		

	} /* End of class */

} /* End of class-exists wrapper */

global $ect_wpseo_front;
$ect_wpseo_front = new WPSEO_Frontend_etm;