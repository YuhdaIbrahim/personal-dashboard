$("#close-sidebar").click(function() {
  $(".page-wrapper").removeClass("toggled");
});
$("#show-sidebar").click(function() {
  $(".page-wrapper").addClass("toggled");
});
// sidebar
$('#sidebarBar').on('click', function () {
$('#sidebar').toggleClass('active');
});
       