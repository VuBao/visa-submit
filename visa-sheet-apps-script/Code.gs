/**
 * Visa Applications - private Drive image sync
 * Bind this script to the Visa Applications spreadsheet. It intentionally
 * uses the bound spreadsheet, so no production Sheet ID is stored in Git.
 */
const DASHBOARD_SHEET = 'Dashboard';
const IMAGE_COLUMN = 5; // Column E in applicant tabs.
const PREVIEW_COLUMN = 3; // Column C keeps the Drive file link/formula.
const FIRST_DOCUMENT_ROW = 9;

function onOpen() {
  SpreadsheetApp.getUi()
    .createMenu('Visa tools')
    .addItem('Sync applicant images', 'syncVisaImages')
    .addItem('Install 5-minute auto sync', 'installVisaTrigger')
    .addToUi();
}

function syncVisaImages() {
  const spreadsheet = SpreadsheetApp.getActiveSpreadsheet();
  spreadsheet.getSheets().forEach(sheet => {
    if (sheet.getName() === DASHBOARD_SHEET || sheet.getLastRow() < FIRST_DOCUMENT_ROW) return;
    syncApplicantSheet_(sheet);
  });
  spreadsheet.toast('Visa images synchronized.', 'Visa tools', 4);
}

function syncApplicantSheet_(sheet) {
  sheet.getImages().forEach(image => {
    const anchor = image.getAnchorCell();
    if (anchor && anchor.getColumn() === IMAGE_COLUMN) image.remove();
  });
  const lastRow = sheet.getLastRow();
  const formulas = sheet.getRange(FIRST_DOCUMENT_ROW, PREVIEW_COLUMN, lastRow - FIRST_DOCUMENT_ROW + 1, 1).getFormulas();
  let inserted = 0;
  formulas.forEach((row, index) => {
    const formula = row[0] || '';
    const match = formula.match(/\/d\/([^/"?&]+)/i) || formula.match(/[?&]id=([^"&)]+)/i);
    if (!match) return;
    try {
      const blob = DriveApp.getFileById(match[1]).getBlob();
      const image = sheet.insertImage(blob, IMAGE_COLUMN, FIRST_DOCUMENT_ROW + index);
      image.setWidth(180).setHeight(120);
      sheet.setRowHeight(FIRST_DOCUMENT_ROW + index, 130);
      sheet.getRange(FIRST_DOCUMENT_ROW + index, IMAGE_COLUMN).clearContent();
      inserted++;
    } catch (error) {
      console.warn(`Cannot insert image on ${sheet.getName()} row ${FIRST_DOCUMENT_ROW + index}: ${error}`);
      sheet.getRange(FIRST_DOCUMENT_ROW + index, IMAGE_COLUMN).setValue('Không thể chèn ảnh - kiểm tra quyền Drive');
    }
  });
  return inserted;
}

function installVisaTrigger() {
  ScriptApp.getProjectTriggers().forEach(trigger => {
    if (trigger.getHandlerFunction() === 'syncVisaImages') ScriptApp.deleteTrigger(trigger);
  });
  ScriptApp.newTrigger('syncVisaImages').timeBased().everyMinutes(5).create();
  SpreadsheetApp.getActiveSpreadsheet().toast('Auto sync installed (every 5 minutes).', 'Visa tools', 4);
}
