<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/zapotocnylubos
 * @since      1.0.0
 *
 * @package    Spc
 * @subpackage Spc/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Spc
 * @subpackage Spc/public
 * @author     Lubos Zapotocny <zapotocnylubos@gmail.com>
 */
class Spc_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Spc_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Spc_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/spc-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Spc_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Spc_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/spc-public.js', array( 'jquery' ), $this->version, false );

	}

	public function spc_rest_api() {
        register_rest_route( 'spc/v1/api', '/news/recent', [
            'methods' => 'GET',
            'callback' => [$this, 'spc_rest_news_recent']
        ]);

        register_rest_route( 'spc/v1/api', '/news', [
            'methods' => 'GET',
            'callback' => [$this, 'spc_rest_news']
        ]);

        register_rest_route( 'spc/v1/api', '/projects', [
            'methods' => 'GET',
            'callback' => [$this, 'spc_rest_projects']
        ]);


    }

    public function spc_rest_news() {
        $posts_array = get_posts( ['category' => get_option('spc-newscategory')] );
        $news = [];

        foreach ($posts_array as $post) {
            $news[] =  [
                'published_at' => $post->post_date,
                'title' => $post->post_title,
                'content' => $post->post_content,
                'link' => $post->guid
            ];
        }

        return $news;
    }

    public function spc_rest_news_recent() {
        $posts_array = get_posts( ['category' => get_option('spc-newscategory'), 'posts_per_page' => 5] );
        $news = [];

        foreach ($posts_array as $post) {
            $news[] =  [
                'published_at' => $post->post_date,
                'title' => $post->post_title,
                'content' => $post->post_content,
                'link' => $post->guid
            ];
        }

        return $news;
    }

    public function spc_rest_projects() {
	    $projects = [];

        $submissions = hf_get_form_submissions(get_option('spc-registrationform'), ['limit' => 50000]);

        foreach($submissions as $submission)
        {
            $subData = $submission->data;
//            $subData = empty( $submission->data ) ? array() : (array) $submission->data;
            $submissionData['submitted_at'] =  $submission->submitted_at;

            $project = [
                'authors' => [],
				'title' => array_key_exists(get_option('spc-projectname'), $subData ) ? $subData[get_option('spc-projectname')] : null,
				'category' => array_key_exists(get_option('spc-projectcategory'), $subData ) ? $subData[get_option('spc-projectcategory')] : null,
				'description' => array_key_exists(get_option('spc-projectdescription'), $subData ) ? $subData[get_option('spc-projectdescription')] : null
            ];

            $solverFields = explode(',', get_option('spc-projectsolver'));
            if(count($solverFields) >= 2) {
                foreach($solverFields as $solverField) {
                    $project['authors'][] = $subData[$solverField];
                }
            } else {
				$project['authors'][] = array_key_exists(get_option('spc-projectsolver'), $subData ) ? $subData[get_option('spc-projectsolver')] : null;
            }

            $projects[] = $project;
        }

        return $projects;
    }

}
