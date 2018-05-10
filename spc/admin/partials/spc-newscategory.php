
<select name="spc-newscategory">
    <option>-----</option>

    <?php
    $categories = get_categories(['hide_empty' => false]);

    foreach($categories as $category) {
        $name = $category->name;
        $id = $category->term_id;
        echo '<option value="' . $id . '" ' . selected(get_option('spc-newscategory'), $id) . '>' . $name . '</option>';
    }
    ?>

</select>