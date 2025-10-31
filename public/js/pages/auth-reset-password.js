/**
 *  Pages Reset Password
 */

"use strict";

document.addEventListener("DOMContentLoaded", function (e) {
  (function () {
    const formResetPassword = document.querySelector("#formResetPassword");

    // Form validation for Add new record
    if (formResetPassword) {
      const fv = FormValidation.formValidation(formResetPassword, {
        fields: {
          password: {
            validators: {
              notEmpty: {
                message: "Please enter your password",
              },
              stringLength: {
                min: 6,
                max: 20,
                message: "Password must be between 6 and 20 characters",
              },
            },
          },
          password_confirmation: {
            validators: {
              notEmpty: {
                message: "Confirm Password is required",
              },
              identical: {
                compare: function () {
                  return formResetPassword.querySelector('[name="password"]')
                    .value;
                },
                message: "The password and its confirm are not the same",
              },
            },
          },
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: "",
            rowSelector: ".mb-3",
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),

          defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
          autoFocus: new FormValidation.plugins.AutoFocus(),
        },
        init: (instance) => {
          instance.on("plugins.message.placed", function (e) {
            if (e.element.parentElement.classList.contains("input-group")) {
              e.element.parentElement.insertAdjacentElement(
                "afterend",
                e.messageElement
              );
            }
          });
        },
      });
    }
  })();
});
