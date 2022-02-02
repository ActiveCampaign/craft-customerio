<?php
/**
 * Customer.io plugin for Craft CMS 3.x
 *
 * Send newsletter subscribers to Customer.io
 *
 * @link      https://wildbit.com
 * @copyright Copyright (c) 2021 Wildbit
 */

namespace wildbit\customerio\models;

use wildbit\customerio\Customerio;

use Craft;
use craft\base\Model;

/**
 * Customerio Settings Model
 *
 * This is a model used to define the plugin's settings.
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Wildbit
 * @package   Customerio
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Customer.io credentials
     *
     * @var string
     */
    public $siteId = '';
    public $apiKey = '';
    public $attrPrefix = '';
    public $successUrl = '';

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['siteId', 'string'],
            ['siteId', 'required'],
            ['siteId', 'default', 'value' => ''],
            ['apiKey', 'string'],
            ['apiKey', 'required'],
            ['apiKey', 'default', 'value' => ''],
            ['attrPrefix', 'string'],
            ['attrPrefix', 'default', 'value' => ''],
            ['successUrl', 'string'],
            ['successUrl', 'default', 'value' => 'https://newsletters.wildbit.com/wildbit/confirm.html'],
        ];
    }
}
