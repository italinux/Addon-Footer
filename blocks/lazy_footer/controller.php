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
| @copyright (c) 2021                                                       |
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
namespace Concrete\Package\LazyFooter\Block\LazyFooter;

use Concrete\Package\LazyFooter\Block\LazyFooter\Src\Utils as BlockUtils;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Asset\Asset;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Editor\LinkAbstractor;
use Concrete\Core\Page\Page;
use Concrete\Core\Block\BlockType\BlockType;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends BlockController
{

    protected $btTable = "btLazyFooter";
    protected static $btHandlerId = "footer";
    protected $btDefaultSet = 'lazy';

    // Style Background & Foreground Colours
    protected static $btStyleOpacity = '0.95';

    // Style Upload Background Image size in KBytes (1KB = 1024b)
    protected static $btStyleUploadImageSize = 450;

    // Style Background Image size: Width X Height (pixels)
    protected static $btStyleUploadThumbWidth = 1190;
    protected static $btStyleUploadThumbHeight = 650;

    // Style Background Over Image default Opacity
    protected static $bgOverImageOpacity = 1;

    // Window Overlay size: Width X Height (pixels)
    protected $btInterfaceWidth = "1050";
    protected $btInterfaceHeight = "650";

    protected $btWrapperClass = 'ccm-ui';
    protected $btWrapperForm = 'lazy-ui';
    
    // Support for Inline Editing
    protected $btSupportsInlineEdit = false;
    protected $btSupportsInlineAdd = false;

    // Bootstrap theme Grid Support
    protected $btIgnorePageThemeGridFrameworkContainer = false;

    // Cache block's database calls
    protected $btCacheBlockRecord = true;

    // Cache block's actual view output
    protected $btCacheBlockOutput = false;

    // Serve cached version even if the result of a post request
    protected $btCacheBlockOutputOnPost = true;

    // Server cached version even if user is logged in
    protected $btCacheBlockOutputForRegisteredUsers = true;

    /**
    * When block caching is enabled and output caching is enabled for a block,
    * this is the value in seconds before cache being refreshed. Default (0) is no limit.
    */
    protected $btCacheBlockOutputLifetime = 0;

    protected static function get_btStyles()
    {
        return array(
            'bgColorRGBA' => t('Background Colour'),
            'bgColorOpacity' => t('Adjust Background Opacity'),
            'bgFID' => t('Background Image'),
            'fgColorRGB' => t('Foreground Colour'),
            'isAnimated' => t('Animation / Transition'),
            'isQuoted' => t('Wrap title with Quotes')
        );
    }

    protected static function get_btFields()
    {
        return array(
            'title' => array(
                'label' => t('Title'),
            ),
            'subtitle' => array(
                'label' => t('Subtitle'),
            ),
            'credits1stMessage' => array(
                'label' => t('1st Credits %s', t('Wording')),
            ),
            'credits1stName' => array(
                'label' => t('1st Credits %s', t('Name')),
            ),
            'credits1stUrl' => array(
                'label' => t('1st Credits %s', t('Link to name')),
            ),
            'credits2ndMessage' => array(
                'label' => t('2nd Credits %s', t('Wording')),
                'encodeEntity' => true,
            ),
            'credits2ndName' => array(
                'label' => t('2nd Credits %s', t('Name')),
            ),
            'credits2ndUrl' => array(
                'label' => t('2nd Credits %s', t('Link to name')),
            ),
        );
    }

    protected static function get_btFormExtraValues()
    {
        return array(
            'bgColorOpacityOptions' => array(
                'label' => t('Options adjust background opacity'),
            ),
        );
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Block info Methods
    */
    public function getBlockTypeName()
    {

        return t('Lazy Footer');
    }

    public function getBlockTypeDescription()
    {

        return t('Add Lazy Footer to your website');
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * This block Main Methods
    */
    public function getTitle() 
    {
        $cName  = 'title';
        $config = self::$btHandlerId . '.' . $cName;
        $dValue = t('a footer phrase goes here');
        
        return BlockUtils::getDefaultValue($config, $dValue, $this->{$cName});
    }
    
    public function getSubtitle() 
    {
        $cName  = 'subtitle';
        $config = self::$btHandlerId . '.' . $cName;
        $dValue = t('and subtitle here');
        
        return BlockUtils::getDefaultValue($config, $dValue, $this->{$cName});
    }

    public function getIsQuoted()
    {
        $cName  = 'isQuoted';
        $config = self::$btHandlerId . '.' . $cName;
        $dValue = false;

        return BlockUtils::getDefaultValue($config, $dValue, $this->{$cName});
    }

    public function getCredits1stMessage()
    {
        $cName  = 'credits1stMessage';
        $config = self::$btHandlerId . '.credits.1.message';
        $dValue = t('you want to download other add-ons?');

        return BlockUtils::getDefaultValue($config, $dValue, $this->{$cName});
    }

    public function getCredits1stName()
    {
        $cName  = 'credits1stName';
        $config = self::$btHandlerId . '.credits.1.name';
        $dValue = t('click here');

        return BlockUtils::getDefaultValue($config, $dValue, $this->{$cName});
    }

    public function getCredits1stUrl()
    {
        $cName  = 'credits1stUrl';
        $config = self::$btHandlerId . '.credits.1.url';
        $dValue = 'http://italinux.com/addons';

        return BlockUtils::getDefaultValue($config, $dValue, $this->{$cName});
    }

    public function getCredits2ndMessage()
    {
        $cName  = 'credits2ndMessage';
        $config = self::$btHandlerId . '.credits.2.message';
        $dValue = t('developed with %s by', '&hearts;');

        return BlockUtils::getDefaultValue($config, $dValue, $this->{$cName});
    }

    public function getCredits2ndName()
    {
        $cName  = 'credits2ndName';
        $config = self::$btHandlerId . '.credits.2.name';
        $dValue = 'Matteo';

        return BlockUtils::getDefaultValue($config, $dValue, $this->{$cName});
    }

    public function getCredits2ndUrl()
    {
        $cName  = 'credits2ndUrl';
        $config = self::$btHandlerId . '.credits.2.url';
        $dValue = 'http://matteo-montanari.com/its-me';

        return BlockUtils::getDefaultValue($config, $dValue, $this->{$cName});
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * This Block RegisterViewAssets
    */
    public function registerViewAssets($outputContent = '')
    {

        // Import JQuery JS framework
        $this->requireAsset('javascript', 'jquery');

        // Import Bootstrap CSS framework
        $this->requireAsset('css', 'bootstrap/*');

        // Import this Block view Assets (css|js)
        $this->requireAsset('jst.block.' . $this->getBlockAssetsHandle() . '-view.assets');

        /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
        * load assets if animation required:
        */
        if ($this->getIsAnimationEnabled() === true) {
            // Import Animations Assets CSS & JS
            $this->requireAsset('jst.animate.assets');

            // Import Animations CSS & JS Configuration
            $this->requireAsset('jst.animate.conf');
        }

        // Import CSS Font-Awesome
        $this->requireAsset('css', 'font-awesome');
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * This Block View/Validate/Save Methods
    */
    public function view()
    {

        // Set style values
        $this->set('sID', $this->getSectionId());
        $this->set('viewPoint', self::getViewPointId());
        $this->set('cTemplate', $this->getCustomTemplateName());
        $this->set('cFgColorClass', $this->getCustomFgColorClassName());

        /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
        * Set main values
        * 1st Credits CTA button
        */
        $this->set('credits1st', array('text' => $this->getCredits1stMessage(),
                                       'name' => $this->getCredits1stName(),
                                      'class' => 'goto',
                                       'link' => $this->getCredits1stUrl(),
                                     'target' => '_blank'));

        // 2nd Credits CTA button
        $this->set('credits2nd', array('text' => $this->getCredits2ndMessage(),
                                       'name' => $this->getCredits2ndName(),
                                      'class' => 'goto',
                                       'link' => $this->getCredits2ndUrl(),
                                     'target' => '_blank'));

        // Import Custom Css3 inline
        $this->set('cStyle', $this->getCustomStyle());
    }

    public function on_start()
    {
        $al = AssetList::getInstance();

        $pf = array(
            'position' => Asset::ASSET_POSITION_FOOTER,
            'minify' => true,
            'combine' => true
        );

        $ph = array(
            'position' => Asset::ASSET_POSITION_HEADER,
            'minify' => true,
            'combine' => true
        );

        $cf = array(
            'position' => Asset::ASSET_POSITION_FOOTER,
            'minify' => false,
            'combine' => false
        );

        /** - - - - - - - - - - - - - - - - - - - - - - - - -
         * Register JS / CSS Animate for this Block
         */
        $al->register('javascript', 'jt.jquery.waypoints', 'blocks/' . $this->getBlockHandle() . '/jscript/min/jquery.waypoints.min.js', $pf, $this->getPackageHandle());

        // Register Assets Animate
        $al->register('javascript', 'animate-lib', 'blocks/' . $this->getBlockHandle() . '/jscript/min/jquery.lazy.animate.min.js', $pf, $this->getPackageHandle());

        $al->register('css', 'style.animate', 'blocks/' . $this->getBlockHandle() . '/style/animate.min.css', $ph, $this->getPackageHandle());
        $al->register('css', 'style.animate.delay', 'blocks/' . $this->getBlockHandle() . '/style/animate.delay.min.css', $ph, $this->getPackageHandle());

        $al->registerGroup(
            'jst.animate.assets', array(
                array(
                    'javascript',
                    'jt.jquery.waypoints'
               ),
               array(
                   'javascript',
                   'animate-lib'
               ),
               array(
                   'css',
                   'style.animate'
               ),
               array(
                   'css',
                   'style.animate.delay'
               ),
            )
        );

        // Register Assets Animate Configuration
        $al->register('javascript', $this->getJSelectorId() . '.animate-conf', 'blocks/' . $this->getBlockHandle() . '/jscript/lazy-animate.conf.js', $cf, $this->getPackageHandle());
        $al->register('javascript-inline', $this->getJSelectorId() . '.animate-init',  '$("section#' . $this->getSectionId()  . '").lazyAnimate(' . $this->getSelectorBlock() . ');', $cf, $this->getPackageHandle());

        $al->registerGroup(
            'jst.animate.conf', array(
               array(
                   'javascript',
                   $this->getJSelectorId() . '.animate-conf'
               ),
               array(
                   'javascript-inline',
                   $this->getJSelectorId() . '.animate-init'
               ),
            )
        );
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Custom inline Style main
    * Stream
    */
    protected function getCustomStyle()
    {
        $o=null;

        if (BlockUtils::isValidColor($this->bgColorRGBA) ||
            BlockUtils::isValidImage($this->getBgFID()) ||
            BlockUtils::isValidColor($this->fgColorRGB) ||
            $this->isCustomOverImageOpacity($this->bgColorOpacity)) {

            ob_start();
            ?>

            <style>
              <?php
                if (BlockUtils::isValidColor($this->bgColorRGBA) ||
                    $this->isCustomOverImageOpacity($this->bgColorOpacity)) {
                ?>
                  footer<?php echo $this->getStyleSelector()?>.over-image::before {
                    <?php
                      if (BlockUtils::isValidColor($this->bgColorRGBA)) { ?>
                          background-color: <?php echo $this->getOverImageBgColor()?> !important;
                    <?php } ?>
                    <?php
                      if ($this->isCustomOverImageOpacity($this->bgColorOpacity)) { ?>
                          opacity: <?php echo $this->bgColorOpacity?> !important;
                    <?php } ?>
                  }
              <?php } ?>

              <?php
                if (BlockUtils::isValidImage($this->getBgFID()) ||
                    BlockUtils::isValidColor($this->bgColorRGBA) ||
                    BlockUtils::isValidColor($this->fgColorRGB)) {
                ?>
                  footer<?php echo $this->getStyleSelector()?>.over-image {
                    <?php
                      if (BlockUtils::isValidImage($this->getBgFID())) { ?>
                          background-image: url('<?php echo parse_url($this->getCustomStyleImagePath(), PHP_URL_PATH)?>') !important;
                    <?php } ?>

                    <?php
                      if (BlockUtils::isValidColor($this->bgColorRGBA)) { ?>
                          background-color: <?php echo $this->bgColorRGBA?> !important;
                    <?php } ?>

                    <?php
                      if (BlockUtils::isValidColor($this->fgColorRGB)) { ?>
                          color: <?php echo $this->fgColorRGB ?> !important;
                    <?php } ?>
                  }
               <?php } ?>

               <?php
                 if (BlockUtils::isValidColor($this->bgColorRGBA)) { ?>
                   footer<?php echo $this->getStyleSelector()?> div.credits {
                       text-shadow: 1px 1px 0 <?php echo $this->bgColorRGBA?> !important;
                       }
               <?php } ?>
            </style>

            <?php
            $o = ob_get_contents();

            ob_end_clean();

            $o = BlockUtils::getCustomStyleSanitised($o);
        }

        return $o;
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Main Validate
    */
    public function validate($args)
    {
        $e = BlockUtils::getThisApp()->make('error');

        // Validate Styles
        foreach (array_keys(self::get_btStyles()) as $key) {

            switch ($key) {
            case (lcfirst(substr($key, -3)) == 'fID'):

                if (!empty($args[$key])) {
                    $f = BlockUtils::getFileObject($args[$key]);

                    if (is_object($f)) {
                        switch ($f->getMimeType()) {
                        case 'image/jpeg':
                        case 'image/jpg':
                        case 'image/png':
                            break;
                        default:
                            $e->add(t('File type required: JPG or PNG'));
                            break;
                        }

                        switch (strtolower($f->getExtension())) {
                        case 'jpeg':
                        case 'jpg':
                        case 'png':
                            break;
                        default:
                            $e->add(t('File extension required: JPG or PNG'));
                            break;
                        }

                        $uploadImageSize = BlockUtils::getUploadImageSize(self::$btStyleUploadImageSize);

                        if ($f->getFullSize() > $uploadImageSize) {
                            $e->add(t('File size exceeded: Max %s', BlockUtils::getHumanReadUploadImageSize($uploadImageSize)));
                        }

                        if ($f->isError()) {
                            $e->add(t('File image is invalid'));
                        }
                    } else {
                        $e->add(t('File image is invalid'));
                    }
                }
                break;
            }
        }

        /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
        * Validate input Fields
        */
        foreach (self::get_btFields() as $key => $value) {

            // Allowed Empty?
            if (isset($value['allowEmpty']) && ($value['allowEmpty'] === false)) {
                if (empty($args[$key])) {
                    $e->add(t('Cannot be empty: %s', $value['label']));
                }
            }

            // URL is Valid?
            if (!empty($args[$key])) {
                if (isset($value['validateURL']) && ($value['validateURL'] === true)) {
                    if (BlockUtils::getIsValidURL($args[$key]) === false) {
                        $e->add(t('Invalid URL: %s', $value['label']));
                    }
                }
            }
        }

        return $e;
    }

    public function save($args)
    {

        // Update custom Styles for all pages
        foreach (array_keys(self::get_btStyles()) as $key) {

            switch ($key) {
            case (lcfirst(substr($key, -3)) == 'fID'):
                if (empty($args[$key])) {
                    $args[$key] = 0;
                }
                break;
            case 'bgColorRGBA':
            case 'fgColorRGB':
                if (empty($args[$key])) {
                    $args[$key] = 'transparent';
                }
                break;
            }
        }

        // Sanitize input for DB
        foreach (array_keys(self::get_btFields()) as $key) {

            // Encode HTML Entities
            if (isset($value['encodeEntity']) && ($value['encodeEntity'] === true)) {
                $args[$key] = htmlentities($args[$key]);
            }

            // Prevent Default Value from setting
            if (empty($args[$key])) {
                $args[$key] = ' ';
            }
        }

        parent::save($args);
    }
 
    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Block Handlers Design Methods
    */
    protected static function getViewPointId()
    {
        return self::$btHandlerId;
    }

    protected function getSectionId()
    {
        return 's' . $this->bID;
    }

    protected function getStyleSelector()
    {
        return '#' . $this->getSectionId() . '.' . self::$btHandlerId;
    }

    protected function getJSelectorId()
    {
        return $this->getSectionId() . '.' . self::$btHandlerId;
    }
 
    protected function getSelectorBlock()
    {
        return str_replace('-', '_', self::$btHandlerId);
    }

    protected function getCustomTemplateName()
    {
        $tName = 'no_template';
        $block = $this->getBlockObject();

        if (is_object($block)) {
          $tName = ($block->getBlockFilename() == true ? $block->getBlockFilename() : $tName);
        } 

        return $tName;
    }

    protected function getCustomFgColorClassName()
    {
        return (BlockUtils::isValidColor($this->fgColorRGB) ? 'cfg-color' : null);
    }

    protected function getPackageHandle()
    {
        return $this->getBlockHandle();
    }

    protected function getBlockHandle()
    {
        return $this->btHandle;
    }

    protected function getBlockAssetsHandle()
    {
        return self::$btHandlerId;
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Custom inline Style Methods
    * Background inline Styles Methods
    */
    public function getBgFID()
    {
        if ($this->bgFID > 0) {
            $fObj = BlockUtils::getFileObject($this->bgFID);
        }

        return (isset($fObj) && is_object($fObj)) ? $fObj : null;
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Check if Animation is enabled (ideal for JS variables)
    */
    public function getIsAnimationEnabled()
    {        
        return ($this->getIsAnimated() === true && Page::getCurrentPage()->isEditMode() == false) ? true : false;
    }

    public function getIsEditMode()
    {
        return (Page::getCurrentPage()->isEditMode() == false) ? 0 : 1;
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Custom Animation / Transition
    */
    public function getIsAnimated()
    {
        $cName  = 'isAnimated';
        $config = self::$btHandlerId . '.' . $cName;
        $dValue = true;

        return filter_var(BlockUtils::getDefaultValue($config, $dValue, $this->{$cName}), FILTER_VALIDATE_BOOLEAN);
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Background Opacity Over Image
    */
    public function getBgColorOpacity()
    {
        $cName  = 'bgColorOpacity';
        $config = self::$btHandlerId . '.' . $cName;
        $dValue = self::$bgOverImageOpacity;

        return BlockUtils::getDefaultValue($config, $dValue, $this->{$cName});
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Background Opacity Over Image Options
    */
    public function getBgColorOpacityOptions()
    {
        return array(
            '-75%' => 0.25,
            '-50%' => 0.5,
            '-25%' => 0.75,
            'default' => 1
        );
    }

    protected function getCustomStyleImagePath()
    {
        return BlockUtils::getThisApp()->make('helper/image')->getThumbnail($this->getBgFID(), self::$btStyleUploadThumbWidth, self::$btStyleUploadThumbHeight, false)->src;
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Custom inline Style Methods
    */
    protected function isCustomOverImageOpacity($value)
    {
        return (($value == true) && ($this->getBgColorOpacity() != 1)) == true ? true : false;
    }

    protected function getOverImageBgColor()
    {
        return $this->bgColorRGBA;
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Form Overlay (Edit/Add) Methods
    */
    public function getBlockAssetsURL()
    {
        $bt = BlockType::getByHandle($this->getBlockHandle());
        $bPath = BlockUtils::getThisApp()->make('helper/concrete/urls')->getBlockTypeAssetsURL($bt);

        return $bPath;
    }

    protected function addLocalAssets($path, $type = 'css')
    {
        $this->addHeaderItem(BlockUtils::getThisApp()->make('helper/html')->{$type}($this->getBlockAssetsURL() . $path));
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Add Form (Window Overlay) - default Values
    */
    protected function addFormDefaultValues()
    {
        // Retrieve defaults Values
        foreach (array_keys(self::get_btFields()) as $key) {
            if (method_exists($this, 'get' . ucfirst($key))) {
                $o = $this->{'get' . ucfirst($key)}();
                // Encode HTML Entities
                if (isset($value['encodeEntity']) && ($value['encodeEntity'] === true)) {
                    $o = html_entity_decode($o);
                }
                $o = is_array($o) ? $o : trim($o);
                $this->set($key, $o);
            }
        }
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Add Form (Window Overlay) - extra Values
    */
    protected function addFormExtraValues()
    {
        // Retrieve defaults Values
        foreach (array_keys(self::get_btStyles()) as $key) {
            if (method_exists($this, 'get' . ucfirst($key))) {
                $this->set($key, $this->{'get' . ucfirst($key)}());
            }
        }

        // Retrieve extra Values
        foreach (array_keys(self::get_btFormExtraValues()) as $key) {
            if (method_exists($this, 'get' . ucfirst($key))) {
                $this->set($key, $this->{'get' . ucfirst($key)}());
            }
        }
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Add Form (Window Overlay)
    */
    protected function add()
    {

        // Custom Styles Palettes
        $this->set('color', BlockUtils::getThisApp()->make('helper/form/color'));
        $this->set('asset', BlockUtils::getThisApp()->make('helper/concrete/asset_library'));

        $this->set('fgColorPalette', BlockUtils::getFgColorPalette(false, true));
        $this->set('bgColorPalette', BlockUtils::getBgColorPalette(true, true, self::$btStyleOpacity));

        $this->set('btWrapperForm', $this->btWrapperForm);

        // User Interface
        $this->set('hUI', BlockUtils::getThisApp()->make('helper/concrete/ui'));

        $this->addFormDefaultValues();
        $this->addFormExtraValues();

        // Add Assets to Window Overlay
        $this->addLocalAssets('/css/tools/lazy-global-ui.css', 'css');
    }

    /** - - - - - - - - - - - - - - - - - - - - - - - - - - -
    * Edit Form (Window Overlay)
    */
    protected function edit() 
    {
        $this->add();
    }
}
