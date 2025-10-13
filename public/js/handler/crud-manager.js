/**
 * A utility class for handling common CRUD operations (Add, Show, Edit, Delete)
 * for records displayed in a DataTables table, interacting with forms and offcanvas elements.
 */
class CrudManager {
  /**
   * @param {object} options - Configuration options for the CRUD manager.
   * @param {boolean} options.debug - Whether to enable debug mode. If true, additional logs will be printed.
   * @param {string} options.tableId - The ID of the DataTable (e.g., '#permission-table').
   * @param {object} options.routes - An object containing base routes for API calls.
   * e.g., { update: 'dashboard.rbac.permission.update', destroy: 'dashboard.rbac.permission.destroy' }
   * @param {object} options.forms - An object containing form IDs and submit button IDs.
   * e.g., { add: { formId: '#form-add-new-record', submitBtnId: '#add-new-record-submit-btn' },
   * edit: { formId: '#form-edit-record', submitBtnId: '#edit-record-submit-btn' },
   * delete: { formId: '#form-delete-record' } }
   * @param {object} options.offcanvas - An object containing offcanvas IDs and their associated field mappings.
   * e.g., { show: { id: '#show-record', fieldMap: { name: '#show-name' } },
   * edit: { id: '#edit-record', fieldMap: { name: '#edit-name' } } }
   * @param {boolean} [options.sweetAlertEnabled=true] - Whether to use SweetAlert for delete confirmation.
   * @param {function} [options.onSuccess] - Callback function for successful form submission. Receives (response, formId).
   * @param {function} [options.onError] - Callback function for failed form submission. Receives (error, formId).
   */
  constructor(options) {
    this.debug = options.debug || false; // Default to false
    if (this.debug) {
      console.log("Initializing CrudManager with options:", options);
    }
    this.tableId = options.tableId || null;
    if (this.tableId && !$(this.tableId).length) {
      console.error(
        `Table with ID '${this.tableId}' not found. Please ensure it exists in the DOM.`
      );
      return;
    }

    this.routes = options.routes;
    this.forms = options.forms;
    this.offcanvas = options.offcanvas;
    this.sweetAlertEnabled = options.sweetAlertEnabled !== false; // Default to true
    this.onSuccess = options.onSuccess || this._defaultOnSuccess;
    this.onError = options.onError || this._defaultOnError;
    this.csrfToken =
      options.csrfToken ||
      document
        .querySelector("meta[name='csrf-token']")
        ?.getAttribute("content");

    if (!this.csrfToken) {
      console.error(
        "CSRF token not found. Please ensure you have <meta name='csrf-token' content='...' /> in your head section."
      );
    }

    this.pageData = options.pageData || [];
    this.findFunction =
      options.findFunction ||
      ((id) => this.pageData.find((item) => item.id === id));

    if (!this.forms) {
      this.forms = {
        add: {
          formId: "#form-add-new-record",
          submitBtnId: "#add-new-record-submit-btn",
        },
        edit: {
          formId: "#form-edit-record",
          submitBtnId: "#edit-record-submit-btn",
        },
        delete: {
          formId: "#form-delete-record",
        },
      };
    }

    this.init();
  }

  /**
   * Initializes all event listeners for the CRUD operations.
   */
  init() {
    this._bindFormSubmission();
    this._bindRecordDisplayAndEdit();
    this._bindDeleteRecord();
  }

  /**
   * Binds click events for form submissions.
   * @private
   */
  _bindFormSubmission() {
    if (this.forms.add) {
      $(this.forms.add.submitBtnId).on("click", (e) =>
        this._handleFormSubmission(e, this.forms.add.formId, "add")
      );
    }
    if (this.forms.edit) {
      $(this.forms.edit.submitBtnId).on("click", (e) =>
        this._handleFormSubmission(e, this.forms.edit.formId, "edit")
      );
    }
  }

  /**
   * Handles the submission of a form, including basic HTML5 validation.
   * @param {Event} e - The click event.
   * @param {string} formId - The ID of the form to submit.
   * @param {string} actionType - The type of action (e.g., 'add', 'edit').
   * @private
   */
  _handleFormSubmission(e, formId, actionType) {
    e.preventDefault();
    const form = $(formId);

    if (form[0].checkValidity()) {
      const formData = new FormData(form[0]);
      const url = form.attr("action");
      let method = form.attr("method") || "POST";

      if (
        ["PUT", "PATCH", "DELETE"].includes(method.toUpperCase()) &&
        method.toUpperCase() !== "POST"
      ) {
        formData.append("_method", method.toUpperCase());
        method = "POST"; // Use POST for non-POST methods
      }

      // Append CSRF token when formData does not already contain it
      if (this.csrfToken && !formData.has("_token")) {
        formData.append("_token", this.csrfToken);
      }

      this._sendFormData(url, method, formData, formId, actionType);
    } else {
      form.addClass("was-validated");
    }
  }

  /**
   * Sends form data using Fetch API.
   * @param {string} url - The URL to send the request to.
   * @param {string} method - The HTTP method (POST, GET, PUT, DELETE).
   * @param {FormData} formData - The FormData object to send.
   * @param {string} formId - The ID of the form that triggered the submission.
   * @param {string} formType - 'add' or 'edit'.
   * @private
   */
  async _sendFormData(url, method, formData, formId, formType) {
    const form = $(formId);

    // disable all input and make button to loader
    form.find("input, select, textarea").prop("disabled", true);
    $(this.forms[formType].submitBtnId)
      .prop("disabled", true)
      .html("Loading...");

    try {
      // const response = await fetch(url, fetchOptions);
      const response = await fetch(url, {
        method: method, // Use the determined actual method
        body: formData, // FormData automatically sets 'Content-Type: multipart/form-data'
        headers: {
          Accept: "application/json", // We expect JSON back from Laravel
          // DO NOT manually set 'Content-Type: multipart/form-data' here,
          // fetch does it automatically and correctly with FormData.
        },
      });

      const responseData = await response.json();

      if (response.ok) {
        this.onSuccess(responseData, formId);
        form.removeClass("was-validated")[0].reset();
        const offcanvasElement = form.closest(".offcanvas");
        if (offcanvasElement.length) {
          bootstrap.Offcanvas.getInstance(offcanvasElement[0])?.hide();
        }
        this._refreshDataTable();
      } else {
        this.onError(responseData, formId);
      }
    } catch (error) {
      if (this.debug) {
        console.error("Fetch error:", error);
      }
      this.onError(
        {
          message:
            "A network error occurred or the server did not respond correctly.",
        },
        formId
      );
    }

    // enable all input and reset button
    form.find("input, select, textarea").prop("disabled", false);
    $(this.forms[formType].submitBtnId).prop("disabled", false).html("Submit");
  }

  /**
   * Binds click events for displaying and editing records.
   * @private
   */
  _bindRecordDisplayAndEdit() {
    // We'll use a generic class for show/edit buttons and determine which offcanvas from data-target
    $(document).on("click", `.show-record, .edit-record`, (event) => {
      const button = $(event.currentTarget);
      const dataId = button.data("id");
      const targetOffcanvasId = button.data("target"); // e.g., '#show-record' or '#edit-record'

      if (this.debug) {
        console.log("Button clicked with data-id:", dataId);
      }

      let offcanvasConfig;
      if (targetOffcanvasId === this.offcanvas.show?.id) {
        offcanvasConfig = this.offcanvas.show;
      } else if (targetOffcanvasId === this.offcanvas.edit?.id) {
        offcanvasConfig = this.offcanvas.edit;
      } else {
        console.warn(
          `No offcanvas configuration found for target: ${targetOffcanvasId}`
        );
        return;
      }

      this._populateRecordData(
        dataId,
        offcanvasConfig.fieldMap,
        targetOffcanvasId,
        button.hasClass("edit-record")
      );
    });
  }

  /**
   * Retrieves record data from a DataTable and populates form/display fields.
   * @param {number} dataId - The ID of the record.
   * @param {object} fieldMap - An object mapping rowData keys to jQuery selectors for display/input fields.
   * @param {string} offcanvasId - The ID of the offcanvas element to show.
   * @param {boolean} isEdit - True if this is for an edit operation, false for show.
   * @private
   */
  _populateRecordData(dataId, fieldMap, offcanvasId, isEdit) {
    let rowData = null;

    if (this.tableId && $(this.tableId).length) {
      rowData = $(this.tableId)
        .DataTable()
        .row(function (idx, data) {
          return data.id === dataId;
        })
        .data();
    } else {
      rowData = this.findFunction(dataId);
    }

    if (rowData) {
      for (const key in fieldMap) {
        const selector = fieldMap[key];
        const element = $(selector);

        if (element.length === 0) {
          console.warn(
            `Element with selector '${selector}' not found for key '${key}'.`
          );
          continue;
        }

        // Determine which offcanvas config is being used (show or edit)
        let fieldMapBehavior = undefined;
        if (isEdit && this.offcanvas.edit?.fieldMapBehavior) {
          fieldMapBehavior = this.offcanvas.edit.fieldMapBehavior;
        } else if (!isEdit && this.offcanvas.show?.fieldMapBehavior) {
          fieldMapBehavior = this.offcanvas.show.fieldMapBehavior;
        }

        const hasCustomBehavior =
          typeof fieldMapBehavior === "object" &&
          typeof fieldMapBehavior[key] === "function";

        if (hasCustomBehavior) {
          fieldMapBehavior[key](element, rowData[key], rowData);
          continue;
        }

        if (
          element.is("input") ||
          element.is("textarea") ||
          element.is("select")
        ) {
          if (key.includes("_at")) {
            element.val(new Date(rowData[key]).toLocaleString());
          } else if (element.is("select")) {
            if (isEdit) {
              element.val(rowData[key]).trigger("change");
            } else {
              element.val(rowData[key]);
            }
          } else {
            element.val(rowData[key]);
          }
        } else {
          if (key.includes("_at")) {
            element.text(new Date(rowData[key]).toLocaleString());
          } else {
            element.text(rowData[key]);
          }
        }
      }

      if (isEdit && this.forms.edit && this.routes.update) {
        // Ensure Blade route helper is processed on the server-side
        const route = this._getRoute(this.routes.update, dataId);
        $(this.forms.edit.formId).attr("action", route);
      }

      $(offcanvasId).offcanvas("show");
    } else {
      console.error("No data found for the given ID:", dataId);
    }
  }

  /**
   * Binds click events for deleting records.
   * @private
   */
  _bindDeleteRecord() {
    if (this.forms.delete && this.routes.destroy) {
      $(document).on("click", ".delete-record", (event) => {
        const dataId = $(event.currentTarget).data("id");
        this._confirmAndDelete(dataId);
      });
    }
  }

  /**
   * Shows a confirmation dialog and proceeds with deletion if confirmed.
   * @param {number} dataId - The ID of the record to delete.
   * @private
   */
  _confirmAndDelete(dataId) {
    if (this.sweetAlertEnabled && typeof Swal !== "undefined") {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: "No, cancel!",
        confirmButtonText: "Yes, delete it!",
        customClass: {
          confirmButton: "btn btn-label-danger",
          cancelButton: "btn btn-primary",
        },
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: "Deleting Record...",
            text: "Please wait while the record is being deleted.",
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
              Swal.showLoading();
            },
          });
          this._performDelete(dataId);
        }
      });
    } else {
      if (confirm("Are you sure you want to delete this record?")) {
        this._performDelete(dataId);
      }
    }
  }

  /**
   * Submits the delete form.
   * @param {number} dataId - The ID of the record to delete.
   * @private
   */
  async _performDelete(dataId) {
    const route = this._getRoute(this.routes.destroy, dataId);

    try {
      const response = await fetch(route, {
        method: "DELETE",
        headers: {
          Accept: "application/json",
          "X-CSRF-TOKEN": this.csrfToken,
        },
      });

      const responseData = await response.json();

      if (response.ok) {
        this.onSuccess(responseData, this.forms.delete.formId);
        this._refreshDataTable();
      } else {
        this.onError(responseData, this.forms.delete.formId);
      }
    } catch (error) {
      if (this.debug) {
        console.error("Error performing delete:", error);
      }
      this.onError(
        {
          message: "An error occurred while trying to delete the record.",
        },
        this.forms.delete.formId
      );
      return;
    }
  }

  /**
   * Refreshes the DataTable.
   * @private
   */
  _refreshDataTable() {
    if (this.tableId && $(this.tableId).length) {
      const dataTable = $(this.tableId).DataTable();
      dataTable.ajax.reload(null, false); // Reloads data without resetting paging
    } else if (this.debug) {
      console.warn(
        `DataTable with selector '${this.tableId}' does not exist or is not initialized.`
      );
    }
  }

  /**
   * Default success handler.
   * @param {object} response - The server response.
   * @param {string} formId - The ID of the form that was submitted.
   * @private
   */
  _defaultOnSuccess(response, formId) {
    if (this.debug) {
      console.log("Operation successful:", response);
    }
    if (typeof Swal !== "undefined") {
      Swal.fire({
        icon: "success",
        title: "Success!",
        text: response.message || "Operation completed successfully.",
        showConfirmButton: false,
        timer: 1500,
      });
    } else {
      alert(response.message || "Operation completed successfully.");
    }
  }

  /**
   * Default error handler.
   * @param {object} error - The error response from the server.
   * @param {string} formId - The ID of the form that was submitted.
   * @private
   */
  _defaultOnError(error, formId) {
    if (this.debug) {
      console.error("Operation failed:", error);
    }
    let errorMessage = "An unexpected error occurred.";

    // Clear previous validation errors
    if (formId) {
      const form = $(formId);
      form.find(".is-invalid").removeClass("is-invalid");
      form.find(".invalid-feedback").remove();
    }

    if (error.errors && typeof error.errors === "object") {
      for (const [field, messages] of Object.entries(error.errors)) {
        if (formId) {
          const input = $(`${formId} [name="${field}"]`);
          if (input.length) {
            input.addClass("is-invalid");
            // Only add one feedback per input's parent
            const parent = input.parent();
            if (parent.find("> .invalid-feedback").length === 0) {
              parent.append(
                `<div class="invalid-feedback">${messages.join("<br>")}</div>`
              );
            }
          }
        }
      }
    } else if (error.message) {
      errorMessage = error.message;
    }

    if (typeof Swal !== "undefined") {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        html: errorMessage,
        confirmButtonText: "OK",
      });
    } else {
      alert("Error: " + errorMessage.replace(/<br>/g, "\n"));
    }
  }

  /**
   * Helper to correctly generate routes from Blade.
   * This method assumes you're embedding this JS in a Blade file.
   * If this is a separate .js file, you'll need a global JS object
   * containing your routes generated by Laravel, or pass them in a different way.
   * @param {string} routeBase - The base route name (e.g., 'dashboard.rbac.permission.update').
   * @param {number} id - The ID to substitute in the route.
   * @returns {string} The complete URL.
   * @private
   */
  _getRoute(routeBase, id) {
    return routeBase.replace(":id", id);
  }
}
