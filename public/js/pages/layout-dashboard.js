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
  $(
    "[id^=sidebar-menu-page-], [id^=shortcut-link-], [id^=navbar-link-], [id^=profile-nav-], [id^=shortcut-add-button]"
  ).on("click", function (e) {
    e.preventDefault();
    const url = $(this).data("route");
    if (url && url !== window.location.href) {
      window.location.href = url;
    }
  });
});

/**
 * Handle online/offline status
 */
$(document).ready(function () {
  function updateOnlineStatus() {
    if (navigator.onLine) {
      $("#main-content").fadeIn(300);
      $("#offline-message").fadeOut(300, function() {
        $(this).addClass("d-none");
      });
    } else {
      $("#main-content").fadeOut(300);
      $("#offline-message").removeClass("d-none").fadeIn(300);
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
