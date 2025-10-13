/**
 * Account Settings - Account
 */

'use strict';

document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    const formAccSettings = document.querySelector('#formAccountSettings'),
      deactivateAcc = document.querySelector('#formAccountDeactivation'),
      deactivateButton = deactivateAcc.querySelector('.deactivate-account');

    // Form validation for Add new record
    if (formAccSettings) {
      const fv = FormValidation.formValidation(formAccSettings, {
        fields: {
          first_name: {
            validators: {
              notEmpty: {
                message: 'Please enter first name'
              },
              stringLength: {
                min: 2,
                max: 25,
                message: 'First name must be between 2 and 25 characters long'
              },
              regexp: {
                regexp: /^[a-zA-Z\s]+$/,
                message: 'First name can only consist of alphabetical characters and spaces'
              }
            }
          },
          last_name: {
            validators: {
              notEmpty: {
                message: 'Please enter last name'
              },
              stringLength: {
                min: 2,
                max: 25,
                message: 'Last name must be between 2 and 25 characters long'
              },
              regexp: {
                regexp: /^[a-zA-Z\s]+$/,
                message: 'Last name can only consist of alphabetical characters and spaces'
              }
            }
          },
          email: {
            validators: {
              notEmpty: {
                message: 'Please enter your email'
              },
              emailAddress: {
                message: "Please enter valid email address",
              },
            }
          },
          whatsapp_number: {
            validators: {
              notEmpty: {
                message: 'Please enter your WhatsApp number'
              },
              stringLength: {
                min: 2,
                max: 20,
                message: 'WhatsApp number must be between 2 and 20 characters long'
              },
              regexp: {
                regexp: /^\+?[0-9\s]+$/,
                message: 'The WhatsApp number can only consist of numbers and spaces'
              }
            }
          },
          address: {
            validators: {
              notEmpty: {
                message: 'Please enter your address'
              },
              stringLength: {
                min: 10,
                max: 255,
                message: 'Address must be between 10 and 255 characters long'
              }
            }
          },
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: '',
            rowSelector: '.mb-3'
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),
          defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
          autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
          instance.on('plugins.message.placed', function (e) {
            if (e.element.parentElement.classList.contains('input-group')) {
              e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
            }
          });
        }
      });
    }

    if (deactivateAcc) {
      const fv = FormValidation.formValidation(deactivateAcc, {
        fields: {
          confirm_delete: {
            validators: {
              notEmpty: {
                message: 'Please confirm you want to delete account'
              }
            }
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: '',
            rowSelector: '.mb-3'
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),
          fieldStatus: new FormValidation.plugins.FieldStatus({
            onStatusChanged: function (areFieldsValid) {
              areFieldsValid
                ? // Enable the submit button
                  // so user has a chance to submit the form again
                  deactivateButton.removeAttribute('disabled')
                : // Disable the submit button
                  deactivateButton.setAttribute('disabled', 'disabled');
            }
          }),
          // Submit the form when all fields are valid
          // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
          autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
          instance.on('plugins.message.placed', function (e) {
            if (e.element.parentElement.classList.contains('input-group')) {
              e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
            }
          });
        }
      });
    }

    // Deactivate account alert
    const accountActivation = document.querySelector('#confirm_delete');

    // Alert With Functional Confirm Button
    if (deactivateButton) {
      deactivateButton.onclick = function () {
        if (accountActivation.checked == true) {
          Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone. Your account will be permanently deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, keep it',
            customClass: {
              confirmButton: 'btn btn-danger',
              cancelButton: 'btn btn-primary'
            }
          }).then(function (result) {
            if (result.value) {
              deactivateAcc.submit();
            } else {
              accountActivation.checked = false;
            }
          });
        }
      };
    }

    // CleaveJS validation
    const phoneNumber = document.querySelector('#whatsapp_number');
    // Phone Mask
    if (phoneNumber) {
      new Cleave(phoneNumber, {
        phone: true,
        phoneRegionCode: 'ID'
      });
    }

    // Update/reset user image of account page
    let accountUserImage = document.getElementById('uploadedAvatar');
    const fileInput = document.querySelector('.account-file-input'),
      resetFileInput = document.querySelector('.account-image-reset');

    if (accountUserImage) {
      const resetImage = accountUserImage.src;
      fileInput.onchange = () => {
        if (fileInput.files[0]) {
          accountUserImage.src = window.URL.createObjectURL(fileInput.files[0]);
        }
      };
      resetFileInput.onclick = () => {
        fileInput.value = '';
        accountUserImage.src = resetImage;
      };
    }
  })();
});
