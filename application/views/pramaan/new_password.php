<style type="text/css">

/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  Reset new Password
 * @date  Nov_2016
*/
p.justify
{
    text-align: justify-all;
}
</style>
<div class="inner">
    <div class="panel panel-default"  style="margin: 5% 25% ">
    <div class="panel-body">   
    <h3>Reset Password</h3>
    <hr>
    <form id="new_password_form"  name="new_password_form" method="post" class="form-horizontal" style="padding-top:10px;">
    <div class="row form-box">
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                <label for="email" class="col-sm-4 control-label">Email Id<span class="validmark">*</span></label>
                <div class="col-sm-7">
                <input type="text" class="form-control" id="email" name="email" placeholder="Your email id" maxlength="100" required>
                <span class="error_label"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="phone" class="col-sm-4 control-label">Old Password<span class="validmark">*</span></label>
                <div class="col-sm-7">
                <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Old Password" maxlength="50" required>
                <span class="error_label"></span>
                </div>
            </div>  
            <div class="form-group">
                <label for="phone" class="col-sm-4 control-label">New Password<span class="validmark">*</span></label>
                <div class="col-sm-7">
                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password" maxlength="50" required>
                <span class="error_label"></span>
                </div>
            </div>
            <div class="form-group">

                <div class="col-sm-offset-2 col-sm-4">
                    <button type="reset" class="btn btn-primary btn-block">Reset</button>
                </div>
                <div class="col-sm-4">
                     <button type="submit" class="btn btn-success btn-block" name="btn_send" id="btn_send">Save</button>
                </div>
            </div>
        </div>
    </div>
    </form>
    </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function()
{
    $("button#btn_send").click(function()
    {
        event.preventDefault();
        var email = $( "#email" ).val();
        var old_password = $( "#old_password" ).val();
        var new_password = $( "#new_password" ).val();
        /* check and make sure the user didn't submit blank values.
        This is where you can add more validation checks which i left open for expantion */
        var storedmobile = sessionStorage.getItem("mobile");

        if(email =="" || old_password ==""|| new_password=="")
        {
            alert("Please enter email and password");
        }
        else
        {
        /* the email & the password values from the index page are assigned to varialbes */
        /* check and make sure the user didn't submit blank values.
        This is where you can add more validation checks which i left open for expantion */
            $.ajax({
             type: "POST",
             url: base_url+"pramaan/save_password",
             data: $('#new_password_form').serialize(),
             dataType:'json',
             success: function (data) 
             {
                var form="#new_password_form";
                if (data.status == true) 
                {
                    alert(data.msg_info);
                    window.location.href = base_url+'pramaan/home';
                }
                else
                {
                    alert(data.msg_info);
                }
             }
            });
            return false; // required to block normal submit since you used ajax
        }
    });

});
</script>