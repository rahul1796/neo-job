<style type="text/css">
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  edit associates list
 * @date  Nov_2016
*/
select.input-sm 
{
 line-height: 10px; 
}
.searchprint
{
  text-align: right;
}
.searchprint .btn-group
{
  padding-bottom: 5px;
}
</style>
<div class="inner">

  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#jobs">Jobs</a></li>
    <li><a data-toggle="tab" href="#aplication_tracker">Aplication Tracker</a></li>
    <li><a data-toggle="tab" href="#menu2">Menu2</a></li>
  </ul>
  <div class="tab-content">
    <div id="jobs" class="tab-pane fade in active">
      <h3>Jobs</h3>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
    </div>
    <div id="aplication_tracker" class="tab-pane fade">
      <h3>Aplication Tracker</h3>
      <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
    </div>
    <div id="menu2" class="tab-pane fade">
      <h3>Menu 2</h3>
      <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
    </div>
  </div>
</div>
<script>
$(document).ready(function() 
{
if(location.hash) 
{
    $('a[href=' + location.hash + ']').tab('show');
}
  $(document.body).on("click", "a[data-toggle]", function(event) {
      location.hash = this.getAttribute("href");
  });
});
$(window).on('popstate', function() {
  var anchor = location.hash || $("a[data-toggle=tab]").first().attr("href");
  $('a[href=' + anchor + ']').tab('show');
});


</script>