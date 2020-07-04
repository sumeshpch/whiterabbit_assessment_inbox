<?php

/**
 *
 * Util class
 * @package white rabbit
 * @author  sumesh <sumeshp@gmail.com>
 * @version SVN: $Id$
 */

namespace app\core;

use app\core\Container;

/**
 * Common util class
 */
class Util {

    /**
     * Show Message (error/success..)
     *
     * @param string $msg message,$msgType type of message
     *
     * @return  string
     */
    public static $className = "";
    public static $methodeName = "";
    public static $pagePermission = array();
    public static $userId = "";

    public static function showMessage($msg = '', $msgType = '') {
        if (trim($msg) == '') {
            $display = 'style="display:none"';
            $msg = '&nbsp;';
        } else {
            $display = 'style="display:block"';
        }

        if (trim($msgType) == 'success') {
            $msgClass = 'alert-success';
            $msgValue = 'Success';
        } else {
            $msgClass = 'alert-error';
            $msgValue = 'Error';
        }

        echo '<div id="msgBox" class="alert ' . $msgClass . '"  ' . $display . '> <button data-dismiss="alert" class="close" type="button">×</button>   <b>' . $msgValue . '! </b><span> ' . $msg . '</span>  </div>';
    }

    public static function setMethod($class, $method) {
        static::$className = $class;
        static::$methodeName = $method;
    }

    /**
     * Fetches the asset url
     *
     * @param string $name name
     * @param string $type type
     *
     * @return  string
     */
    public static function getAssetUrl($name, $type) {
        $prefix = "";
        $prefix = ($type == "css") ? THEME_STYLE_URL : THEME_SCRIPT_URL;
        $url = $prefix . '/' . $name;
        return $url;
    }

    /**
     * Displays the html options
     *
     * @param array $rsArr
     * @param array/string $select
     * @return String HTML
     */
    public static function getHTMLOptions($rsArr, $select = "", $ucwords = false, $return = false) {
        $str = '';
        if (!$rsArr) {
            return false;
        }

        foreach ($rsArr as $key => $value) {
            $selected = "";
            if (is_array($select)) {
                if (in_array($key, $select)) {
                    $selected = " selected=\"selected\"";
                }
            } elseif ($select && $key == $select) {
                $selected = " selected=\"selected\"";
            }

            if ($ucwords) {
                $str .= "<option value=\"$key\" $selected>" . ucwords($value) . "</option>\n";
            } else {
                $str .= "<option value=\"$key\" $selected>$value</option>\n";
            }
        }
        if ($return) {
            return $str;
        } else {
            echo $str;
        }
    }

    /**
     * Displays the html radio buttons
     *
     * @param array $rsArr
     * @param array/string $select
     * @return String HTML
     */
    public static function getHTMLRadioBoxes($name, $rsArr, $select = "", $container = '', $containerClass = '', $inputClass = '', $ucwords = false) {

        if (!$rsArr) {
            return false;
        }

        $class = ($containerClass) ? "class=\"$containerClass\"" : "";
        $inputClass = ($inputClass) ? "class=\"$inputClass\"" : "";

        foreach ($rsArr as $key => $value) {
            $selected = "";
            if ($select && $key == $select) {
                $selected = " checked=\"checked\"";
            }
            if ($ucwords) {
                $value = ucwords($value);
            }

            if ($container) {
                echo "<$container $class>";
            }
            echo "<input $inputClass type=\"radio\" name=\"$name\" value=\"$key\" $selected/>$value";
            if ($container) {
                echo "</$container>";
            }
        }
    }

    /**
     * Displays the html checkboxes
     *
     * @param array $rsArr
     * @param array/string $select
     * @return String HTML
     */
    public static function getHTMLCheckBoxes($name, $rsArr, $select = "", $container = '', $containerClass = '', $inputClass = '', $ucwords = false) {

        if (!$rsArr) {
            return false;
        }

        $class = ($containerClass) ? "class=\"$containerClass\"" : "";
        $inputClass = ($inputClass) ? "class=\"$inputClass\"" : "";

        foreach ($rsArr as $key => $value) {
            $selected = "";
            if (is_array($select)) {
                if (in_array($key, $select)) {
                    $selected = "checked=checked";
                }
            } elseif ($select && $key == $select) {
                $selected = "checked=checked";
            }
            if ($ucwords) {
                $value = ucwords($value);
            }


            if ($container) {
                echo "<$container $class>";
            }
            echo "<input $inputClass type=\"checkbox\" name=\"$name" . "[]" . "\" value=\"$key\" $selected/>$value";
            if ($container) {
                echo "</$container>";
            }
        }
    }

    public static function getCSVHTML($valArray, $keyArray) {
        $str = '';
        $keys = explode(",", $keyArray);
        foreach ($keys as $key) {
            $str .= $valArray[$key] . '<br/>';
        }
        return $str;
    }

    public static function buildBreadCumb($breadCumbs = array()) {
        foreach ($breadCumbs as $key => $value) {
            if ($value == '') {
                $breadCumb .= '<li class="Libreadcrumb">' . $key . '</li>';
            } else {
                $breadCumb .= '<li><a href="' . $value . '">' . $key . '</a></li>';
            }
        }
        return $breadCumb;
    }

    /**
     * stop
     *
     * @param int $var Mixed var
     *
     * @return  ''
     *
     */
    public static function stop($var) {
        self::pre($var);
        exit;
    }

    /**
     * pre
     *
     * @param int $var Mixed var
     *
     * @return  ''
     *
     */
    public static function pre($var) {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

    public static function getErrorClass($errors = array(), $field = '') {
        if (isset($errors[$field])) {
            echo ' error';
        }
        return '';
    }

    public static function getErrorMessage($errors = array(), $field = '') {
        if (isset($errors[$field]) && $errors[$field] != 'Blank') {
            echo "<span class=\"help-inline\">$errors[$field]</span>";
        }
        return '';
    }

    /**
     * add dates
     *
     *  @param date $date date from which number to be addded
     *
     *  @param int $days-days to add
     *
     * @return  date
     *
     */
    public static function dateAdd($date, $days) {
        $addedDate = strtotime('+' . $days . ' day', strtotime($date));
        $addedDate = date('Y-m-d', $addedDate);
        return $addedDate;
    }

    /**
     * get values as array against array of keys
     *
     * @param array $keys       array of keys
     * @param array $keyValues  keys and values
     *
     * @return array
     *
     */
    public static function getValuesOfKeys($keys, $keyValues) {
        $values = array();
        foreach ($keys as $key) {
            if (in_array($key, array_keys($keyValues))) {
                $values[] = $keyValues[$key];
            }
        }
        return $values;
    }

    /**
     * get formated date
     *
     * @param string $postedDate date (timestamp) from db
     * @param bool   $onListings list true will make 'ago' with return string
     *
     * @return string formated date
     *
     */
    public static function formatPostedDate($postedDate, $onListings = false) {
        $currentTimeStamp = time();
        $currentMinute = date("i", $currentTimeStamp);
        $timeStampFromDB = strtotime($postedDate);
        $timeDifInMinutes = ($currentTimeStamp - $timeStampFromDB) / 60;
        $formatedDate = '';

        $ago = '';
        if ($onListings) {
            $ago = ' ago';
        }

        switch ($timeDifInMinutes) {
            case '0' :
                $formatedDate = 'Now';
                break;
            case ($timeDifInMinutes < 1) :
                $formatedDate = 'Now';
                break;
            case ($timeDifInMinutes <= 59) :
                $sec = round(($currentTimeStamp - $timeStampFromDB) % 60);
                $timeDifInMinutes = round($timeDifInMinutes);
                $formatedDate = "$timeDifInMinutes Mins" . $ago;
                break;
            case (($timeDifInMinutes / 60) <= 23) :
                $hours = round($timeDifInMinutes / 60);
                $minutes = round($timeDifInMinutes % 60);
                $formatedDate = "$hours Hrs and $minutes Mins" . $ago;
                break;
            default:
                $timeDifInDays = round(($timeDifInMinutes / (60 * 24)));
                switch ($timeDifInDays) {
                    case ($timeDifInDays <= 7) :
                        $formatedDate = date("D h:iA", $timeStampFromDB);
                        break;
                    default :
                        $formatedDate = date("d M, Y h:iA", $timeStampFromDB);
                        break;
                }
                break;
        }

        return $formatedDate;
    }

    /**
     * Get IST from UTC
     *
     * @param string $utcTime UTC time
     *
     * @return date          IST
     */
    public static function getIstFromUtc($utcTime = '') {
        if (is_numeric($utcTime)) {
            $utcTime = date('Y-m-d H:i:s', $utcTime);
        } elseif (!$utcTime) {
            $utcTime = date('Y-m-d H:i:s');
        }
        $date = new \DateTime($utcTime, new \DateTimeZone('UTC'));
        $date->setTimezone(new \DateTimeZone('Asia/Calcutta'));

        return $date;
    }

    /**
     * Get IST from UTC
     *
     * @param string $utcTime UTC time
     *
     * @return date          IST
     */
    public static function convertDateFormat($originalDate, $format = 'Y-m-d') {
        $newDate = date($format, strtotime($originalDate));
        return $newDate;
    }

}
