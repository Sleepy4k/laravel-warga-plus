/**
 * Handle logout confirmation
 */
$(document).ready(function () {
  $("#logout-btn").on("click", function (e) {
    e.preventDefault();
    Swal.fire({
      title: "Confirm Logout",
      text: "Are you sure you want to log out from your dashboard?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, log out!",
      cancelButtonText: "Cancel",
      customClass: {
        confirmButton: "btn btn-label-danger",
        cancelButton: "btn btn-primary",
      },
    }).then((result) => {
      if (result.isConfirmed) {
        $("#logout-form").submit();
      }
    });
  });
});

/**
 * Handle change url when clicking navigation links
 */
$(document).ready(function () {
  $(document).on("click", "[data-route]", function (e) {
    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.which === 2) return;

    const route = $(this).data("route");
    if (!route) return;

    const baseUrl = window.location.origin + window.location.pathname;
    const target = new URL(route, baseUrl).href;

    if (target === baseUrl) return;

    e.preventDefault();
    window.location.href = target;
  });
});

/**
 * Handle online/offline status
 */
$(document).ready(function () {
  const $offlineMessage = $("#offline-message");
  const $mainContent = $("#main-content");

  function updateOnlineStatus() {
    if (navigator.onLine) {
      $offlineMessage.fadeOut(300, function () {
        $(this).addClass("d-none");
        $mainContent.fadeIn(200);
      });
    } else {
      $mainContent.fadeOut(200, function () {
        $offlineMessage.removeClass("d-none").fadeIn(300);
      });
    }
  }

  updateOnlineStatus();

  if (window.addEventListener) {
    window.addEventListener("online", updateOnlineStatus, false);
    window.addEventListener("offline", updateOnlineStatus, false);
  } else {
    document.body.ononline = updateOnlineStatus;
    document.body.onoffline = updateOnlineStatus;
  }
});
