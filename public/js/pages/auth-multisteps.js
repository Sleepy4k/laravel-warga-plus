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
      // Multi steps next prev button
      const stepsValidationNext = [].slice.call(
        stepsValidationForm.querySelectorAll(".btn-next")
      );
      const stepsValidationPrev = [].slice.call(
        stepsValidationForm.querySelectorAll(".btn-prev")
      );

      const multiStepsMobile = document.querySelector(".multi-steps-mobile");

      // Mobile
      if (multiStepsMobile) {
        new Cleave(multiStepsMobile, {
          phone: true,
          phoneRegionCode: "ID",
        });
      }

      let validationStepper = new Stepper(stepsValidation, {
        linear: true,
      });

      // Account details
      const multiSteps1 = FormValidation.formValidation(
        stepsValidationFormStep1,
        {
          fields: {
            username: {
              validators: {
                notEmpty: {
                  message: "Please enter username",
                },
                stringLength: {
                  min: 6,
                  max: 25,
                  message:
                    "The username must be more than 6 and less than 25 characters long",
                },
                regexp: {
                  regexp: /^[a-zA-Z0-9]+$/,
                  message:
                    "The username can only consist of alphabetical and number",
                },
              },
            },
            email: {
              validators: {
                notEmpty: {
                  message: "Please enter email address",
                },
                emailAddress: {
                  message: "The value is not a valid email address",
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
            whatsapp_number: {
              validators: {
                notEmpty: {
                  message: "Please enter mobile number",
                },
              },
            },
            telkom_batch: {
              validators: {
                notEmpty: {
                  message: "Please enter telkom batch",
                },
                stringLength: {
                  min: 4,
                  max: 5,
                  message:
                    "The telkom batch must be more than 4 and less than 5 characters long",
                },
                regex: {
                  pattern:/^(19|20)\d{2}$/,
                  message: "The telkom batch must be a valid year (e.g., 1999, 2024)",
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
