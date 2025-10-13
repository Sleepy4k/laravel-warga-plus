/**
 *  Pages Reset Password
 */

"use strict";

document.addEventListener("DOMContentLoaded", function (e) {
  (function () {
    const formForgotPassword = document.querySelector("#formForgotPassword");

    // Form validation for Add new record
    if (formForgotPassword) {
      const fv = FormValidation.formValidation(formForgotPassword, {
        fields: {
          email: {
            validators: {
              notEmpty: {
                message: "Please enter your email",
              },
              emailAddress: {
                message: "The value is not a valid email address",
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
