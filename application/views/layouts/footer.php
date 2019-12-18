<script type='text/javascript'>
    $(document).ready(function(){
        $(window).scroll(function(){
            if ($(this).scrollTop() > 100) {
                $('#scroll').fadeIn();
            } else {
                $('#scroll').fadeOut();
            }
        });
        $('#scroll').click(function(){
            $("html, body").animate({ scrollTop: 0 }, 600);
            return false;
        });
    });
    $(document).ready(function() {
    $(".dropdown-toggle").dropdown();
});
</script>
<style type="text/css">
    /* BackToTop button css */
    #scroll {
        position:fixed;
        right:90px;
        bottom:15px;
        cursor:pointer;
        width:35px;
        height:35px;
        background-color:#ef7f1a;
        text-indent:-9999px;
        display:none;
        -webkit-border-radius:60px;
        -moz-border-radius:60px;
        border-radius:60px
    }
    #scroll span {
        position:absolute;
        top:50%;
        left:50%;
        margin-left:-8px;
        margin-top:-12px;
        height:0;
        width:0;
        border:8px solid transparent;
        border-bottom-color:#ffffff
    }
    #scroll:hover {
        background-color:#bb610f;
        opacity:1;filter:"alpha(opacity=50)";
        -ms-filter:"alpha(opacity=50)";
    }
</style>
<script type="text/javascript" src="<?php echo base_url().'assets/js/bootstrap-datepicker.min.js'?>"></script>
<footer class="footer navbar-fixed-bottom footer-light" style="background-color: #fff;padding: 10px;font-size:12px;">
    <a href="javascript:void(0);" id="scroll" title="Scroll to Top" style="display: none;">Top<span></span></a>
    <div class="pull-right hidden-xs"> <b>Version</b> 1.0</div>
    <strong>Copyright © <?php echo date("Y"); ?>. <a href="https://labournet.in" style="color: #ef7f1a;">LabourNet</a>, All Rights Reserved.</strong>
</footer>
