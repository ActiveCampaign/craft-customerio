<?php
/**
 * Customer.io plugin for Craft CMS 3.x
 *
 * Send newsletter subscribers to Customer.io
 *
 * @link      https://activecampaign.com
 * @copyright Copyright (c) 2022 ActiveCampaign
 */

namespace wildbit\customerio\controllers;

use wildbit\customerio\Customerio;
use wildbit\customerio\models\Subscriber;

use mattwest\craftrecaptcha\CraftRecaptcha;

use Craft;
use craft\web\Controller;
use yii\web\Response;

/**
 * Subscribers Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your plugin’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    Wildbit
 * @package   Customerio
 * @since     1.0.0
 */
class SubscribersController extends Controller
{
  // Protected Properties
  // =========================================================================

  /**
   * @var    bool|array Allows anonymous access to this controller's actions.
   *         The actions must be in 'kebab-case'
   * @access protected
   */
  protected $allowAnonymous = ["new-subscriber"];

  // Public Methods
  // =========================================================================

  /**
   * Handle requests to add a new subscriber.
   * e.g.: actions/customerio/subscribers/new-subscriber
   *
   * @return mixed
   */
  public function actionNewSubscriber(): Response
  {
    $this->requirePostRequest();

    $successUrl = Customerio::$plugin->getSettings()->successUrl;

    // Fetch email from request
    $post = Craft::$app->request->bodyParams;

    // Create a new subscriber model
    $subscriber = new Subscriber();
    $subscriber->email = $post["cio-liam3"];

    if (array_key_exists('cio-3man', $post)) {
      $subscriber->name = $post["cio-3man"];  
    }
    
    $subscriber->lists = $post["lists"];
    $subscriber->page = Craft::$app->request->referrer;

    $success = false;
    $errors = [];

    $captcha = Craft::$app->getRequest()->getParam("g-recaptcha-response");

    // Pass the response code to the verification service.
    $captchaValidates = CraftRecaptcha::$plugin->craftRecaptchaService->verify(
      $captcha
    );

    // Validate email address
    if (!$captchaValidates) {
      $errors = ["general" => ["Who goes there? Be you human or bot? 🕵️"]];
    } elseif (!$subscriber->validate()) {
      $errors = $subscriber->getErrors();
    } else {
      if (Customerio::$plugin->subscribers->saveSubscriber($subscriber)) {
        $success = true;
      } else {
        $errors = [
          "general" => [
            "There was an error saving your subscription. Please try again.",
          ],
        ];
      }
    }

    if (Craft::$app->request->acceptsJson) {
      $this->response->setStatusCode($success ? 200 : 400);
      return $this->asJson([
        "subscribed" => $success,
        "errors" => $errors,
      ]);
    } elseif ($success) {
      return $this->redirect($successUrl);
    } else {
      $this->response->setStatusCode(400);
      return $this->asRaw(
        "There was an error saving your subscription. Please go back and try again."
      );
    }
  }
}
