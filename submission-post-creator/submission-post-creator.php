<?php
/*
Plugin name: Submission post creator
Plugin URI: http://lubos3d.cz
Version: 1.0
Author: Lubos Zapotocny
Author URI: http://lubos3d.cz
License: GPL2
*/

function process_cms($string, $replaceValues){
    preg_match_all('~%%(.*?)%%~s', $string, $datas);

    $Html = $string;
    foreach($datas[1] as $value){           
        $Html = str_replace($value, $replaceValues[$value], $Html);
    }
    $Html = str_replace(array("%%","%%"),'',$Html);

    return $Html;
}

add_action('hf_form_success', 'create_registration_post', 10, 2);
    
function create_registration_post($submission, $form){
    $postBody = process_cms(get_option('competition-post-content'), $submission->data);
    $postTitle = process_cms(get_option('competition-post-title'), $submission->data);

    $new_post = [
     'post_title'    => $postTitle,
     'post_content'  => $postBody,
     'post_status'   => 'pending',
     'post_author'   => 1,
     'post_category' => [get_option('competition-postcategory')]  // prihlasene-projekty
     ];
    
    // Insert the post into the database
    wp_insert_post( $new_post );
}

add_action('admin_menu', function(){
    add_menu_page( 'Submission post creator', 'Submission post creator', 'manage_options', 'submission-post-plugin', 'competition_page_settings' );
});

add_action( 'admin_post_print.csv', 'csv_submissions_pull' );

add_action("admin_init", function(){
    add_settings_section("competition", "Konfigurace", null, "competition-page");
    
    add_settings_field("competition-postcategory", "Do které kategorie postovat?", "postcategory_select_display", "competition-page", "competition"); 
    register_setting("competition", "competition-postcategory");

    add_settings_field("competition-post-title", "Titulek příspěvku", "post_title_display", "competition-page", "competition"); 
    register_setting("competition", "competition-post-title");

    add_settings_field("competition-post-content", "Obsah příspěvku", "post_textarea_display", "competition-page", "competition"); 
    register_setting("competition", "competition-post-content");

});

 
function competition_page_settings(){
    ?>
      <div class="wrap">
         <h1>Submission post creator plugin</h1>
  
         <form method="post" action="options.php">
            <?php
               settings_fields("competition");
  
               do_settings_sections("competition-page");
                 
               submit_button(); 
            ?>

            <hr>
            <a href="<?php echo admin_url( 'admin-post.php?action=print.csv' ) ?>">CSV DATA EXPORT</a> <small>(utf-8 encoding)</small>
         </form>
      </div>
   <?php
}

function postcategory_select_display()
{
    $categories = get_categories(array('hide_empty' => false));

    echo '<select name="competition-postcategory">';

    foreach($categories as $category) {
        $name = $category->name;
        $id = $category->term_id;
        echo '<option value="' .$id. '" ' .selected(get_option('competition-postcategory'), $id). '>' .$name. '</option>';
    }
    echo '</select>';
}

function post_title_display(){
    echo '<input name="competition-post-title" type="text" value="'.get_option('competition-post-title').'" style="width: 100%;">';
}

function post_textarea_display()
{
    $settings = array( 'textarea_name' => 'competition-post-content' );

    wp_editor( get_option('competition-post-content'), 'competition-post-content-editor', $settings );
}

function csv_submissions_pull() {
    if ( ! current_user_can( 'manage_options' ) )
        return;    

    global $wpdb;

    $results = $wpdb->get_results("SELECT s.* FROM {$wpdb->prefix}hf_submissions s;", OBJECT);

    $datas = [];
    foreach($results as $result) 
    { 
        $submission = empty( $result->data ) ? array() : (array) json_decode( $result->data, true );
        $submission['submitted_at'] =  $result->submitted_at;

        $datas[] = $submission;
    }

    if (empty($datas)) {
      return;
    }

    $csv_output = '"'.implode('","',array_keys($datas[0])).'"';
  
    foreach ($datas as $row) {
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