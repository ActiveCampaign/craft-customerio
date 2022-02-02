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
 * Subscriber Model
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
class Subscriber extends Model
{
  // Public Properties
  // =========================================================================

  /**
   * Some model attribute
   *
   * @var string
   */
  public $email;
  public $name;
  public $lists;
  public $page;

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
      ["email", "string"],
      ["email", "required"],
      ["email", "email", "message" => "Please enter a valid email address."],
      ["name", "string"],
      ["name", "default", "value" => ""],
      ["lists", "default", "value" => ["newsletter"]],
      ["page", "string"],
      ["page", "default", "value" => ""],
    ];
  }
}
