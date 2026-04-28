if (window.__view_id) {
    let startTime = Date.now();

    function sendTime() {
        const timeSpent = Math.floor((Date.now() - startTime) / 1000);

        const data = new FormData();
        data.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        data.append('view_id', window.__view_id);
        data.append('duration', timeSpent);

        navigator.sendBeacon(window.__page_exit_route, data);
    }

    document.addEventListener("visibilitychange", function () {
        if (document.visibilityState === "hidden") {
            sendTime();
        }
    });

    window.addEventListener("beforeunload", sendTime);
}
