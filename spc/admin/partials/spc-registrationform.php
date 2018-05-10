
<select name="spc-registrationform">
    <option>-----</option>

    <?php
    $forms = get_posts(['post_type' => 'html-form']);

    foreach($forms as $form) {
        $name = $form->post_title;
        $id = $form->ID;
        echo '<option value="' . $id . '" ' . selected(get_option('spc-registrationform'), $id) . '>' . $name . '</option>';
    }
    ?>

</select>