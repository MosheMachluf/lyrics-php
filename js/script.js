$(".file-input").on("change", function (e) {
  $(".file-name").text(e.target.files[0].name);
});

$(".confirm-delete").on("click", function () {
  if (confirm("Are you sure you want to delete?")) return true;
  else return false;
});
