<?php
/**
 * Customer.io plugin for Craft CMS 3.x
 *
 * Send newsletter subscribers to Customer.io
 *
 * @link      https://activecampaign.com
 * @copyright Copyright (c) 2022 ActiveCampaign
 */

namespace wildbit\customerio\services;

use wildbit\customerio\Customerio;
use Customerio\Client;

use Craft;
use craft\base\Component;

/**
 * Subscribers Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Wildbit
 * @package   Customerio
 * @since     1.0.0
 */
class Subscribers extends Component
{
  /**
   * An HTTP Client
   * @var \GuzzleHttp\Client
   */
  protected $client;

  /**
   * Basic Auth Credentials
   * ['user', 'password']
   * @var array
   */
  protected $auth = [];

  // Public Methods
  // =========================================================================

  /**
   * Send the subscriber to Customer.io
   */
  public function saveSubscriber($subscriber)
  {
    $apiKey = Customerio::$plugin->getSettings()->apiKey;
    $siteId = Customerio::$plugin->getSettings()->siteId;
    $attrPrefix = Customerio::$plugin->getSettings()->attrPrefix;

    // Create a new client for interacting with the Customer.io API
    $this->client = new \GuzzleHttp\Client([
      "base_uri" => "https://track.customer.io",
    ]);
    $this->auth[0] = $siteId;
    $this->auth[1] = $apiKey;

    // Create/update the Customer.
    try {
      $body = array_merge([
        "email" => $subscriber->email,
        "subscribed_at" => time(),
      ]);

      foreach ($subscriber->lists as $list) {
        $body[$attrPrefix . $list . "_subscription"] = "true";
      }

      if ($subscriber->name) {
        $body["name"] = $subscriber->name;
      }

      $response = $this->client->put(
        "/api/v1/customers/" . $subscriber->email,
        [
          "auth" => $this->auth,
          "json" => $body,
        ]
      );
    } catch (BadResponseException $e) {
      return false;
    } catch (RequestException $e) {
      return false;
    }

    // Create an event.
    try {
      $body = [
        "name" => "email_subscription",
        "data" => [
          "lists" => $subscriber->lists,
          "page" => $subscriber->page,
        ],
      ];

      $response = $this->client->post(
        "/api/v1/customers/" . $subscriber->email . "/events",
        [
          "auth" => $this->auth,
          "json" => $body,
        ]
      );
    } catch (BadResponseException $e) {
      return false;
    } catch (RequestException $e) {
      return false;
    }

    return true;
  }
}
