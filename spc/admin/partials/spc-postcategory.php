
<select name="spc-postcategory">
    <option>-----</option>

    <?php
    $categories = get_categories(['hide_empty' => false]);

    foreach($categories as $category) {
        $name = $category->name;
        $id = $category->term_id;
        echo '<option value="' . $id . '" ' . selected(get_option('spc-postcategory'), $id) . '>' . $name . '</option>';
    }
    ?>

</select>