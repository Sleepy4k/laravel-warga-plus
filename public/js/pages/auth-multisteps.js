/**
 *  Page auth register multi-steps
 */

"use strict";

// Multi Steps Validation
// --------------------------------------------------------------------
document.addEventListener("DOMContentLoaded", function (e) {
  (function () {
    const stepsValidation = document.querySelector("#multiStepsValidation");
    if (typeof stepsValidation !== undefined && stepsValidation !== null) {
      // Multi Steps form
      const stepsValidationForm =
        stepsValidation.querySelector("#multiStepsForm");
      // Form steps
      const stepsValidationFormStep1 = stepsValidationForm.querySelector(
        "#accountDetailsValidation"
      );
      const stepsValidationFormStep2 = stepsValidationForm.querySelector(
        "#personalInfoValidation"
      );
      const stepsValidationFormStep3 = stepsValidationForm.querySelector(
        "#tosLinksValidation"
      );
      if (
        !stepsValidationFormStep1 ||
        !stepsValidationFormStep2 ||
        !stepsValidationFormStep3
      ) {
        console.log("Multi steps form steps are missing");
        return;
      }
      // Multi steps next prev button
      const stepsValidationNext = [].slice.call(
        stepsValidationForm.querySelectorAll(".btn-next")
      );
      const stepsValidationPrev = [].slice.call(
        stepsValidationForm.querySelectorAll(".btn-prev")
      );

      let validationStepper = new Stepper(stepsValidation, {
        linear: true,
      });

      // Account details
      const multiSteps1 = FormValidation.formValidation(
        stepsValidationFormStep1,
        {
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
                    "The phone number must be a valid Indonesian phone number. (e.g., 81234567890)",
                },
              },
            },
            identity_number: {
              validators: {
                notEmpty: {
                  message: "Please enter your identity number",
                },
                stringLength: {
                  min: 10,
                  max: 20,
                  message:
                    "Identity number must be between 10 and 20 characters long",
                },
                regexp: {
                  regexp: /^[0-9]{12,16}$/,
                  message:
                    "The identity number must be a valid Indonesian identity number",
                },
              },
            },
            password: {
              validators: {
                notEmpty: {
                  message: "Please enter password",
                },
                stringLength: {
                  min: 8,
                  max: 20,
                  message:
                    "The password must be more than 8 and less than 20 characters long",
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
                    return stepsValidationFormStep1.querySelector(
                      '[name="password"]'
                    ).value;
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
              rowSelector: ".col-sm-6",
            }),
            autoFocus: new FormValidation.plugins.AutoFocus(),
            submitButton: new FormValidation.plugins.SubmitButton(),
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
        }
      ).on("core.form.valid", function () {
        validationStepper.next();
      });

      // Personal info
      const multiSteps2 = FormValidation.formValidation(
        stepsValidationFormStep2,
        {
          fields: {
            first_name: {
              validators: {
                notEmpty: {
                  message: "Please enter first name",
                },
                stringLength: {
                  min: 2,
                  max: 25,
                  message:
                    "The first name must be more than 2 and less than 25 characters long",
                },
              },
            },
            last_name: {
              validators: {
                notEmpty: {
                  message: "Please enter last name",
                },
                stringLength: {
                  min: 2,
                  max: 25,
                  message:
                    "The first name must be more than 2 and less than 25 characters long",
                },
              },
            },
            gender: {
              validators: {
                notEmpty: {
                  message: "Please select your gender",
                },
              },
            },
            birth_date: {
              validators: {
                notEmpty: {
                  message: "Please select your birth date",
                },
                date: {
                  format: "YYYY-MM-DD",
                  message: "The value is not a valid date",
                },
              },
            },
            address: {
              validators: {
                notEmpty: {
                  message: "Please enter your address",
                },
                stringLength: {
                  min: 2,
                  max: 255,
                  message:
                    "The address must be more than 2 and less than 255 characters long",
                },
              },
            },
          },
          plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
              eleValidClass: "",
              rowSelector: function (field, ele) {
                switch (field) {
                  case "address":
                    return ".col-md-12";
                  case "job":
                    return ".col-md-12";
                  default:
                    return ".col-sm-6";
                }
              },
            }),
            autoFocus: new FormValidation.plugins.AutoFocus(),
            submitButton: new FormValidation.plugins.SubmitButton(),
          },
        }
      ).on("core.form.valid", function () {
        validationStepper.next();
      });

      // Social links
      const multiSteps3 = FormValidation.formValidation(
        stepsValidationFormStep3,
        {
          fields: {
            agreement: {
              validators: {
                notEmpty: {
                  message: "You must agree to the terms and conditions",
                },
              },
            },
            privacy_policy: {
              validators: {
                notEmpty: {
                  message: "You must agree to the privacy policy",
                },
              },
            },
          },
          plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
              eleValidClass: "",
              rowSelector: ".col-12",
            }),
            autoFocus: new FormValidation.plugins.AutoFocus(),
            submitButton: new FormValidation.plugins.SubmitButton(),
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
        }
      ).on("core.form.valid", function () {
        stepsValidationForm.submit();
      });

      stepsValidationNext.forEach((item) => {
        item.addEventListener("click", (event) => {
          switch (validationStepper._currentIndex) {
            case 0:
              multiSteps1.validate();
              break;
            case 1:
              multiSteps2.validate();
              break;
            case 2:
              multiSteps3.validate();
              break;
            default:
              break;
          }
        });
      });

      stepsValidationPrev.forEach((item) => {
        item.addEventListener("click", (event) => {
          switch (validationStepper._currentIndex) {
            case 2:
              validationStepper.previous();
              break;
            case 1:
              validationStepper.previous();
              break;
            case 0:
            default:
              break;
          }
        });
      });
    }
  })();
});
