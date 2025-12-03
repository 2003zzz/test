export function saveDownloadedFile(fileData, name = "Downloaded file") {
  const blob = new Blob([fileData]);
  const link = document.createElement("a");
  link.href = window.URL.createObjectURL(blob);
  link.download = name;
  link.click();
}
