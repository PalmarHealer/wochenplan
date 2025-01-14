let isSelecting = false;
let startCell = null;
let endCell = null;

function addColumn() {
    const colCount = $("#editableTable tbody tr:first-child td").length + 1;
    const alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    const newColHeader = alphabet[colCount - 1];

    $("#tableHeader").append('<th class="Header">' + newColHeader + "</th>");

    $("#editableTable tbody tr").each(function (index) {
        $(this).append("<td></td>");
    });
}

function addRow() {
    const table = $("#editableTable");
    const columnsCount = table.find("tr:first-child td").length;
    const rowsCount = $("#editableTable tbody tr").length;
    const newRow = $("<tr>");
    newRow.append('<th class="Header">' + (rowsCount + 1) + "</th>");

    for (let i = 0; i < columnsCount; i++) {
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
    const startRowIndex = startCell.parentNode.rowIndex;
    const startCellIndex = startCell.cellIndex;
    const endRowIndex = endCell.parentNode.rowIndex;
    const endCellIndex = endCell.cellIndex;
    const rowIndex = cell.parentNode.rowIndex;
    const cellIndex = cell.cellIndex;

    const minRowIndex = Math.min(startRowIndex, endRowIndex);
    const maxRowIndex = Math.max(startRowIndex, endRowIndex);
    const minCellIndex = Math.min(startCellIndex, endCellIndex);
    const maxCellIndex = Math.max(startCellIndex, endCellIndex);

    return rowIndex >= minRowIndex && rowIndex <= maxRowIndex && cellIndex >= minCellIndex && cellIndex <= maxCellIndex;
}

function endSelection(event) {
    isSelecting = false;
    $(event.target).closest("td").addClass("selected");


    if ($(event.target).closest("#editableTable").length > 0) {
        const selectedCells = $('td.selected');

        if (selectedCells.length === 1) {
            $(".form-control").not("#color").prop('disabled', false);
            $(".form-group").not("#name").prop('disabled', false);

            const time = selectedCells.attr('time');
            const room = selectedCells.attr('room');
            const label = $.trim(selectedCells.html());

            $('.form-control#time').val(time);
            $('.form-control#room').val(room);
            $('.form-group#name').val(label);
        } else {

            $(".form-control").not("#color").prop('disabled', true);
            $(".form-group").not("#name").prop('disabled', true);
            $('.form-control#time').val(null);
            $('.form-control#room').val(null);
            $('.form-group#name').val(null);
        }
    }

}

function updateSelectedCell() {
    const selectedCells = $('td.selected');

    if (selectedCells.length === 1) {
        selectedCells.removeClass("show-info");

        const timeValue = $('.form-control#time').val();
        const roomValue = $('.form-control#room').val();

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
    const selectedCells = $('td.selected');

    if (selectedCells.length === 1) {
        const labelValue = $('#name').val();
        selectedCells.html(labelValue);
    }
}


function mergeCells() {
    const firstSelectedRow = $("#editableTable tbody tr")
        .filter(function () {
            return $(this).find("td.selected").length > 0;
        })
        .first();
    const selectedCells = $("#editableTable tbody td.selected");
    if (selectedCells.length > 1) {
        splitCells();
        const firstCell = selectedCells.first();
        firstCell.attr("colspan", firstSelectedRow.find("td.selected").length);
        firstCell.attr("rowspan", $("#editableTable tbody td.selected").closest("tr").length);
        selectedCells.slice(1).each(function () {
            $(this).addClass("hideCell");
        });
    }
}

function splitCells() {
    $("#editableTable tbody td.selected").each(function () {
        const cell = $(this);
        let colspan = parseInt(cell.attr("colspan")) || 1;
        let rowspan = parseInt(cell.attr("rowspan")) || 1;
        const startRow = cell.closest("tr").index();
        const startCol = cell.index();
        const endRow = startRow + rowspan;
        const endCol = startCol + colspan;

// Loop through the cells and remove the 'hide' class
        for (let i = startRow; i < endRow; i++) {
            for (let j = startCol - 1; j < endCol; j++) {
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

    const maxHeader = getMaximumCellCount() + 1;
    $("#editableTable thead th").each(function (index) {
        if (index >= maxHeader) {
            $(this).remove();
        }
    });
}

function getMaximumCellCount() {
    let maxCells = 0;
    $("#editableTable tbody tr").each(function () {
        let cellsCount = $(this).find("td").length;
        maxCells = Math.max(maxCells, cellsCount);
    });
    return maxCells;
}

function dyeCells() {
    const selectedValue = $('.form-control#color');
    $('.selected').css('background-color', selectedValue.val());
    selectedValue.val('1');
}

function toggleCenterText() {
    $('.selected').toggleClass("center2");
}

function sendData() {
    const clone = $("table").clone();
    clone.find("#tableHeader").addClass("hideCell");
    clone.find(".Header").addClass("hideCell");
    const textContent = $.trim(clone.html());
    const name = $('.form-group#name').val();
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
            const btnText = $('.send').text();
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