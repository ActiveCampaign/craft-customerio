/**
 * Customer.io plugin for Craft CMS
 *
 * Customer.io JS
 *
 * @author    Wildbit
 * @copyright Copyright (c) 2021 Wildbit
 * @link      https://wildbit.com
 * @package   Customerio
 * @since     1.0.0
 */

var CustomerioForm = {
  formTarget: null,

  init: function() {
    document.querySelectorAll(".js-customerio-form").forEach(function(form) {
      form.addEventListener("submit", CustomerioForm.checkCaptcha);
    });
  },

  loadCaptcha: function() {
    let _this = this;

    document.querySelectorAll(".g-recaptcha").forEach(function(captcha) {
      let elementId = captcha.getAttribute("id");
      let captchaId = grecaptcha.render(elementId);

      captcha.setAttribute("data-recaptcha-id", captchaId);
    });
  },

  checkCaptcha: function(e) {
    e.preventDefault();

    window.CustomerioForm.formTarget = e.target;

    let captchaId = window.CustomerioForm.formTarget
      .querySelector(".g-recaptcha")
      .getAttribute("data-recaptcha-id");

    grecaptcha.execute(captchaId);
  },

  submitForm: function(token) {
    var form = window.CustomerioForm.formTarget;
    var fields = form.querySelector(".js-customerio-fields");
    var submitBtn = form.querySelector(".js-customerio-submit");
    var submitBtnText = submitBtn.value;

    submitBtn.setAttribute("disabled", true);
    submitBtn.value = "Subscribing...";

    // Remove any errors
    form.querySelectorAll(".form_errors").forEach(function(error) {
      error.parentNode.removeChild(error);
    });

    var formData = new FormData(form);
    formData.set("g-recaptcha-response", token);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", form.action);
    xhr.setRequestHeader("Accept", "application/json");
    xhr.onload = function() {
      if (xhr.status == 200) {
        // Success
        var successMsg = document.createElement("div");
        successMsg.innerHTML =
          "<strong>Thanks for signing up!</strong> <br>Check your inbox for a link to confirm your subscription.";
        successMsg.classList.add("form_success");
        fields.parentNode.insertBefore(successMsg, fields);
        fields.style.display = "none";
      } else {
        // Error
        var response = JSON.parse(xhr.responseText);

        if (response.errors) {
          var errors = Object.keys(response.errors).map(function(key) {
            return response.errors[key][0];
          });
        } else {
          var errors = ["Oops! Something went wrong."];
        }

        var errorMsg = document.createElement("div");
        errorMsg.innerHTML = errors.join("<br>");
        errorMsg.classList.add("form_errors");
        fields.appendChild(errorMsg);
      }

      submitBtn.removeAttribute("disabled");
      submitBtn.value = submitBtnText;

      window.CustomerioForm.formTarget = null;
    };
    xhr.send(formData);
  },
};

window.CustomerioForm = CustomerioForm;
window.addEventListener("load", CustomerioForm.init);

window.handleRecaptcha = function(token) {
  window.CustomerioForm.submitForm(token);
};

window.loadRecaptcha = function() {
  CustomerioForm.loadCaptcha();
};
