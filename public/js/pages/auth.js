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
          "phone-identity": {
            validators: {
              notEmpty: {
                message: "Please enter your phone number or identity number",
              },
              stringLength: {
                min: 10,
                max: 25,
                message:
                  "Phone number or identity number must be more than 10 characters and less than 25 characters",
              },
              regexp: {
                regexp: /^(8[1-9][0-9]{6,15}|[0-9]{12,16})$/,
                message:
                  "The phone number or identity number must be a valid Indonesian phone number or identity number",
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
