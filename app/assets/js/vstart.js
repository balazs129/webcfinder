// Open visualization in new window
window.onload = function () {
    "use strict";
    var btnCollection = document.getElementsByClassName("btn-visualize"),
        btnArray = [].slice.call(btnCollection);

    var btnClick = function (e) {
        var jobID = e.target.id,
            url = "/visualize/" + jobID;

        var visWindow = window.open(url, "Visualization", "width=800, height=600");

        if (visWindow) {
            visWindow.focus();
        } else {
            window.alert("Please allow popups for this site!");
        }
    };

    btnArray.forEach(function (btn) {
        btn.addEventListener("click", btnClick);
    });
};