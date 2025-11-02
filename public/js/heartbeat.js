document.addEventListener("DOMContentLoaded", function () {
  let isFocused = true;

  const baseUrl = window.location.origin;
  const csrfToken = document
    .querySelector('meta[property="csrf-token"]')
    .getAttribute("content");
  const loginUrl = document
    .querySelector('meta[property="login-url"]')
    .getAttribute("content") || baseUrl + "/login";
  const heartbeatUrl = document
    .querySelector('meta[property="heartbeat-url"]')
    .getAttribute("content") || baseUrl + "/profile/heartbeat";

  function sendHeartbeat() {
    fetch(heartbeatUrl, {
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
            if (!window.location.href.includes("/dashboard")) {
              return;
            }

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
