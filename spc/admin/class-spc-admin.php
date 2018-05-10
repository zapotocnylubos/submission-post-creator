<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/zapotocnylubos
 * @since      1.0.0
 *
 * @package    Spc
 * @subpackage Spc/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Spc
 * @subpackage Spc/admin
 * @author     Lubos Zapotocny <zapotocnylubos@gmail.com>
 */
class Spc_Admin {

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

    private $partialsPath = '';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->partialsPath = plugin_dir_path( __FILE__ ) . 'partials/';

	}


	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/spc-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/spc-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function settings_page() {
		add_menu_page(__('SPC page', 'spc'), __('Post creator', 'spc'), 'manage_options', 'spc', function() {

		    /** Admin settings template */
		    include(plugin_dir_path( __FILE__ ) . 'partials/spc-admin-display.php');
        });
	}

	public function settings_init() {
	    $settingsSection = "spc-configuration";
	    $settingsPage = "spc-page";

        add_settings_section($settingsSection, __("Configuration", 'spc'), null, $settingsPage);

        add_settings_field('spc-postcategory', __('Post category', 'spc'), function() {
            include($this->partialsPath . 'spc-postcategory.php');
        }, $settingsPage, $settingsSection);
        register_setting($settingsSection, 'spc-postcategory');

        add_settings_field('spc-newscategory', __('News category', 'spc'), function() {
            include($this->partialsPath . 'spc-newscategory.php');
        }, $settingsPage, $settingsSection);
        register_setting($settingsSection, 'spc-newscategory');

        add_settings_field('spc-poststatus', __('Post status', 'spc'), function() {
            include($this->partialsPath . 'spc-poststatus.php');
        }, $settingsPage, $settingsSection);
        register_setting($settingsSection, 'spc-poststatus');

        add_settings_field('spc-registrationform', __('Registration form', 'spc'), function() {
            include($this->partialsPath . 'spc-registrationform.php');
        }, $settingsPage, $settingsSection);
        register_setting($settingsSection, 'spc-registrationform');

        add_settings_field('spc-posttitle', __('Post title', 'spc'), function() {
            echo '<input name="spc-posttitle" type="text" value="'.get_option('spc-posttitle').'" style="width: 100%;">';
        }, $settingsPage, $settingsSection);
        register_setting($settingsSection, 'spc-posttitle');

        add_settings_field('spc-postcontent', __('Post content', 'spc'), function() {
            $settings = array( 'textarea_name' => 'spc-postcontent' );
            wp_editor( get_option('spc-postcontent'), 'spc-postcontent-editor', $settings );
        }, $settingsPage, $settingsSection);
        register_setting($settingsSection, 'spc-postcontent');

        add_settings_field('spc-projectsolver', __('Project solver(s) field(s)', 'spc'), function() {
            echo '<input name="spc-projectsolver" type="text" value="'.get_option('spc-projectsolver').'" style="width: 100%;">';
        }, $settingsPage, $settingsSection);
        register_setting($settingsSection, 'spc-projectsolver');

        add_settings_field('spc-projectname', __('Project name field', 'spc'), function() {
            echo '<input name="spc-projectname" type="text" value="'.get_option('spc-projectname').'">';
        }, $settingsPage, $settingsSection);
        register_setting($settingsSection, 'spc-projectname');

        add_settings_field('spc-projectcategory', __('Project category field', 'spc'), function() {
            echo '<input name="spc-projectcategory" type="text" value="'.get_option('spc-projectcategory').'">';
        }, $settingsPage, $settingsSection);
        register_setting($settingsSection, 'spc-projectcategory');

        add_settings_field('spc-projectdescription', __('Project description field', 'spc'), function() {
            echo '<input name="spc-projectdescription" type="text" value="'.get_option('spc-projectdescription').'">';
        }, $settingsPage, $settingsSection);
        register_setting($settingsSection, 'spc-projectdescription');
    }

    public function create_registration_post($submission, $form) {
	    // handle only selected form
	    if($submission->form_id != get_option('spc-registrationform')) {
	        return;
        }

        $postBody = CMSConvertor::convert(get_option('spc-postcontent'), $submission->data);
        $postTitle = CMSConvertor::convert(get_option('spc-posttitle'), $submission->data);

        $new_post = [
            'post_title'    => $postTitle,
            'post_content'  => $postBody,
            'post_status'   => get_option('spc-poststatus'),
            'post_author'   => 1,
            'post_category' => [get_option('spc-postcategory')]  // prihlasene-projekty
        ];

        // Insert the post into the database
        wp_insert_post($new_post);
    }

    public function print_csv() {
        //TODO vzlepsit :]

        if ( ! current_user_can( 'manage_options' ) )
            return;

        $submissions = hf_get_form_submissions(get_option('spc-registrationform'), ['limit' => 50000]);

        $columns = array();
        foreach( $submissions as $s ) {
            if( ! is_array( $s->data ) ) {
                continue;
            }

            foreach( $s->data as $field => $value ) {
                if (!array_key_exists($field, $columns)) {
                    $columns[$field] = true;
                }
            }
        }
        $columns = array_keys( $columns );

        $datas = [];
        foreach($submissions as $submission)
        {
            $submissionData = [];

            foreach( $columns as $column ) {
                //$data = empty( $result->data ) ? array() : (array) json_decode( $result->data, true );
                $submissionData[$column] = isset( $submission->data[ $column ] ) ? $submission->data[ $column ] : '';
            }

            //$submission = empty( $result->data ) ? array() : (array) json_decode( $result->data, true );
            $submissionData['submitted_at'] =  $submission->submitted_at;

            $datas[] = $submissionData;
        }

        if (empty($datas)) {
            return;
        }

        $csv_output = '"'.implode('","',array_keys($datas[0])).'"';

        foreach ($datas as $row) {
            foreach($row as $key => $value){
                $row[$key] = str_replace(["'", '"'], '', $value);
            }

            $csv_output .= "\r\n";
            $with_breaks = '"'.implode('","',$row).'"';
            $csv_output .= str_replace(array("\n", "\r"), '', $with_breaks);

        }

        $filename = "export_".date("Y-m-d_H-i",time());

        header('Content-Type: application/csv; charset=utf-8');
        header("Content-disposition: filename=".$filename.".csv");
        header('Pragma: no-cache');

        print $csv_output;

        die();
    }

}
