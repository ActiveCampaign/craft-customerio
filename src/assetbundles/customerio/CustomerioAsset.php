<?php
/**
 * Customer.io plugin for Craft CMS 3.x
 *
 * Send newsletter subscribers to Customer.io
 *
 * @link      https://activecampaign.com
 * @copyright Copyright (c) 2022 ActiveCampaign
 */

namespace wildbit\customerio\assetbundles\customerio;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * CustomerioAsset AssetBundle
 *
 * AssetBundle represents a collection of asset files, such as CSS, JS, images.
 *
 * Each asset bundle has a unique name that globally identifies it among all asset bundles used in an application.
 * The name is the [fully qualified class name](http://php.net/manual/en/language.namespaces.rules.php)
 * of the class representing it.
 *
 * An asset bundle can depend on other asset bundles. When registering an asset bundle
 * with a view, all its dependent asset bundles will be automatically registered.
 *
 * http://www.yiiframework.com/doc-2.0/guide-structure-assets.html
 *
 * @author    Wildbit
 * @package   Customerio
 * @since     1.0.0
 */
class CustomerioAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * Initializes the bundle.
     */
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = "@wildbit/customerio/assetbundles/customerio/dist";

        // define the dependencies
        $this->depends = [];

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            'js/Customerio.js',
        ];

        $this->css = [];

        parent::init();
    }
}
