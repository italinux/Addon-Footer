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
| @copyright (c) 2020                                                       |
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
namespace Concrete\Package\LazyFooter;

use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Package\Package;
use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Block\BlockType\Set as BlockTypeSet;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends Package
{

    protected $pkgHandle = 'lazy_footer';
    protected $pkgVersion = '1.1.4';

    protected $appVersionRequired = '5.7.4.2';

    protected $pkg;


    public function getPackageName()
    {
        return t('Lazy Footer');
    }

    public function getPackageDescription()
    {
        return t('Add Lazy Footer to your website');
    }

    public function getPackageBlockTypeSet()
    {
        return strstr($this->pkgHandle, '_', true);
    }

    /** * * * * * * * * * * * * * * * * * * * * * * * * * *
    * Configure / Install / Upgrade / Uninstall
    */    
    public function install()
    {
        $this->pkg = parent::install();

        if (BlockTypeSet::getByHandle($this->getPackageBlockTypeSet()) == false) {
            BlockTypeSet::add($this->getPackageBlockTypeSet(), ucfirst($this->getPackageBlockTypeSet()), $this->pkg);
        }

        $this->configureBlocks();
    }

    public function upgrade()
    {
        parent::upgrade();

        $this->pkg = Package::getByHandle($this->pkgHandle);

        $this->configureBlocks();
    }

    public function uninstall()
    {
        parent::uninstall();

        $app = Application::getFacadeApplication();

        $db = $app->make('database')->connection();
        $db->executeQuery('DROP TABLE IF EXISTS bt' . ucfirst(str_replace('_', '', mb_convert_case(mb_strtolower($this->pkgHandle, "UTF-8"), MB_CASE_TITLE, "UTF-8"))));
    }

    protected function configureBlocks()
    {
        if (is_object(BlockType::getByHandle($this->pkgHandle)) == false) {
            BlockType::installBlockType($this->pkgHandle, $this->pkg);
        }
    }
}
