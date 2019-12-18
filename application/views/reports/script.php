<script>
const _MS_PER_DAY = 1000 * 60 * 60 * 24;
$(document).ready(function() {

    $('#start_date_input, #end_date_input').datetimepicker({
      //language:  'fr',
        weekStart: 1,
        endDate: '+0d',
        startView: 2,
        todayHighlight:'TRUE',
        forceParse: 0,
        showMeridian: 1,
        format: 'dd-MM-yyyy',
        autoclose: true,
        minuteStep: 1,
        todayBtn: true,
        maxView: 4,
        minView: 2
      });
    });

    function downloadreport() {
      $('#alert-box').addClass('hidden');
      $('#alert-box').removeAttr('style');

      let start_date = $('#start_date_input').val();
      let end_date = $('#end_date_input').val();
      let start = new Date(start_date);
      let end = new Date(end_date);
      let today = new Date();
      //let report = ($('#report_id').find(':selected').val()) || '';
      let report = ($('#report_id').val()) || '';
      let reportURL = '<?= base_url('reports/');?>'+report;
      //console.log(dateDiffInDays(end, start));
      if(report=='' || start_date=='' || end_date=='') {
        $('#alert-box').removeClass('hidden');
        $('#status_message').html('').html("All fields are required");
        hideErrorAlert();
        return;
      }
      else if(start>today || end>today) {
        $('#alert-box').removeClass('hidden');
        $('#status_message').html('').html("Start/End date must be less than today's date");
        hideErrorAlert();
        return;
      } else if(end<start) {
        $('#alert-box').removeClass('hidden');
        $('#status_message').html('').html('Start date must be less than End date ');
        hideErrorAlert();
        return;
      } else if(dateDiffInDays(end, start)>360){
        $('#alert-box').removeClass('hidden');
        $('#status_message').html('').html('Maximum 360 Days report allowed at this time');
        hideErrorAlert();
        return;
      }
        else {
        reportURL = reportURL+'?start_date='+start_date.toString()+'&end_date='+end_date.toString();
        window.open(reportURL,'_blank');
        //window.location.href = reportURL;
      }
   }

   function hideErrorAlert() {
     window.setTimeout(function() {
       $("#alert-box").fadeTo(500, 0).slideUp(500, function(){
         $("#alert-box").addClass('hidden');
       });
     }, 3000);
   }
    // a and b are javascript Date objects
    function dateDiffInDays(a, b) {
      // Discard the time and time-zone information.
      const utc1 = Date.UTC(a.getFullYear(), a.getMonth(), a.getDate());
      const utc2 = Date.UTC(b.getFullYear(), b.getMonth(), b.getDate());

      return Math.abs(Math.floor((utc2 - utc1) / _MS_PER_DAY));
    }


</script>
