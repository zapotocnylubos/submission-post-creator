
<select name="spc-poststatus">
    <option>-----</option>

    <?php
    $statuses = [
        'publish' => "Publikováno",
        'pending' => "Čeká na schválení",
        'draft' => "Koncept"
    ];
    foreach($statuses as $statusName => $statusTitle) {
        echo '<option value="' .$statusName. '" ' .selected(get_option('spc-poststatus'), $statusName). '>' .$statusTitle. '</option>';
    }
    ?>

</select>