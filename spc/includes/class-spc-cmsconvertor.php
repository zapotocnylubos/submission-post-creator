<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/zapotocnylubos
 * @since      1.0.0
 *
 * @package    Spc
 * @subpackage Spc/includes
 */

/**
 * main meaning
 *
 * description
 *
 * @since      1.0.0
 * @package    Spc
 * @subpackage Spc/includes
 * @author     Lubos Zapotocny <zapotocnylubos@gmail.com>
 */
class CMSConvertor {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function convert($text, $values) {
        preg_match_all('~%%(.*?)%%~s', $text, $datas);

        $Html = $text;
        foreach($datas[1] as $value){
            $Html = str_replace($value, $values[$value], $Html);
        }
        $Html = str_replace(array("%%","%%"),'',$Html);

        return $Html;
    }

}
