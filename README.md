# Customer.io plugin for Craft CMS 3.x

Send newsletter subscribers to Customer.io

![Customer.io logo](resources/img/plugin-logo.svg)

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1.  Open your terminal and go to your Craft project:

        cd /path/to/project

2.  Then tell Composer to load the plugin:

        composer require wildbit/customer.io

3.  In the Control Panel, go to Settings → Plugins and click the “Install” button for Customer.io.

## Customer.io Overview

This plugin powers the newsletter sign up form on the site, sending details to Customer.io. It utilizes a double opt-in flow for signups.

Subscribers will be added/updated in Customer.io with a `<prefix><list>_confirmed` property set to `false`. Ensure that a workflow is set up in Customer.io that sends an email to these subscribers with a confirmation link that will update this propery to `true`. See [Customer.io’s double opt-in documentation](https://customer.io/docs/double-opt-in/) for more info.

When someone subscribes to a list we also create a `email_subscription` event in Customer.io with the `lists` that were subscribed to and the `page` where the subscription occurred.

## Configuring Customer.io

1. Go to **Settings** → **Customer.io**.
2. Add the **Site ID** and **API Key** for the relevant Customer.io workspace.
3. Specify a prefix for the attribute that will be added to the user's profile in Customer.io. (e.g. `wb_` or `pm_`)
4. Add the URL of the newsletter success page. This will be shown to users that sign up for the newsletter with JavaScript disabled. (e.g. `https://newsletters.wildbit.com/wildbit/confirm.html`)

## Using Customer.io

The plugin does not contain a template for the subscription form as this will vary depending on the site design. However, it does include a JavaScript file with the logic for submitting the form (`srs/assetbundles/customerio/dist/js/Customerio.js`).

1. Include the Customer.io asset bundle on pages with a subscription form `{% do view.registerAssetBundle("wildbit\\customerio\\assetbundles\\customerio\\CustomerioAsset") %}`
2. Ensure that the form action is set to `/actions/customerio/subscribers/new-subscriber`.
3. Add the `js-customerio-form` class to the `form` element.
4. Ensure the form has `name`, `email`, and `lists[]` fields. The `lists[]` field(s) determines the attribute that will be added to the user's profile in Customer.io. For example: specifying `newsletter` will create a `wb_newsletter_subscription` attribute. You can subscribe a user to more than one list by adding multiple inputs with the `lists[]` name. (For example: if you want to include checkboxes with multiple list options.)
5. Ensure the form fields and submit button are contained within an element with the `js-customerio-fields` class.
6. Successful subscription requests will result in the form fields being hidden and a success message added inside a `form_success` element. There are no default styles for this element.
7. Errors will be displayed in a `form_errors` element below the `js-customerio-fields` element. There are no default styles for this element.

---

Brought to you by [ActiveCampaign](https://activecampaign.com)
