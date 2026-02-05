document.addEventListener("DOMContentLoaded", function () {
    try {
        if (typeof DecoxProjectView === "undefined") {
            return;
        }

        const id  = DecoxProjectView.projectId;
        const key = "project_view_" + id;
        const now = Date.now();

        // --- 1) Xác định chế độ luôn tăng view hay không ---
        let alwaysCount = !!DecoxProjectView.alwaysCount;

        // Nếu có thêm ?debug_views=1 trên URL thì luôn tăng view, không cần đổi PHP
        if (window.location.search.indexOf("debug_views=1") !== -1) {
            alwaysCount = true;
        }

        // --- 2) Logic tăng view ---
        let shouldIncrease = true;

        if (!alwaysCount) {
            // Chế độ bình thường: 1 view / 30 phút / post / user
            const last = localStorage.getItem(key);
            if (last && (now - parseInt(last, 10)) < 30 * 60 * 1000) {
                shouldIncrease = false;
            } else {
                localStorage.setItem(key, now.toString());
            }
        }

        if (shouldIncrease) {
            fetch(DecoxProjectView.endpoint, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-WP-Nonce": DecoxProjectView.nonce
                },
                body: JSON.stringify({ project_id: id })
            }).catch(function () {});
        }

        // --- 3) Luôn gọi API lấy view để hiển thị (bypass cache) ---
        const countSpan = document.getElementById("project-view-count");
        if (countSpan) {
            const countUrl = DecoxProjectView.endpoint
                .replace("project-view", "project-view-count")
                + "?project_id=" + encodeURIComponent(id);

            fetch(countUrl, {
                method: "GET"
            })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data && typeof data.views !== "undefined") {
                        countSpan.textContent = data.views.toLocaleString("vi-VN");
                    }
                })
                .catch(function () {});
        }
    } catch (e) {
        // ignore
    }
});
