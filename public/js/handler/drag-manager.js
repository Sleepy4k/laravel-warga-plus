/**
 * A utility class for managing drag-and-drop operations in a web application.
 * This class is designed to handle the initialization of drag-and-drop features,
 * including setting up event listeners and managing the drag state.
 */
class DragManager {
  /**
   * @param {object} options - Configuration options for the Drag manager.
   * @param {boolean} options.debug - Whether to enable debug mode. If true, additional logs will be printed.
   * @param {object} options.data - Initial data for the drag manager.
   * @param {object} options.permissions - Permissions for the user, used to control actions
   * such as editing or deleting items.
   * @param {string} options.saveUrl - The URL to save the order of items.
   * @param {string} options.csrfToken - CSRF token for secure requests,
   * typically retrieved from a meta tag in the HTML.
   * @param {function} options.handleCreateMenu - A function to create menu items.
   * This function should accept an item and permissions object and return a jQuery element.
   * @constructor
   */
  constructor(options) {
    this.debug = options.debug || false;
    if (this.debug) {
      console.log("Initializing DragManager with options:", options);
    }
    this.menuData = options.data || {};
    this.userPermission = options.permissions || {};
    this.saveUrl = options.saveUrl || null;
    this.csrfToken =
      options.csrfToken ||
      document
        .querySelector("meta[name='csrf-token']")
        ?.getAttribute("content");

    this.handleCreateMenu = options.handleCreateMenu || this._handleCreateMenu.bind(this);

    this.draggedItem = null;
    this.draggedItemId = null;
    this.draggedItemParentId = null;
    this.dropTargetElement = null;

    this.$menuList = $("#menu-list");
    this.$saveButton = $("#save-button");
    this.$saveButtonText = $("#save-button-text");

    this.init();
  }

  /**
   * Initializes all event listeners for the drag-and-drop operations.
   * @returns {void}
   */
  init() {
    this.updateMenuOrder();
    this.attachDragAndDropListeners();
    this.$saveButton.on("click", this.handleSave.bind(this));
  }

  /**
   * Attaches the necessary drag-and-drop event listeners to the draggable and droppable elements.
   * It first removes any existing listeners to prevent duplication.
   * @returns {void}
   */
  attachDragAndDropListeners() {
    if (this.debug) {
      console.log("Attaching drag and drop listeners.");
    }
    $('[draggable="true"]').off("dragstart dragover dragleave drop dragend");
    $("[data-droppable-id]").off("dragover dragleave drop");
    this.$menuList.off("dragover dragleave drop");

    $('[draggable="true"]').on({
      dragstart: this.handleDragStart.bind(this),
      dragover: this.handleDragOver.bind(this),
      dragleave: this.handleDragLeave.bind(this),
      drop: this.handleDrop.bind(this),
      dragend: this.handleDragEnd.bind(this),
    });

    this.$menuList.on({
      dragover: this.handleDragOver.bind(this),
      dragleave: this.handleDragLeave.bind(this),
      drop: this.handleDrop.bind(this),
    });

    $("ul[data-droppable-id]").on({
      dragover: this.handleDragOver.bind(this),
      dragleave: this.handleDragLeave.bind(this),
      drop: this.handleDrop.bind(this),
    });
  }

  /**
   * Renders the menu based on the current state of menuData.
   * It clears the existing list and rebuilds it from the data.
   * @returns {void}
   */
  updateMenuOrder() {
    if (this.debug) {
      console.log("Updating menu order.");
    }
    this.$menuList.empty();
    this.menuData.forEach((item) => {
      const $contentLi = this.handleCreateMenu(item, this.userPermission);
      this.$menuList.append($contentLi);
    });
    this.attachDragAndDropListeners();
  }

  /**
   * Handles the `dragstart` event. Stores the dragged item's information in the class instance and the data transfer object.
   * @param {DragEvent} e - The drag event.
   * @returns {void}
   */
  handleDragStart(e) {
    this.draggedItem = e.target;
    this.draggedItemId = $(this.draggedItem).data("id");
    this.draggedItemParentId =
      $(this.draggedItem).data("parentId") === "null"
        ? null
        : $(this.draggedItem).data("parentId");

    if (this.debug) {
      console.log(
        "Drag started for item:",
        this.draggedItemId,
        "with parent:",
        this.draggedItemParentId
      );
    }

    e.originalEvent.dataTransfer.setData(
      "text/plain",
      JSON.stringify({
        id: this.draggedItemId,
        parentId: this.draggedItemParentId,
      })
    );
    e.originalEvent.dataTransfer.effectAllowed = "move";

    setTimeout(() => {
      $(this.draggedItem).addClass("dragging");
    }, 0);
  }

  /**
   * Handles the `dragover` event. Prevents the default behavior and highlights potential drop targets.
   * Determines if a drop is valid based on parent-child relationships.
   * @param {DragEvent} e - The drag event.
   * @returns {void}
   */
  handleDragOver(e) {
    e.preventDefault();

    let target = e.target;
    let $target = $(target);

    while (
      target &&
      target !== this.$menuList[0] &&
      !$target.is("[draggable]") &&
      !$target.is("[data-droppable-id]")
    ) {
      target = target.parentNode;
      $target = $(target);
    }

    if (target === this.draggedItem) return;

    if (this.dropTargetElement && this.dropTargetElement !== target) {
      $(this.dropTargetElement).removeClass("drop-target-hover");
    }

    const targetParentId =
      $target.data("parentId") === "null" ? null : $target.data("parentId");
    const targetDroppableId = $target.data("droppableId");

    let isValidDrop = false;

    if (this.debug) {
      console.log(
        "Drag over target:",
        target,
        "Target ID:",
        $target.data("id"),
        "Target Parent ID:",
        targetParentId,
        "Dragged Item Parent ID:",
        this.draggedItemParentId
      );
    }

    if (target === this.$menuList[0] && this.draggedItemParentId === null) {
      isValidDrop = true; // Dropping on the main menu list
    } else if ($target.is("[draggable]") && targetParentId === null) {
      isValidDrop = true; // Dropping on a valid draggable item with the same parent
    } else if (
      $target.is("[draggable]") &&
      targetParentId !== null &&
      this.draggedItemParentId === targetParentId
    ) {
      isValidDrop = true; // Dropping on a valid draggable item with the same parent
    } else if (
      targetDroppableId !== undefined &&
      this.draggedItemParentId === targetDroppableId
    ) {
      isValidDrop = true; // Dropping on a valid droppable area
    }

    if (isValidDrop) {
      $target.addClass("drop-target-hover");
      this.dropTargetElement = target;
      e.originalEvent.dataTransfer.dropEffect = "move";
    } else {
      e.originalEvent.dataTransfer.dropEffect = "none";
    }
  }

  /**
   * Handles the `dragleave` event. Removes the hover class from the drop target.
   * @returns {void}
   */
  handleDragLeave(e) {
    if (this.dropTargetElement) {
      if (this.debug) {
        console.log("Drag left target:", this.dropTargetElement);
      }
      $(this.dropTargetElement).removeClass("drop-target-hover");
      this.dropTargetElement = null;
    }
  }

  /**
   * Handles the `drop` event. Prevents the default behavior, moves the item in the data structure,
   * and re-renders the menu.
   * @param {DragEvent} e - The drag event.
   * @returns {void}
   */
  handleDrop(e) {
    e.preventDefault();

    if (this.dropTargetElement) {
      $(this.dropTargetElement).removeClass("drop-target-hover");
    }

    if (!this.draggedItem) return;

    const data = JSON.parse(e.originalEvent.dataTransfer.getData("text/plain"));
    const sourceId = data.id;
    const sourceParentId = data.parentId;

    let targetElement = e.target;
    let $targetElement = $(targetElement);

    while (
      targetElement &&
      targetElement !== this.$menuList[0] &&
      !$targetElement.is("[draggable]") &&
      !$targetElement.is("[data-droppable-id]")
    ) {
      targetElement = targetElement.parentNode;
      $targetElement = $(targetElement);
    }

    if (!targetElement || targetElement === this.draggedItem) return;

    const targetId = $targetElement.data("id");
    const targetParentId =
      $targetElement.data("parentId") === "null"
        ? null
        : $targetElement.data("parentId");
    const targetDroppableId = $targetElement.data("droppableId");

    if (this.debug) {
      console.log(
        "Drop event. Source ID:",
        sourceId,
        "Source Parent:",
        sourceParentId,
        "Target ID:",
        targetId,
        "Target Parent:",
        targetParentId,
        "Target Droppable ID:",
        targetDroppableId
      );
    }

    let sourceIndex = -1;
    let sourceList = null;

    if (sourceParentId === null) {
      sourceList = this.menuData;
      sourceIndex = this.menuData.findIndex((item) => item.id === sourceId);
    } else {
      const parent = this.menuData.find((item) => item.id === sourceParentId);
      if (parent) {
        sourceList = parent.children;
        sourceIndex = parent.children.findIndex((item) => item.id === sourceId);
      }
    }

    if (sourceIndex === -1 || !sourceList) return;

    const [movedItem] = sourceList.splice(sourceIndex, 1);

    let destinationList = null;
    let destionationIndex = -1;

    if (sourceParentId === null) {
      if (targetParentId === null && targetId) {
        destinationList = this.menuData;
        destionationIndex = this.menuData.findIndex(
          (item) => item.id === targetId
        );
        const targetRect = targetElement.getBoundingClientRect();
        const dropY = e.originalEvent.clientY;
        if (dropY > targetRect.top + targetRect.height / 2) {
          destionationIndex++; // Insert before the target
        }
      } else if (targetElement === this.$menuList[0]) {
        destinationList = this.menuData;
        destionationIndex = this.menuData.length; // Append to the end
      }
    } else {
      if (targetParentId === sourceParentId && targetId) {
        const parent = this.menuData.find((item) => item.id === targetParentId);
        if (parent) {
          destinationList = parent.children;
          destionationIndex = parent.children.findIndex(
            (item) => item.id === targetId
          );
          const targetRect = targetElement.getBoundingClientRect();
          const dropY = e.originalEvent.clientY;
          if (dropY > targetRect.top + targetRect.height / 2) {
            destionationIndex++; // Insert before the target
          }
        }
      } else if (
        targetDroppableId !== undefined &&
        targetDroppableId === sourceParentId
      ) {
        const parent = this.menuData.find(
          (item) => item.id === targetDroppableId
        );
        if (parent) {
          destinationList = parent.children;
          destionationIndex = parent.children.length; // Append to the end
        }
      }
    }

    if (destinationList && destionationIndex !== -1) {
      destinationList.splice(destionationIndex, 0, movedItem);
      if (this.debug) {
        console.log("Drop successful. New order:", this.menuData);
      }
      this.updateMenuOrder();
    } else {
      sourceList.splice(sourceIndex, 0, movedItem); // Reinsert in the original position if no valid drop target
      if (this.debug) {
        console.log("Invalid drop target. Reverting to original position.");
      }
    }
  }

  /**
   * Handles the `dragend` event. Cleans up the dragged item's state and removes temporary classes.
   * @returns {void}
   */
  handleDragEnd(e) {
    if (this.draggedItem) {
      if (this.debug) {
        console.log("Drag ended.");
      }
      $(this.draggedItem).removeClass("dragging");
    }
    if (this.dropTargetElement) {
      $(this.dropTargetElement).removeClass("drop-target-hover");
    }
    this.draggedItem = null;
    this.draggedItemId = null;
    this.draggedItemParentId = null;
    this.dropTargetElement = null;
  }

  /**
   * Handles the `click` event on the save button.
   * It disables drag-and-drop, collects the new order, and sends it to the server via an AJAX request.
   * @returns {void}
   */
  handleSave() {
    if (!this.userPermission.update) {
      if (this.debug) {
        console.log("User does not have permission to update.");
      }
      if (typeof Swal !== "undefined") {
        Swal.fire({
          title: "Permission Denied",
          text: "You do not have permission to save the menu order.",
          icon: "error",
          confirmButtonText: "OK",
        });
      } else {
        alert("You do not have permission to save the menu order.");
      }
      return;
    }

    if (this.debug) {
      console.log("Saving new menu order.");
    }

    this.$menuList.find("[draggable]").prop("draggable", false);
    this.$saveButton.prop("disabled", true);
    this.$saveButtonText.text("Saving...");

    const order = [];
    this.menuData.forEach((parentItem, parentIndex) => {
      order.push({
        id: parentItem.id,
        parent_id: null,
        order: parentIndex,
      });
      if (parentItem.children && parentItem.children.length > 0) {
        parentItem.children.forEach((childItem, childIndex) => {
          order.push({
            id: childItem.id,
            parent_id: parentItem.id,
            order: childIndex,
          });
        });
      }
    });

    if (this.debug) {
      console.log("Sending order to server:", order);
    }

    $.ajax({
      url: this.saveUrl,
      method: "POST",
      data: {
        _token: this.csrfToken,
        order: order,
      },
      success: (response) => {
        if (response.status === "success") {
          if (typeof Swal !== "undefined") {
            Swal.fire({
              title: "Success",
              text: "Menu order saved successfully.",
              icon: "success",
              confirmButtonText: "OK",
            }).then(() => {
              window.location.reload();
            });
          } else {
            alert(response.message || "Menu order saved successfully.");
            setTimeout(() => {
              window.location.reload();
            }, 1000);
          }
        } else {
          if (typeof Swal !== "undefined") {
            Swal.fire({
              title: "Error",
              text: response.message || "Failed to save menu order.",
              icon: "error",
              confirmButtonText: "OK",
            });
          } else {
            alert(
              response.message ||
                "An error occurred while saving the menu order."
            );
          }
        }
      },
      error: (xhr) => {
        if (typeof Swal !== "undefined") {
          Swal.fire({
            title: "Error",
            text:
              xhr.responseJSON.message ||
              "An error occurred while saving the menu order.",
            icon: "error",
            confirmButtonText: "OK",
          });
        } else {
          alert(
            xhr.responseJSON.message ||
              "An error occurred while saving the menu order."
          );
        }
      },
      complete: () => {
        this.$saveButton.prop("disabled", false);
        this.$saveButtonText.text("Save Order");
        this.attachDragAndDropListeners();
      },
    });
  }

  /**
   * Creates a jQuery list item element.
   * @param {object} parentItem - The data object for the parent item.
   * @param {object} permissions - The permissions object for the user.
   * @returns {jQuery} The jQuery object for the created parent list item.
   */
  _handleCreateMenu(item, permissions) {
    const $parentLi = $("<li>")
      .addClass(
        "list-group-item border border-primary-subtle rounded-3 shadow-sm d-flex flex-column"
      )
      .attr({
        draggable: item.meta.is_sortable ? "true" : "false",
        "data-id": item.id,
        "data-parent-id": "null",
      });

    const $parentHeader = $("<div>")
      .addClass(
        "d-flex align-items-center gap-2 pb-2 mt-2" +
          (item.meta.is_sortable ? " cursor-grab" : "")
      )
      .css("cursor", item.meta.is_sortable ? "grab" : "default").html(`
      <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 1.5rem; height: 1.5rem; color: #6366f1;">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
      </svg>
      <span class="flex-grow-1 fw-bold fs-5">${item.name}</span>
      <span class="badge bg-primary text-white fs-6">
        ${
          item.is_spacer
            ? "Spacer"
            : item.children.length > 0
            ? item.children.length + " items"
            : "No children"
        }
      </span>
      <div class="dropdown">
        <button class="btn btn-sm btn-light border-0 shadow-none d-flex align-items-center gap-1"
          type="button" id="dropdownMenuButton-${
            item.id
          }" data-bs-toggle="dropdown" aria-expanded="false"
          title="More actions" style="border: none; background: none;">
          <i class="bx bx-dots-vertical-rounded fs-5"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="dropdownMenuButton-${
          item.id
        }">
          ${
            permissions.show
              ? `<li><button class="dropdown-item d-flex align-items-center gap-2 show-record" data-id="${item.id}" data-target="#show-record"><i class="bx bx-info-circle text-primary"></i> Detail</button></li>`
              : ""
          }
          ${
            permissions.edit
              ? `<li><button class="dropdown-item d-flex align-items-center gap-2 edit-record" data-id="${item.id}" data-target="#edit-record"><i class="bx bx-edit-alt text-warning"></i> Edit</button></li>`
              : ""
          }
          ${
            item.meta.is_sortable && permissions.delete
              ? `
            <li><hr class="dropdown-divider"></li>
            <li><button class="dropdown-item d-flex align-items-center gap-2 text-danger delete-record" data-id="${item.id}" data-target="#delete-record"><i class="bx bx-trash"></i> Delete</button></li>
          `
              : ""
          }
        </ul>
      </div>
    `);

    $parentLi.append($parentHeader);

    if (!item.is_spacer && item.children && item.children.length > 0) {
      const $childrenUl = $("<ul>")
        .addClass("list-group children-list mt-2")
        .attr("data-droppable-id", item.id);

      item.children.forEach((childItem) => {
        const $childLi = $("<li>")
          .addClass(
            "list-group-item border border-secondary-subtle rounded-2 shadow-sm d-flex align-items-center gap-2 mb-2"
          )
          .attr({
            draggable: "true",
            "data-id": childItem.id,
            "data-parent-id": item.id,
          })
          .css("cursor", "grab").html(`
            <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 1.25rem; height: 1.25rem; color: #6c757d;">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="flex-grow-1 fw-medium fs-6">${childItem.name}</span>
            <div class="dropdown">
              <button class="btn btn-sm btn-light border-0 shadow-none d-flex align-items-center gap-1"
                type="button" id="dropdownMenuButton-child-${
                  childItem.id
                }" data-bs-toggle="dropdown" aria-expanded="false"
                title="More actions" style="border: none; background: none;">
                <i class="bx bx-dots-vertical-rounded fs-5"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="dropdownMenuButton-${
                childItem.id
              }">
                ${
                  permissions.show
                    ? `<li><button class="dropdown-item d-flex align-items-center gap-2 show-record" data-id="${childItem.id}" data-target="#show-record"><i class="bx bx-info-circle text-primary"></i> Detail</button></li>`
                    : ""
                }
                ${
                  permissions.edit
                    ? `<li><button class="dropdown-item d-flex align-items-center gap-2 edit-record" data-id="${childItem.id}" data-target="#edit-record"><i class="bx bx-edit-alt text-warning"></i> Edit</button></li>`
                    : ""
                }
                ${
                  item.meta.is_sortable && permissions.delete
                    ? `
                      <li><hr class="dropdown-divider"></li>
                      <li><button class="dropdown-item d-flex align-items-center gap-2 text-danger delete-record" data-id="${childItem.id}" data-target="#delete-record"><i class="bx bx-trash"></i> Delete</button></li>
                    `
                    : ""
                }
              </ul>
            </div>
          `);

        $childrenUl.append($childLi);
      });

      $parentLi.append($childrenUl);
    }

    return $parentLi;
  }
}
