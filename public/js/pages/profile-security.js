/**
 * Account Security - Account
 */

'use strict';

document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    const formChangePassword = document.querySelector('#formChangePassword');

    // Form validation for Change Password
    if (formChangePassword) {
      const fv = FormValidation.formValidation(formChangePassword, {
        fields: {
          current_password: {
            validators: {
              notEmpty: {
                message: 'Please enter your current password'
              },
              stringLength: {
                min: 6,
                max: 20,
                message: 'Current password must be between 6 and 20 characters'
              }
            }
          },
          password: {
            validators: {
              notEmpty: {
                message: 'Please enter your new password'
              },
              stringLength: {
                min: 6,
                max: 20,
                message: 'New password must be between 6 and 20 characters'
              }
            }
          },
          password_confirmation: {
            validators: {
              notEmpty: {
                message: 'Please confirm your new password'
              },
              identical: {
                compare: function () {
                  return formChangePassword.querySelector('[name="password"]').value;
                },
                message: 'The password and its confirm are not the same'
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
  })();
});
