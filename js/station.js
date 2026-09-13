
function startAvailabilityPolling(stationId, elementId, intervalMs) {
    intervalMs = intervalMs || 10000;
    var el = document.getElementById(elementId);
    if (!el) return;

    function poll() {
        fetch("../../controller/StationAvailabilityController.php?stationId=" + encodeURIComponent(stationId))
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data && typeof data.available !== "undefined") {
                    el.textContent = data.available;
                }
            })
            .catch(function () { /* silently ignore transient network errors */ });
    }

    poll();
    setInterval(poll, intervalMs);
}
