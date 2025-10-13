/**
 *  Pages Authentication
 */

"use strict";

document.addEventListener("DOMContentLoaded", function (e) {
  (function () {
    const formAuthentication = document.querySelector("#formAuthentication");

    // Form validation for Add new record
    if (formAuthentication) {
      const fv = FormValidation.formValidation(formAuthentication, {
        fields: {
          "email-username": {
            validators: {
              notEmpty: {
                message: "Please enter email / username",
              },
              stringLength: {
                min: 4,
                message: "Email or username must be more than 4 characters",
              },
              regexp: {
                regexp: /^[a-zA-Z0-9@._-]+$/,
                message:
                  "The email or username can only consist of alphabetical, number, dot, underscore, and hyphen",
              },
            },
          },
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
