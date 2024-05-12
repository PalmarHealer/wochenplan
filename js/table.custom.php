
var isSelecting = false;
var startCell = null;
var endCell = null;

function addColumn() {
    var colCount = $("#editableTable tbody tr:first-child td").length + 1;
    var alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    var newColHeader = alphabet[colCount - 1];

    $("#tableHeader").append('<th class="Header">' + newColHeader + "</th>");

    $("#editableTable tbody tr").each(function (index) {
        $(this).append("<td></td>");
    });
}

function addRow() {
    var table = $("#editableTable");
    var columnsCount = table.find("tr:first-child td").length;
    var rowsCount = $("#editableTable tbody tr").length;
    var newRow = $("<tr>");
    newRow.append('<th class="Header">' + (rowsCount + 1) + "</th>");

    for (var i = 0; i < columnsCount; i++) {
        newRow.append("<td></td>");
    }
    newRow.append("</tr>");

    table.find("tbody").append(newRow);
}

function startSelection(event) {
    if ($(event.target).closest("#editableTable").length > 0) {
        $("#editableTable td").removeClass("selected");
    }
    isSelecting = true;
    startCell = event.target;
    endCell = event.target;
}

function selectCells(event) {
    if ($(event.target).closest("#editableTable").length > 0 && isSelecting) {
        endCell = event.target;
        highlightCells();
    }
}

function highlightCells() {
    $("#editableTable td").removeClass("selected");
    var table = document.getElementById("editableTable");
    var cells = table.getElementsByTagName("td");
    for (var i = 0; i < cells.length; i++) {
        var cell = cells[i];
        if (isBetween(cell, startCell, endCell)) {
            $(cell).addClass("selected");
        }
    }
}

function isBetween(cell, startCell, endCell) {
    var startRowIndex = startCell.parentNode.rowIndex;
    var startCellIndex = startCell.cellIndex;
    var endRowIndex = endCell.parentNode.rowIndex;
    var endCellIndex = endCell.cellIndex;
    var rowIndex = cell.parentNode.rowIndex;
    var cellIndex = cell.cellIndex;

    var minRowIndex = Math.min(startRowIndex, endRowIndex);
    var maxRowIndex = Math.max(startRowIndex, endRowIndex);
    var minCellIndex = Math.min(startCellIndex, endCellIndex);
    var maxCellIndex = Math.max(startCellIndex, endCellIndex);

    return rowIndex >= minRowIndex && rowIndex <= maxRowIndex && cellIndex >= minCellIndex && cellIndex <= maxCellIndex;
}

function endSelection(event) {
    isSelecting = false;
    $(event.target).closest("td").addClass("selected");


    if ($(event.target).closest("#editableTable").length > 0) {
        var selectedCells = $('td.selected');

        if (selectedCells.length === 1) {
            $(".form-control[type='text']:not(.name)").prop('disabled', false);

            var time = selectedCells.attr('time');
            var room = selectedCells.attr('room');
            var label = $.trim(selectedCells.html());

            $('.form-control#time').val(time);
            $('.form-control#room').val(room);
            $('.form-control#label').val(label);
        } else {

            $(".form-control[type='text']:not(.name)").prop('disabled', true);
            $('.form-control#time').val(null);
            $('.form-control#room').val(null);
            $('.form-control#label').val(null);
        }
    }

}

function updateSelectedCell() {
    var selectedCells = $('td.selected');

    if (selectedCells.length === 1) {
        selectedCells.removeClass("show-info show-room-info show-time-info");

        var timeValue = $('.form-control#time').val();
        var roomValue = $('.form-control#room').val();


        if (!isNaN(timeValue) && !isNaN(roomValue)) {
            selectedCells.attr('time', timeValue);
            selectedCells.attr('room', roomValue);
            if (timeValue !== "" && roomValue !== "") {
                selectedCells.addClass("show-info");
            }
        }
    }
}

function updateText() {
    var selectedCells = $('td.selected');

    if (selectedCells.length === 1) {
        var labelValue = $('.form-control#label').val();
        selectedCells.html(labelValue);
    }
}


function mergeCells() {
    var firstSelectedRow = $("#editableTable tbody tr")
        .filter(function () {
            return $(this).find("td.selected").length > 0;
        })
        .first();
    var selectedCells = $("#editableTable tbody td.selected");
    if (selectedCells.length > 1) {
        splitCells();
        var firstCell = selectedCells.first();
        firstCell.attr("colspan", firstSelectedRow.find("td.selected").length);
        firstCell.attr("rowspan", $("#editableTable tbody td.selected").closest("tr").length);
        selectedCells.slice(1).each(function () {
            $(this).addClass("hideCell");
        });
    }
}

function splitCells() {
    $("#editableTable tbody td.selected").each(function () {
        var cell = $(this);
        var colspan = parseInt(cell.attr("colspan")) || 1;
        var rowspan = parseInt(cell.attr("rowspan")) || 1;
        var startRow = cell.closest("tr").index();
        var startCol = cell.index();
        var endRow = startRow + rowspan;
        var endCol = startCol + colspan;

// Loop through the cells and remove the 'hide' class
        for (var i = startRow; i < endRow; i++) {
            for (var j = startCol - 1; j < endCol; j++) {
                $("#editableTable tbody tr").eq(i).find("td").eq(j).removeClass("hideCell");
            }
        }

// Reset rowspan and colspan for the selected cell

        cell.removeAttr("rowspan");
        cell.removeAttr("colspan");
        cell.removeClass("hideCell");
    });
}

function deleteCells() {
    $("#editableTable tbody td.selected").each(function () {
        $(this).remove();
    });

// Überprüfe, ob Zeilen leer sind und entferne sie
    $("#editableTable tbody tr").each(function () {
        if ($(this).find("td").length === 0) {
            $(this).remove();
        }
    });

    var maxHeader = getMaximumCellCount() + 1;
    $("#editableTable thead th").each(function (index) {
        if (index >= maxHeader) {
            $(this).remove();
        }
    });
}

function getMaximumCellCount() {
    var maxCells = 0;
    $("#editableTable tbody tr").each(function () {
        var cellsCount = $(this).find("td").length;
        maxCells = Math.max(maxCells, cellsCount);
    });
    return maxCells;
}

function dyeCells() {
    var selectedValue = $('.form-control#color');
    $('.selected').css('background-color', selectedValue.val());
    selectedValue.val('1');
}

function toggleCenterText() {
    $('.selected').toggleClass("center2");
}

function sendData() {
    var clone = $("table").clone();
    clone.find("#tableHeader").addClass("hideCell");
    clone.find(".Header").addClass("hideCell");
    var textContent = $.trim(clone.html());
    var name = $('.form-control#name').val();
    $.ajax({
        type: 'POST',
        url: '../save/ajax.php',
        data: {
            type: "plan",
            name: name,
            plan: textContent
        },
        dataType: 'json',
        success: function(response){
            var btnText = $('.send').text();
            $('.send').text(response.message).delay(3000).queue(function(){
                $('.send').text(btnText);
                $(this).dequeue();
            });

        },
        error: function(xhr, status, error){
            console.error(xhr.responseText);
        }
    });
}

$(document).on("mousedown", startSelection);
$(document).on("mousemove", selectCells);
$(document).on("mouseup", endSelection);
$(document).ready(function() {
    $(".Header").removeClass("hideCell");
    $("#tableHeader").removeClass("hideCell");
    $("td").removeClass("selected");
});

$('.form-control#time, .form-control#room').on('input', updateSelectedCell);
$('.form-control#label').on('input', updateText);