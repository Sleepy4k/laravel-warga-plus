$(document).ready(function () {
  const $mainContent = $("#main-content");
  const $loader = $("#loader");
  const $footer = $("#footer-dashboard");

  $mainContent.hide();
  $footer.hide();
  $loader.show();

  function handleWindowLoad() {
    $loader.fadeOut(300, function () {
      $footer.fadeIn(200);
      $mainContent.fadeIn(200);
    });
  }

  if (document.readyState === "complete") {
    handleWindowLoad();
  } else {
    $(window).on("load", handleWindowLoad);

    setTimeout(function () {
      if ($loader.is(":visible")) {
        handleWindowLoad();
      }
    }, 5000);
  }

  const clickSelector = '[id^="sidebar-menu-page-"], [id^="shortcut-link-"], [id^="navbar-link-"], [id^="profile-nav-"], [id^="shortcut-add-button"], [id^="dashboard-write-report-button"]';

  $(document).on("click", clickSelector, function (e) {
    const url = $(this).data("route");
    if (!url || url === window.location.href) return;

    e.preventDefault();

    $footer.stop(true, true).fadeOut(200);
    $mainContent.stop(true, true).fadeOut(200).promise().done(function () {
      $loader.stop(true, true).fadeIn(300).promise().done(function () {
        window.location.href = url;
      });
    });
  });
});
