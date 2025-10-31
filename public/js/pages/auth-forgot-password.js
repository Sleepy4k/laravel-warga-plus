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
          phone: {
            validators: {
              notEmpty: {
                message: "Please enter your phone number",
              },
              stringLength: {
                min: 10,
                max: 25,
                message:
                  "Phone number must be between 10 and 25 characters long",
              },
              regexp: {
                regexp: /^8[1-9][0-9]{6,15}$/,
                message:
                  "The phone number must be a valid Indonesian phone number",
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
