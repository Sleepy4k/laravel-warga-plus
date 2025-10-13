/**
 *  Pages Product Category
 */
"use strict";

document.addEventListener("DOMContentLoaded", function (e) {
  (function () {
    const forms = [
      { selector: "#form-add-new-record" },
      { selector: "#form-edit-record" }
    ];

    const validationConfig = {
      fields: {
        name: {
          validators: {
            notEmpty: { message: "Please enter name" },
            stringLength: {
              min: 2,
              max: 255,
              message: "Name must be between 2 and 255 characters"
            },
            regexp: {
              regexp: /^[a-z.]+$/,
              message: "Name can only consist of alphabetical characters and dots"
            }
          }
        },
        guard_name: {
          validators: {
            notEmpty: { message: "Please enter your guard name" },
            stringLength: {
              min: 2,
              max: 255,
              message: "Guard name must be between 2 and 255 characters"
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          eleValidClass: "",
          rowSelector: ".col-sm-12"
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
        autoFocus: new FormValidation.plugins.AutoFocus()
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
      }
    };

    forms.forEach(({ selector }) => {
      const form = document.querySelector(selector);
      if (form) {
        FormValidation.formValidation(form, validationConfig);
      }
    });
  })();
});
