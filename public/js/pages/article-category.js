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
        notEmpty: {
          message: "Please enter name",
        },
        stringLength: {
          min: 2,
          max: 35,
          message: "Name must be between 2 and 35 characters",
        },
        regexp: {
          regexp: /^[a-zA-Z_-]+$/,
          message:
          "Name can only consist of alphabetical characters, dashes and underscores",
        },
        },
      },
      label: {
        validators: {
        notEmpty: {
          message: "Please enter your label",
        },
        stringLength: {
          min: 2,
          max: 75,
          message: "Label must be between 2 and 75 characters",
        },
        regexp: {
          regexp: /^[a-zA-Z\s_-]+$/,
          message:
          "Label can only consist of alphabetical characters, dashes, underscores and spaces",
        },
        },
      },
      },
      plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        eleValidClass: "",
        rowSelector: ".col-sm-12",
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
    };

    forms.forEach(({ selector }) => {
      const form = document.querySelector(selector);
      if (form) {
      FormValidation.formValidation(form, validationConfig);
      }
    });
  })();
});
