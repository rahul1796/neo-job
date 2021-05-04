<?php
  $user_det = $this->session->userdata('usr_authdet');
?>

<style>
    /* The CSS */
   /* select {
        padding:3px;
        margin: 0;
        -webkit-border-radius:4px;
        -moz-border-radius:4px;
        border-radius:4px;
        -webkit-box-shadow: 0 1px 0 #ccc, 0 -1px #fff inset;
        -moz-box-shadow: 0 1px 0 #ccc, 0 -1px #fff inset;
        box-shadow: 0 1px 0 #ccc, 0 -1px #fff inset;
        background: #f8f8f8;
        color:#888;
        border:none;
        outline:none;
        display: inline-block;
        -webkit-appearance:none;
        -moz-appearance:none;
        appearance:none;
        cursor:pointer;
    }


    input[type=text], textarea {
        -webkit-transition: all 0.30s ease-in-out;
        -moz-transition: all 0.30s ease-in-out;
        -ms-transition: all 0.30s ease-in-out;
        -o-transition: all 0.30s ease-in-out;
        outline: none;
        border: 1px solid #DDDDDD;
    }

    input[type=text]:focus, textarea:focus {
        box-shadow: 0 0 5px rgba(154, 18, 179, 1);
        border: 1px solid rgba(154, 18, 179, 1);
    }
     !* Let's get this party started *!
    ::-webkit-scrollbar {
        width: 12px;
    }

    !* Track *!
    ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        -webkit-border-radius: 0px;
        border-radius: 0px;
    }

    !* Handle *!
    ::-webkit-scrollbar-thumb {
        -webkit-border-radius: 0px;
        border-radius: 0px;
        background: #b392b9;
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5);
    }
    ::-webkit-scrollbar-thumb:window-inactive {
        background: rgba(154,18,179,0.4);
    }*/
ul {
list-style-type: none;
margin: 0;
padding: 0;
}

#nav {
width: 980px;
margin: 0 auto;
list-style: none;
margin-top: 7px;
padding-left: 50px;
}
#nav li {
float:left;
margin-left: 55px;
}
#nav a {

text-align:center;99
width:150px; /* fixed width */
text-decoration:none;
}
.modal-open {
    overflow: visible;
}
.modal-open, .modal-open .navbar-fixed-top, .modal-open .navbar-fixed-bottom {
    padding-right:0px!important;
}
header,
.demo,
.demo p {
 /*   margin: 4em 0;*/
    text-align: center;
}

/**
 * Tooltip Styles
 */

/* Add this attribute to the element that needs a tooltip */
[data-tooltip] {
    position: relative;
    z-index: 2;
    cursor: pointer;
}

/* Hide the tooltip content by default */
[data-tooltip]:before,
[data-tooltip]:after {
    visibility: hidden;
    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
    filter: progid: DXImageTransform.Microsoft.Alpha(Opacity=0);
    opacity: 0;
    pointer-events: none;
}

/* Position tooltip above the element */
[data-tooltip]:before {
    position: absolute;
    bottom: -50%;
    left: 50%;
    margin-bottom: 5px;
    margin-left: -80px;
    padding: 7px;
    width: 100px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    background-color: #000;
    background-color: hsla(0, 0%, 20%, 0.9);
    color: #fff;
    content: attr(data-tooltip);
    text-align: center;
    font-size: 14px;
    line-height: 1.2;
}

/* Triangle hack to make tooltip look like a speech bubble */
[data-tooltip]:after {
    position: absolute;
    top: 77%;
    left: 50%;
    margin-left: -5px;
    width: 0;
    border-bottom: 5px solid #000;
    border-bottom: 5px solid hsla(0, 0%, 20%, 0.9);
    border-right: 5px solid transparent;
    border-left: 5px solid transparent;
    content: " ";
    font-size: 0;
    line-height: 0;
}

/* Show tooltip content on hover */
[data-tooltip]:hover:before,
[data-tooltip]:hover:after {
    visibility: visible;
    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
    filter: progid: DXImageTransform.Microsoft.Alpha(Opacity=100);
    opacity: 1;
}
</style>

<?php if($user_det)
{
?>
 <!-- navbar-fixed-top-->

    <!-- font icons-->

   <!-- <link rel="stylesheet" href="https://jqueryvalidation.org/files/demo/site-demos.css">-->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="<?php echo base_url('adm-assets/vendors/js/ui/screenfull.min.js')?>" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('adm-assets/fonts/icomoon.css')?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('adm-assets/fonts/flag-icon-css/css/flag-icon.min.css')?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('adm-assets/vendors/css/sliders/slick/slick.css')?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('adm-assets/vendors/css/extensions/pace.css')?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('adm-assets/vendors/css/charts/morris.css')?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('adm-assets/vendors/css/extensions/unslider.css')?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('adm-assets/vendors/css/weather-icons/climacons.min.css')?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('adm-assets/css/bootstrap-extended.min.css')?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('adm-assets/css/app.min.css')?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('adm-assets/css/colors.min.css')?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('adm-assets/css/core/menu/menu-types/vertical-content-menu.min.css')?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('adm-assets/css/core/menu/menu-types/vertical-overlay-menu.min.css')?>">


    <!-- BEGIN Custom CSS-->

    <nav class="header-navbar navbar navbar-with-menu navbar-fixed-top navbar-semi-dark navbar-shadow" style="background-color: #ef7f1a">
      <div class="navbar-wrapper">
        <div class="navbar-header">
          <ul class="nav navbar-nav">
            <li class="nav-item mobile-menu hidden-md-up float-xs-left"><a class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="icon-menu5 font-large-1"></i></a></li>
            <li class="nav-item"><a href="<?= site_url('pramaan/dashboard');?>" class="navbar-brand nav-link"><img alt="branding logo" src="<?php echo base_url('adm-assets/images/logo/robust-logo-dark.png');?>" data-expand="<?php echo base_url('adm-assets/images/logo/robust-logo-dark.png');?>" data-collapse="<?php echo base_url('adm-assets/images/logo/robust-logo-small.png');?>" class="brand-logo"></a></li>
            <li class="nav-item hidden-md-up float-xs-right"><a data-toggle="collapse" data-target="#navbar-mobile" class="nav-link open-navbar-container"><i class="icon-ellipsis pe-2x icon-icon-rotate-right-right"></i></a></li>
          </ul>
        </div>
        <div class="navbar-container content container-fluid">
          <div id="navbar-mobile" class="collapse navbar-toggleable-sm">
            <ul class="nav navbar-nav demo" style="float: right;">
              <!--<li class="nav-item hidden-sm-down" style="border-left: 1px solid #d86905;"><a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="true"><i class="fa fa-bell"></i></a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-lg show" style="top: 49px; margin-right: 122px;" x-placement="top-end"></a></div>
              </li>-->
              <li class="nav-item hidden-sm-down" style="border-left: 1px solid #d86905;"><a class="nav-link nav-menu-main menu-toggle hidden-xs" title="Collapse"><i class="icon-menu5"></i></a></li>
              <li class="nav-item hidden-sm-down" style="border-left: 1px solid #d86905;"><a href="#"  class="nav-link nav-link-expand" title="Fullscreen" ><i class="ficon icon-expand2"></i></a></li>
            </ul>

            <ul class="nav navbar-nav float-xs-right">

              <li class="dropdown dropdown-user nav-item"><a style="padding: 6px 7px;" href="#" data-toggle="dropdown" class="dropdown-toggle nav-link dropdown-user-link"><span class="avatar avatar-online"><img src="<?php echo base_url('adm-assets/images/portrait/small/avatar-s-1.png');?>" alt="avatar"><i></i></span><span><?php echo $user_det['email'];?></span><br><small style="margin-left: 37px; font-size: small;"><?php echo $user_det['role_name'];?></small></a>

                  <div class="dropdown-menu dropdown-menu-right"><a href="<?php echo base_url('pramaan/changepassword/');?>"  class="dropdown-item"><i class="icon-lock"></i> Change Password</a>
                  <!--<div class="dropdown-divider"></div>--><a href="<?php echo base_url('pramaan/logout/');?>" class="dropdown-item"><i class="icon-power3"></i> Logout</a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>

<div id="nav_left">
    <?php
    if($user_det)    
        $this->load->view('common/navigation');
    ?>

</div>

<?php
}
else
{
?>

     <header class="main-header">

    <!-- Header Navbar -->
         <link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/menu/dropdowns.css'?>">
         <link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/menu/dropdowns-skin-discrete.css'?>">
         <link href="<?php echo base_url().'assets/dist/css/styleBD.css'?>" rel="stylesheet" type="text/css"/>
         <link rel="stylesheet" type="text/css" href="<?php echo base_url('adm-assets/fonts/icomoon.css')?>">
<!--    <nav class="navbar navbar-static-top" style="background-color:#9a12b3">
     <div class="navbar-custom-menu" style="    margin-top: 13px;">
          <ul id="nav">

                  <a href="<?php /*echo base_url('');*/?>"><li>Home</li></a>
                  <a href="<?php /*echo site_url('pramaan/aboutUs');*/?>"><li>About Us</li></a>
                  <a href="<?php /*echo site_url('pramaan/jobs');*/?>"><li>Jobs</li></a>
                  <a href="<?php /*echo site_url('employer/corporate');*/?>"><li>Corporate</li></a>
                  <a href="<?php /*echo site_url('pramaan/contactUs');*/?>"><li>Contact us</li></a>
              <li>

                  <button type="button" class="btn btn-labeled btn-success m-b-5" data-toggle="modal" onclick="login()" style=" margin-left: 185px; margin-top: -8px;">
                        <span class="btn-label"><i class="icon-android-lock" style="font-size: small;"></i></span>Login
                  </button>
              </li>
          </ul>
      </div>
    </nav>-->
         <?php
/*         $txt = "testing";
         $encrypttext = urlencode($this->encrypt->encode($txt));
         */?>
         <a href="<?php echo base_url('');?>"><img src="<?php echo base_url().'assets/dist/img/logo.png';?>" style="float: left; height: 50px; margin: 10px -106px 0px 0px;"/></a>
         <div class="dropdowns">

             <a class="toggleMenu" href="#">Menu</a>

             <ul class="nav">

                 <li  class="test" style="margin-left: 46%;">
                     <a href="<?php echo base_url('');?>">Home</a>
                 </li>
                <!-- <li>
                     <a href="<?php /*echo site_url('pramaan/aboutUs');*/?>">About us</a>
                 </li>-->
                 <li>
                     <a href="<?php echo site_url("/jobs/");?>">Jobs</a>
                 </li>
                <!-- <li>
                     <a href="<?php /*echo site_url('employer/corporate');*/?>">Corporate</a>
                 </li>-->
                 <li>
                     <a href="<?php echo site_url("pramaan/contactUs/");?>">Contact us</a>
                 </li>
                 <li style=" margin-top: 73px;  margin-left: 60px;">
                 <button type="button" id="login" class="btn btn-labeled btn-success m-b-5" data-toggle="modal"  onclick="login()" style="float: right; margin-top: -58px; /*margin-right: 55px;*/">
                     <span class="btn-label"><i class="icon-android-lock" style="font-size: small;"></i></span>Login
                 </button> </li>
             </ul>

         </div>
     </header>
<?php
}
?>

 <!-- ./wrapper -->

    <!-- Modal -->

    <div class="modal fade text-xs-left" id="signmein" tabindex="-1" role="dialog" aria-labelledby="myModalLabel34" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary white">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel34" style="color: white;">Member Login</h4>

                </div>
                <div id="msgDisplay" style="padding: 10px;"></div>
                <form method="POST" action="" id="loginForm" onsubmit="return false;">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-2 label-control" for="projectinput1">Username</label>
                            <div class="col-md-10">
                                <input  type="text" class="form-control" placeholder="Email address" name="username" id="username" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 label-control" for="projectinput1">Password</label>
                            <div class="col-md-10">
                                <input type="password" class="form-control" placeholder="Password" name="password" id="password" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <button type="submit" class="btn btn-success " name="btn_login" id="btn_login" style="width: 23%; margin-left: 445px; margin-bottom: -16px;">Member Login</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <span class="pull-left"><a href="javascript:recover_password()" style="color:#ef7f1a;">Forgot Your Password?</a></span> <!--<span class="pull-right">Don't have an account, <a href="<?php /*echo site_url('pramaan/contactUs');*/?>" style="color:#ef7f1a;">Contact Us</a> </span>-->
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- *************** END: recover_password screen **************** -->
    <div class="modal" id="recover_password" data-backdrop="static" data-keyboard="false"> <!-- link between the <li> above this div tag -->
        <div class="modal-dialog" >
            <div class="modal-content" >
                <div class="modal-header ">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Reset password</h4>
                </div>
                <form class="form-horizontal recover-form" method="post" id="recover_form" style="padding: 2em">
                    <div class="input-group">
                        <input type="email" id="txtResetEmail" name="email_reset" class="form-control" placeholder="Please enter Registered email id" required oninvalid="this.setCustomValidity('Please Enter valid email')" oninput="setCustomValidity('')">
                    <span class="input-group-btn" placeholder="Email address">
                        <button class="btn btn-default" type="submit">Submit</button>
                    </span>
                    </div>
                    <div style="clear:both;"></div>
                    <label id="lblResetEmailStatus" style="color:red;display:none;"></label>
                </form>
            </div>
        </div>
    </div> <!-- END of login screen-->
</div>
</div>
<!-- BEGIN PAGE VENDOR JS-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Knob/1.2.13/jquery.knob.min.js"></script>-->
<script src="<?php echo base_url('adm-assets/js/core/libraries/bootstrap.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('adm-assets/vendors/js/extensions/unslider-min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('adm-assets/vendors/js/ui/perfect-scrollbar.jquery.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('adm-assets/vendors/js/ui/unison.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('adm-assets/vendors/js/ui/blockUI.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('adm-assets/vendors/js/ui/jquery.matchHeight-min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('adm-assets/vendors/js/ui/jquery-sliding-menu.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('adm-assets/vendors/js/sliders/slick/slick.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('adm-assets/vendors/js/extensions/pace.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('adm-assets/js/core/app-menu.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('adm-assets/js/core/app.min.js')?>" type="text/javascript"></script>

<!--<script src="<?php /*echo base_url('adm-assets/js/scripts/pages/dashboard-crm.min.js')*/?>" type="text/javascript"></script>-->
<script type="text/javascript" src="<?php echo base_url().'assets/js/jquery.validate.min.js'?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/sweetalert.css'?>">
<script type="text/javascript" src="<?php echo base_url().'assets/js/sweetalert.min.js'?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/jquery.dataTables.min.css'?>">
<script type="text/javascript" src="<?php echo base_url().'adm-assets/menu/dropdowns.js'?>"></script>

<script>
    $(".dropdowns").dropdowns();
</script>

<script>
    function login()
    {
        $("#signmein").modal("show");
        $("#msgDisplay").html('');
    }
    function recover_password()
    {
        document.getElementById("recover_form").reset();
        $("#lblResetEmailStatus").html("");
        $("#signmein").modal("hide");
        $("#recover_password").modal('show');
    }
    $(function()
    {
        //binding the key press for login modal
         $('#signmein').bind('keypress', function(e)
         {
         if(e.keyCode==13)
         {
         //alert('hdfdfh');
         $('button#btn_login').trigger('click');
         }
         });
        //---------
        $("button#btn_login").click(function()
        {
            event.preventDefault();

            /* the email & the password values from the index page are assigned to varialbes */
            var username = $( "#username" ).val();
            var password = $( "#password" ).val();
            /* check and make sure the user didn't submit blank values.
             This is where you can add more validation checks which i left open for expantion */
            if(username =="" || password =="" )
            {
                var message="Email address or password fields cannot be empty";

                $("#msgDisplay").html('<div class="alert alert-warning"><a class="close" data-dismiss="alert">×</a><span>'+message+'</span></div>');
            }
            else
            {
                $.ajax({
                    type: "POST",
                    url: base_url+"pramaan/process_login", /* to validate the user input with database */
                    data:$('#loginForm').serialize(),
                    //data: {'username': username, 'password': password}, /* passing the email & the password values to loginprocess.php */
                    dataType:'json',
                    success: function(msg)
                    {
                        if(msg.status == 1)
                        {
                            //alert('test1=>: ' + msg.info) ; /* debug testing */
                            $("#signmein").modal('hide'); /* hide the dialog box if the login is successfull */
                            window.location.href=base_url+"pramaan/dashboard";

                        }
                        else
                        {
                            $("#msgDisplay").html('<div class="alert alert-warning"><a class="close" data-dismiss="alert">×</a><span>'+msg.msg_info+'</span></div>'); /* Display login failer message in the div tag id=msgDisplay */
                        }

                    },
                    error: function()
                    {
                        var message="Error occurred!";
                        $("#msgDisplay").html('<div class="alert alert-warning"><a class="close" data-dismiss="alert">×</a><span>'+message+'</span></div>');

                    }
                });

            }
            return false;
        });
        $('#signmein').on('hidden.bs.modal', function ()
        {
            $('.modal-body').find('textarea,input').val('');
            $('.modal-body').find('#msgDisplay').html('');
        });
    });

    $( "#recover_form" ).submit(function( event )
    {
        event.preventDefault();

        $("#lblResetEmailStatus").hide();

        if (!ValidateEmail($("#txtResetEmail").val()))
        {
            $("#lblResetEmailStatus").text('* Please enter a valid email!');
            $("#lblResetEmailStatus").show();
            $("#txtResetEmail").focus();
            return;
        }

        $.ajax({
            type: "POST",
            url: base_url + "UsersController/send_reset_password_info_mail",
            data:$('#recover_form').serialize(),
            dataType:'json',
            success: function(varResponse)
            {
                $("#lblResetEmailStatus").text(varResponse.message);
                $("#lblResetEmailStatus").show();

                if(varResponse.status)
                {
                    $("#lblResetEmailStatus").show();
                }
                else
                {
                    $("#txtResetEmail").focus();
                }
            },
            error: function()
            {
                alert("Error Occurred!");
            }
        });
    });

    function ValidateEmail(email) {
        const expression = /(?!.*\.{2})^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        return expression.test(String(email).toLowerCase())
    }
   
/*
    document.getElementById("msgDisplay").style.display="block";
    setTimeout(function(){
        document.getElementById("msgDisplay").style.display="none";
    }, 10000);*/

      
        function update_password() {        
        $('#modal_update_password').modal('show');
       $('#modal_update_password').on('hidden.bs.modal', function () {
        location.reload();
       })    
        
          }
            
        function checkPass()
        {
            var pass1 = document.getElementById('newpassword');
            var pass2 = document.getElementById('confirmpassword');
            var message = document.getElementById('error-nwl');
            var goodColor = "";
            var badColor = "";

            if(pass1.value.length > 7)
            {
                $('#BtnSubmit').prop('disabled',false);
                pass1.style.backgroundColor = goodColor;
                message.style.color = "green";
                message.innerHTML = "character number ok!"
            }
            else
            {
                $('#BtnSubmit').prop('disabled',true);
                pass1.style.backgroundColor = badColor;
                message.style.color = "red";
                message.innerHTML = " you have to enter at least 8 character!"
                return;
            }

            if(pass1.value == pass2.value)
            {  
                $('#BtnSubmit').prop('disabled',false);
                pass2.style.backgroundColor = goodColor;
                message.style.color = "green";
                message.innerHTML = "Password Matched!"             
            }
                else
            {   
                $('#BtnSubmit').prop('disabled',true);
                pass2.style.backgroundColor = badColor;
                message.style.color = "red";
                message.innerHTML = " These passwords don't match"
            }
        }  
  
  $(document).on('submit', '#frmUpdatePassword', function(event){
        event.preventDefault();

//        $("#lblOldPassword").hide();
        $("#lblNewPassword").hide();
        $("#lblConfirmPassword").hide();

        var varReturnValue = true, varFocus = false;

        
//        if($("#old_password").val().trim() == '')
//        {
//            $("#lblOldPassword").show();
//            if (!varFocus)
//            {
//                $("#old_password").focus();
//                varFocus=true;
//            }
//            varReturnValue=false;
//        }

        if($("#newpassword").val().trim() == '')
        {
            $("#lblNewPassword").show();
            if (!varFocus)
            {
                $("#newpassword").focus();
                varFocus=true;
            }
            varReturnValue=false;
        }

        if($("#confirmpassword").val().trim() == '')
        {
            $("#lblConfirmPassword").show();
            if (!varFocus)
            {
                $("#confirmpassword").focus();
                varFocus=true;
            }
            varReturnValue=false;
        }

        if (!varReturnValue) return;

        $.ajax({
            url: base_url + "UsersController/updateUserPassword",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (result)
            {               
                var varResult = JSON.parse(result);
                swal({
                        title: "Password Updated successfully",
                        text: varResult.msg_info,
                        confirmButtonColor: "#5cb85c",
                        confirmButtonText: 'OK',
                        closeOnConfirm: true,
                        closeOnCancel: true
                    },
                    function (confirmed) {
                        window.location = '<?php echo base_url('pramaan/logout/');?>';
                    });
            },
            error: function () {
                alert("Error Occured");
            }
        });
    });
  
  
</script>

<form  id="frmUpdatePassword" method="post" enctype="multipart/form-data" class="form-horizontal">
<div class="modal fade text-xs-left" id="modal_update_password" tabindex="-1" role="dialog" aria-labelledby="myModalLabel34" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="myModalLabel34">Update Password</h3>
            </div>
                <div class="modal-body" style="margin-bottom: 5%">
                    <div class="form-group row">
<!--                        <div class="col-md-4">
                            <label for="old_password" class="label">Old Password:</label>
                            <input type="password" class="form-control" id="old_password" placeholder="Enter Old Password" name="old_password"  value="">
                            <label id="lblOldPassword" style="color:red;display: none;">* Please Enter Old Password</label>
                        </div>-->

                        <div class="col-md-6">
                            <label for="newpassword" class="label">New Password:</label>
                            <input type="password" class="form-control" id="newpassword" placeholder="Enter New Password" name="newpassword" onkeyup="checkPass(); return false;" value="" min="8"   maxlength = "16" >
                            <label id="lblNewPassword" style="color:red;display: none;">* Please Enter New Password</label>
                        </div>

                        <div class="col-md-6">
                            <label for="confirmpassword" class="">Confirm Password:</label>
                            <input type="password" class="form-control" id="confirmpassword" placeholder="Enter Confirm Password" name="confirmpassword"  onkeyup="checkPass(); return false;" value="" min="8" maxlength = "16">
                            <label id="lblConfirmPassword" style="color:red;display: none;">* Please Enter Confirm Password</label>
                        </div>
                    </div>                   
                      <div id="error-nwl"></div>
                    <hr>                    
                      <div class="form-group" style="float: right">
                          <button type="submit" class="btn btn-primary" name="button" id="BtnSubmit" disabled>Update Password</button>
                      </div>
                </div>

                
        </div>
    </div>
</div>
    </form>