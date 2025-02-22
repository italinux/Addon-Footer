<?php
/**
.---------------------------------------------------------------------.
|  @package: Lazy Footer (a.k.a. add-on Footer)
|  @version: Latest on Github
|  @link:    http://italinux.com/addon-footer
|  @docs:    http://italinux.com/addon-footer/docs
|
|  @author: Matteo Montanari <matteo@italinux.com>
|  @link:   https://matteo-montanari.com
'---------------------------------------------------------------------'
.---------------------------------------------------------------------------.
| @copyright (c) current year                                               |
| ------------------------------------------------------------------------- |
| @license: Concrete5.org Marketplace Commercial Add-Ons & Themes License   |
|           https://concrete5.org/help/legal/commercial_add-on_license      |
|           or just: file://lazy_footer/LICENSE.TXT                         |
|                                                                           |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
'---------------------------------------------------------------------------'
*/
namespace Concrete\Package\LazyFooter\Block\LazyFooter\Src;

use Concrete\Core\Page\Page;
use HtmlObject\Element;
use Concrete\Core\File\File;
use Concrete\Core\File\Set\Set as FileSet;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Support\Facade\Config;
use Concrete\Core\Block\View\BlockView;

class Utils {

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Get Facade Application
    */
    public static function getThisApp()
    {

        // retrieve Facade Application
        return Application::getFacadeApplication();
    }

    /**
    * Direct to Values: 1. property local / 2. app/config / 3. method local
    */
    public static function getDefaultValue($key, $value, $name=null)
    {

        if (isset($name)) {

            $o = $name;

        } else {

            /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
            * Detect base path config: concrete | app
            */
            $config = (current(explode(".", $key)) == 'concrete' ? null : 'app.') . $key;

            /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
            * Retrieve DIRECT value (e.g. app.team.item.1.imageWidth)
            */
            $o = Config::get(trim($config));

            /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
            * IF empty AND 'item' (CHILD) THEN Retrieve PARENT value
            */
            if (empty($o)) {

              $full = explode(".", $config);

              // Check IF it's 'item' (CHILD)
              if ($full[2] == 'item') {

                // HOT-FIX: PHPv8 Compatibility CHECK ARRAY KEY EXISTS
                // Check IF key 4 exists
                if (array_key_exists(4, $full)) {

                    switch ($full[4]) {
                    /** - - - - - - - - - - - - - - - - - - - - - - - - -
                    * Add entries HERE to AVOID climbing up to PARENT value (e.g. title)
                    */
                    case 'title':
                        break;
                    default:
                      /** - - - - - - - - - - - - - - - - - - - - - - - - -
                      * NOW climb up to PARENT value (e.g. app.team.imageWidth)
                      */
                      array_splice($full, 3, 1);
                      $o = Config::get(trim(implode(".", $full)));
                    }
                } else {
                    /** - - - - - - - - - - - - - - - - - - - - - - - - -
                    * NOW climb up to PARENT value (e.g. app.team.imageWidth)
                    */
                    array_splice($full, 3, 1);
                    $o = Config::get(trim(implode(".", $full)));
                }
              }
            }

            /** - - - - - - - - - - - - - - - - - - - - - - - - -
            * Swap value if numeric
            */
            if (is_numeric($o) === true) {
              $value = $o;
            }

            /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
            * Retrieve VALUE
            */
            $o = (is_bool($o) === true) ? ((int) $o) : (((empty($o) === true) || is_numeric($o)) ? $value : ((is_array($o) == true) ? $o :  t($o)));
        }

        return $o;
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * BlockView instance
    */
    public static function getInstanceBlockView()
    {

        // retrieve blockView: Object
        return BlockView::getInstance();
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Page Methods
    */
    public static function getPageObject($value)
    {

        // retrieve page: Object
        return Page::getByID($value);
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * File Methods
    */
    public static function getFileObject($value)
    {

        // retrieve file: Object
        return File::getByID($value);
    }

    public static function getFileSetObject($value)
    {

        // retrieve file set: Object
        return FileSet::getByID($value);
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Image Upload maximum size
    */
    public static function getUploadImageSize($size)
    {

        return (int) ($size * 1024);
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Human Readable image size
    */
    public static function getHumanReadUploadImageSize($size)
    {

        return self::getHumanReadableFileSize($size);
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Human Readable file size
    */
    public static function getHumanReadableFileSize($size)
    {

        $units = explode(' ','B KB MB GB TB PB');

        for ($i = 0; $size > 1024; $i++) {
             $size /= 1024;
        }

        return round($size, 2) . $units[$i];
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * File Upload maximum size
    */
    public static function getUploadFileSize($size)
    {

        return (int) ($size * 1024);
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Get Ordinal number from Cardinal number
    */
    public static function getOrdinalNumberShort($value)
    {

        switch (substr($value, -1)) {
        case 1:
            $ord='st';
            break;
        case 2:
            $ord='nd';
            break;
        case 3:
            $ord='rd';
            break;
        default:
            $ord='th';
        }

        return $value.$ord;
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Custom inline Style Methods
    */
    public static function getCustomStyleSanitised($buffer)
    {

        /** HOT-FIX: new Marketplace approval guidelines at ConcreteCMS (avoid preg_replace)
        $search = array(
            '/\}[^\S ]+/s',  // strip whitespaces after closing tag, except space
            '/[^\S ]+\{/s',  // strip whitespaces before opening tag, except space
            '/(\s)+/s'       // shorten multiple whitespace sequences
        );
        $replace = array(
            '}',
            '{',
            '\\1'
        );

        $buffer = preg_replace($search, $replace, $buffer);
        */

        $search = array(
            "\n", // strip new line UNIX
            "\r", // strip new line MS
            ' '   // strip whitespaces
        );

        $buffer = str_replace($search, '', $buffer);

        return $buffer;
    }

    protected static function getRGBColors($rgba)
    {

        preg_match('/\((.*?)\)/', $rgba, $match);

        if (substr_count($match[1], ',') > 2) {
            $match[1] = substr($match[1], 0, strrpos($match[1], ','));
        }

        return explode(',', $match[1]);
    }

    public static function isValidColor($value)
    {

        return (substr(trim($value), 0, 3) =='rgb' ? true : false);
    }

    public static function isValidImage($value)
    {

        return (is_object($value) ? true : false);
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Check if is a valid url
    * @return boolean (true | false)
    */
    public static function getIsValidURL($uri)
    {

        // HOT-FIX: PHPv8 Compatibility REMOVE = FILTER_FLAG_SCHEME_REQUIRED
        return ((substr($uri, 0, 4) == 'http' && filter_var($uri, FILTER_VALIDATE_URL) !== false) ? true : false);
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Check if is a valid image size
    * @return boolean (true | false)
    */
    public static function getIsValidImageSize($value, $min, $max)
    {

        return (is_numeric($value) ? (filter_var($value, FILTER_VALIDATE_INT, array("options" => array("min_range" => $min, "max_range" => $max))) ? true : false) : false);
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Custom inline Style Config (window overlay)
    * Background Colours Palette
    */
    public static function getBgColorPalette($showAlpha = false, $showPalette = true, $opacity)
    {

        return array(
                'showAlpha' => $showAlpha,
                'showPalette' => $showPalette,
                'palette' => array(
                    // greens
                    array(
                      "rgba(0, 141, 6, $opacity)",
                      "rgba(0,  95, 4, $opacity)",
                      "rgba(0,  58, 3, $opacity)"
                    ),
                    // lightblues
                    array(
                      "rgba(0, 90, 141, $opacity)",
                      "rgba(0, 61,  95, $opacity)",
                      "rgba(0, 37,  58, $opacity)"
                    ),
                    // blues
                    array(
                      "rgba(0, 23, 142, $opacity)",
                      "rgba(0, 15,  95, $opacity)",
                      "rgba(0,  9,  58, $opacity)"
                    ),
                    // purples
                    array(
                      "rgba(61, 0, 142, $opacity)",
                      "rgba(41, 0,  95, $opacity)",
                      "rgba(25, 0,  58, $opacity)"
                    )
              )
        );
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Custom inline Style Config (window overlay)
    * Foreground Colours Palette
    */
    public static function getFgColorPalette($showAlpha = false, $showPalette = true)
    {

        return array(
                'showAlpha' => $showAlpha,
                'showPalette' => $showPalette,
                'palette' => array(
                    // blacks
                    array(
                      "rgb(0, 0, 0)",
                      "rgb(34, 34, 34)",
                      "rgb(85, 85, 85)"
                    ),
                    // greens
                    array(
                      "rgb(0, 141, 6)",
                      "rgb(0,  95, 4)",
                      "rgb(0,  58, 3)"
                    ),
                    // purples
                    array(
                      "rgb(61, 0, 142)",
                      "rgb(41, 0,  95)",
                      "rgb(25, 0,  58)"
                    ),
                    // whites
                    array(
                      "rgb(255, 255, 255)",
                      "rgb(238, 238, 238)",
                      "rgb(204, 204, 204)"
                    )
              )
        );
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * @param $tabs
    * @param null|string $id
    *
    * @return string
    */
    public function tabs($tabs, $id = null)
    {
        $ul = new Element("ul");
        $ul->addClass("nav");
        $ul->addClass("nav-tabs mb-3 nav-fill");
        $ul->setAttribute("role", "tablist");

        if ($id !== null) {
            $ul->setAttribute("id", $id);
        }

        foreach ($tabs as $tab) {
            $a = new Element("a");
            $a->addClass("nav-link");

            if ((isset($tab[2]) && $tab[2])) {
                $a->addClass("active");
            }

            if (strpos($tab[0], "/") !== false) {
                $a->setAttribute("href", $tab[0]);
            } else {
                $a->setAttribute("href", "#" . $tab[0]);
                $a->setAttribute("data-bs-toggle", "tab");
            }

            $a->setAttribute("id", $tab[0] . "-tab");
            $a->setAttribute("aria-controls", $tab[0]);
            $a->setAttribute("data-tab", $tab[0]);
            $a->setAttribute("role", "tab");
            $a->setAttribute("aria-selected", (isset($tab[2]) && $tab[2]) ? "true" : "false");
            $a->setValue($tab[1]);

            $li = new Element("li");
            $li->addClass("nav-item");

            if ((isset($tab[2]) && $tab[2])) {
                $li->addClass("active");
            }

            if ((! isset($tab[3]) || (! $tab[3]))) {
                $li->addClass("hide");
            }

            if ((isset($tab[4]) && $tab[4])) {
                $li->addClass("only");
            }

            $li->appendChild($a);
            $ul->appendChild($li);
        }

        return (string)$ul;
    }
}
