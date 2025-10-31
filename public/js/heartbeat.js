$(document).ready(function () {
  let isFocused = true;
  const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

  function sendHeartbeat() {
    const loginUrl = "{{ route('login') }}";

    fetch("{{ route('profile.heartbeat') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": csrfToken,
      },
      body: JSON.stringify({
        isFocused: isFocused,
      }),
    })
      .then((response) => {
        if (response.ok) {
          if (response.redirected && response.url === loginUrl) {
            console.log("User is not authenticated, redirecting to login...");
            window.location.href = loginUrl;
          } else {
            response.json().then((result) => {
              $class =
                "avatar avatar-" + result.data.last_seen + " position-relative";
              $("#user-avatar").removeClass().addClass($class);
              $("#user-avatar-drop").removeClass().addClass($class);
            });
          }
        } else {
          console.error("Heartbeat failed:", response.statusText);
          if (response.status === 401) {
            window.location.href = loginUrl;
          }
        }
      })
      .catch((error) => {
        console.error("Heartbeat failed:", error);
      });
  }

  window.addEventListener("visibilitychange", function () {
    if (document.visibilityState === "visible") {
      isFocused = true;
      sendHeartbeat();
    } else {
      isFocused = false;
    }
  });

  sendHeartbeat();

  setInterval(sendHeartbeat, 2 * 60 * 1000);
});
