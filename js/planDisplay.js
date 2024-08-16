let isUpdating = false;

$(document).ready(function() {
    updateProgressBar();
});

let updateBar = true;
function updateProgressBar() {
    const progressBar = $('.progress-bar');
    const currentValue = progressBar.width() / progressBar.parent().width() * 100;
    if (currentValue < 100 && updateBar) {
        progressBar.css('width', currentValue + 5 + '%');
        setTimeout(updateProgressBar, 100);
    }
}

function customPrint() {
    if (navigator.userAgent.indexOf("Firefox") === -1) {
        // Browser is not Firefox
        window.print();
    } else {
        // Browser is Firefox
        const html = `
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Nicht unterstützt</strong> das Drucken in Firefox wird leider nicht unterstützt.<button onclick="closePrintAlert()" type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>`;
        $(".alert-message").html(html);
    }

}
function closePrintAlert() {
    $(".alert-dismissible").fadeOut();
}

const elem = document.documentElement;
function openFullscreen() {
    if (elem.requestFullscreen) {
        elem.requestFullscreen();
    } else if (elem.webkitRequestFullscreen) { /* Safari */
        elem.webkitRequestFullscreen();
    } else if (elem.msRequestFullscreen) { /* IE11 */
        elem.msRequestFullscreen();
    }
    $(".open_fullscreen").hide();
    $(".close_fullscreen").show();
}

function closeFullscreen() {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.webkitExitFullscreen) { /* Safari */
        document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) { /* IE11 */
        document.msExitFullscreen();
    }
    $(".open_fullscreen").show();
    $(".close_fullscreen").hide();
}

function fetchData(fullReload = false, dateParam) {
    const urlParams = new URLSearchParams(window.location.search);
    const dateValue = dateParam || urlParams.get('date');
    let modeData = urlParams.get('mode') || 'normal';
    let deferred = $.Deferred();  // Create a Deferred object

    $.ajax({
        url: `./reload<?php echo ($_GET['version'] ?? '3') ?>.php`,
        type: "POST",
        data: {
            date: dateValue,
            mode: modeData
        },
        cache: false,
        success: function(data) {
            if (fullReload || isUpdating) {
                console.log("Data loaded");
                updateBar = false;
                $('.progress-bar').css('width', '100%');
                setTimeout(function() {
                    $(".full").fadeOut(250, function() {
                        $(this).html(data).fadeIn(250);
                        deferred.resolve(true);
                    });
                }, 250);
            } else {
                updateBar = false;
                console.log("Data reloaded");
                $('.full').html(data);
                deferred.resolve(true);
            }
        },
        error: function() {
            console.error("Failed to load data");
            deferred.reject(false);
        }
    });

    return deferred.promise();
}


function updateDateInUrl(daysToAddOrSubtract, element) {
    if (isUpdating) {
        console.log("Update is already in progress.");
        return $.Deferred().reject().promise();
    }
    isUpdating = true;

    const urlParams = new URLSearchParams(window.location.search);
    const dateString = urlParams.get('date');
    const date = dateString ? new Date(dateString) : new Date();

    date.setDate(date.getDate() + daysToAddOrSubtract);

    if (daysToAddOrSubtract > 0) {
        while (date.getDay() === 0 || date.getDay() === 6) {
            if (date.getDay() === 6) {
                date.setDate(date.getDate() + 2);
            } else {
                date.setDate(date.getDate() + 1);
            }
        }
    } else {
        while (date.getDay() === 0 || date.getDay() === 6) {
            if (date.getDay() === 0) {
                date.setDate(date.getDate() - 2);
            } else {
                date.setDate(date.getDate() - 1);
            }
        }
    }

    const year = date.getFullYear();
    const month = date.getMonth() + 1;
    const day = date.getDate();
    const formattedDate = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
    const newUrl = window.location.origin + window.location.pathname + '?date=' + formattedDate;
    window.history.replaceState({}, document.title, newUrl);

    document.title = "Plan - " + formattedDate.split('-').reverse().join('.');

    $(element).removeClass().addClass('spinner-border spinner-border-sm btn-spinner disabled_cursor');

    return fetchData(true, formattedDate);
}
