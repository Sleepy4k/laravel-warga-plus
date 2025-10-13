$(document).ready(function () {
  $("#main-content").hide();
  $("#loader").show();

  $(window).on("load", function () {
    $("#loader").fadeOut(300, function () {
      $("#main-content").fadeIn(200);
    });
  });

  $("[id^=sidebar-menu-page-], [id^=shortcut-link-], [id^=navbar-link-], [id^=profile-nav-], [id^=shortcut-add-button]").on("click", function (e) {
    e.preventDefault();
    const url = $(this).data("route");
    if (url && url !== window.location.href) {
      $("#main-content").fadeOut(200, function () {
        $("#loader").fadeIn(300, function () {
          window.location.href = url;
        });
      });
    }
  });
});
