<?php
	
	namespace ElementPack\Includes\Controls\SelectInput;
	
	defined( 'ABSPATH' ) || die();
	
	class ElementPack_Dynamic_Select_Input_Module {
		
		const ACTION = '';
		
		private static $instance = null;
		
		/**
		 * Returns the instance.
		 *
		 * @return object
		 * @since  1.0.0
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			
			return self::$instance;
		}
		
		/**
		 * Init method
		 */
		
		/**
		 * Constructor.
		 */
		public function init() {
			add_action( 'wp_ajax_elementpack_dynamic_select_input_data', array( $this, 'getSelectInputData' ) );
		}
		
		/**
		 * get Ajax Data
		 */
		public function getSelectInputData() {
			$nonce = isset( $_POST['security'] ) ? $_POST['security'] : '';
			
			try {
				if ( ! wp_verify_nonce( $nonce, 'ep_dynamic_select' ) ) {
					throw new \Exception( 'Invalid request' );
				}
				
				if ( ! current_user_can( 'edit_posts' ) ) {
					throw new \Exception( 'Unauthorized request' );
				}
				
				$query = isset( $_POST['query'] ) ? $_POST['query'] : '';
				
				if ( $query == 'terms' ) {
					$data = $this->getTerms();
				} else if ( $query == 'authors' ) {
					$data = $this->getAuthors();
				} else {
					$data = $this->getPosts();
				}
				
				wp_send_json_success( $data );
			} catch ( \Exception $e ) {
				wp_send_json_error( $e->getMessage() );
			}
			
			die();
		}
		
		/**
		 * Get Post Type
		 * @return string
		 */
		protected function getPostType() {
			return isset( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : '';
		}
		
		/**
		 * @return string[]|\WP_Post_Type[]
		 */
		protected function getAllPublicPostTypes() {
			return array_values( get_post_types( [ 'public' => true ] ) );
		}
		
		/**
		 * @return string
		 */
		protected function getSearchQuery() {
			return isset( $_POST['search_text'] ) ? sanitize_text_field( $_POST['search_text'] ) : '';
		}
		
		/**
		 * @return array|mixed
		 */
		protected function getselecedIds() {
			return isset( $_POST['ids'] ) ? $_POST['ids'] : [];
		}
		
		
		/**
		 * @param string $taxonomy
		 *
		 * @return mixed|string
		 */
		public function getTaxonomyName( $taxonomy = '' ) {
			$taxonomies = get_taxonomies( [ 'public' => true ], 'objects' );
			$taxonomies = array_column( $taxonomies, 'label', 'name' );
			
			return isset( $taxonomies[ $taxonomy ] ) ? $taxonomies[ $taxonomy ] : '';
		}
		
		/**
		 * @return string[]|\WP_Taxonomy[]
		 */
		protected function getAllPublicTaxonomies() {
			return array_values( get_taxonomies( [ 'public' => true ] ) );
		}
		
		/**
		 * Get Post Query Data
		 *
		 * @return array
		 */
		public function getPosts() {
			$include    = $this->getselecedIds();
			$searchText = $this->getSearchQuery();
			
			$args = [];
			
			if ( $this->getPostType() ) {
				$args['post_type'] = $this->getPostType();
			} else {
				$args['post_type'] = $this->getAllPublicPostTypes();
			}
			
			if ( ! empty( $include ) ) {
				$args['include']     = $include;
				$args['numberposts'] = count( $include );
			} else {
				$args['numberposts'] = 20;
			}
			
			if ( $searchText ) {
				$args['s'] = $searchText;
			}
			
			$posts = get_posts( $args );
			
			$data = [];
			
			if ( empty( $posts ) ) {
				return $data;
			}
			
			foreach ( $posts as $post ) {
				$data[] = [
					'id'   => $post->ID,
					'text' => strip_tags( $post->post_title ),
				];
			}
			
			return $data;
		}
		
		/**
		 * Get Terms query data
		 *
		 * @return array
		 */
		public function getTerms() {
			$search_text = $this->getSearchQuery();
			$taxonomies  = $this->getAllPublicTaxonomies();
			$include     = $this->getselecedIds();
			
			if ( $this->getPostType() ) {
				$post_taxonomies = get_object_taxonomies( $this->getPostType() );
				$taxonomies      = array_intersect( $post_taxonomies, $taxonomies );
			}
			
			$data = [];
			
			if ( empty( $taxonomies ) ) {
				return $data;
			}
			
			$args = [
				'taxonomy'   => $taxonomies,
				'hide_empty' => false,
			];
			
			if ( ! empty( $include ) ) {
				$args['include'] = $include;
			}
			
			if ( $search_text ) {
				$args['number'] = 20;
				$args['search'] = $search_text;
			}
			
			$terms = get_terms( $args );
			
			if ( is_wp_error( $terms ) || empty( $terms ) ) {
				return $data;
			}
			
			foreach ( $terms as $term ) {
				$label         = $term->name;
				$taxonomy_name = $this->getTaxonomyName( $term->taxonomy );
				
				if ( $taxonomy_name ) {
					$label = "{$taxonomy_name}: {$label}";
				}
				
				$data[] = [
					'id'   => $term->term_taxonomy_id,
					'text' => $label,
				];
			}
			
			return $data;
		}
		
		/**
		 * Get Authors query Data
		 *
		 * @return array
		 */
		public function getAuthors() {
			$include     = $this->getselecedIds();
			$search_text = $this->getSearchQuery();
			
			$args = [
				'fields'  => [ 'ID', 'display_name' ],
				'orderby' => 'display_name',
			];
			
			if ( ! empty( $include ) ) {
				$args['include'] = $include;
			}
			
			if ( $search_text ) {
				$args['number'] = 20;
				$args['search'] = "*$search_text*";
			}
			
			$users = get_users( $args );
			
			$data = [];
			
			if ( empty( $users ) ) {
				return $data;
			}
			
			foreach ( $users as $user ) {
				$data[] = [
					'id'   => $user->ID,
					'text' => $user->display_name,
				];
			}
			
			return $data;
		}
		
	}
	
	function elementPack_dynamic_select_input_module() {
		return ElementPack_Dynamic_Select_Input_Module::get_instance();
	}
	
	elementPack_dynamic_select_input_module()->init();
