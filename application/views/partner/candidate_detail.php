<script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" type="text/javascript"></script>

<style>

    ul.tabs{
        margin: 0px;
        padding: 0px;
        list-style: none;
    }
    ul.tabs li{
        background: none;
        color: #222;
        display: inline-block;
        padding: 10px 15px;
        cursor: pointer;
    }

    ul.tabs li.current{
        background: #ededed;
        color: #222;
    }

    .tab-content{
        display: none;
        background: #ededed;
        padding: 15px;
    }

    .tab-content.current{
        display: inherit;
    }
   .emp-profile{
        padding: 3%;
        margin-top: 3%;
        margin-bottom: 3%;
        border-radius: 0.5rem;
        background: #fff;
    }
    .profile-img{
        text-align: center;
    }
    .profile-img img{
        width: 50%;
        height: 145px;
        border-radius: 100%;
    }
    .profile-img .file {
        position: relative;
        overflow: hidden;
        margin-top: -20%;
        width: 70%;
        border: none;
        border-radius: 0;
        font-size: 15px;
        background: #212529b8;
    }
    .profile-img .file input {
        position: absolute;
        opacity: 0;
        right: 0;
        top: 0;
    }
    .profile-head h5{
        color: #333;
    }
    .profile-head h6{
        color: #0062cc;
    }
    .profile-edit-btn{
        border: none;
        border-radius: 1.5rem;
        width: 70%;
        padding: 2%;
        font-weight: 600;
        color: #6c757d;
        cursor: pointer;
    }
    .proile-rating{
        font-size: 12px;
        color: #818182;
        margin-top: 5%;
    }
    .proile-rating span{
        color: #495057;
        font-size: 13px;
       /* font-weight: 600;*/
    }
    .profile-head .nav-tabs{
        margin-bottom:5%;
    }
    .profile-head .nav-tabs .nav-link{
        font-weight:600;
        border: none;
    }
    .profile-head .nav-tabs .nav-link.active{
        border: none;
        border-bottom:2px solid #0062cc;
    }
    .profile-work{
        padding: 10%;
        margin-top: -15%;
    }
    .profile-work p{
        font-size: 12px;
        color: #818182;
        font-weight: 600;
        margin-top: 10%;
    }
    .profile-work a{
        text-decoration: none;
        color: #495057;
        font-weight: 600;
        font-size: 14px;
    }
    .profile-work ul{
        list-style: none;
    }
    .profile-tab label{
        font-weight: 600;
    }
    .profile-tab p{
        font-weight: 600;
        color: #0062cc;
    }
</style>
<div class="content-body">
    <div class=" breadcrumbs-top col-md-8 col-xs-12">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("partner/candidates","Candidate List");?></a>
                </li>
                <li class="breadcrumb-item active">Candidate Profile
                </li>
            </ol>
        </div>
    </div>
    <section id="description" class="card" style="border: hidden;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-sm-3">
                        <div class="page_display_log pull-left" style=" color: green"></div>
                    </div>
                    <div class="card-block">

                        <div class="container emp-profile">
                            <form method="post">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="profile-img">
                                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS52y5aInsxSm31CvHOFHWujqUx_wWTS9iM6s7BAm21oEN_RiGoog" alt=""/>

                                        </div>
                                        <div class="profile-work">
                                            <p>Cloud Engineer with 8+ years of experience in DevOps Environment, Amazon Web Services (AWS), Azure and VMware.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="profile-head">
                                                <h3><b>Kshiti Ghelani</b></h3>
                                            <h6>Web Developer and Designer</h6>
                                            <p class="proile-rating"><i class="fa fa-star"></i> <span>8/10</span></p>
                                            <p class="proile-rating"><i class="fa fa-map-marker"></i> <span>Hyderabad / Secunderabad</span></p>
                                            <p class="proile-rating"><i class="fa fa-envelope"></i> <span>Kshiti.Ghelani0502@gmail.com</span></p>
                                            <p class="proile-rating"><i class="fa fa-phone"></i> <span>+919854681270</span></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <p class="proile-rating"><i class="fa fa-wrench"></i> <span>8 Years</span></p>
                                        <p class="proile-rating"><i class="fa fa-inr"></i> <span>6.50( Lac)</span></p>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="submit" class="btn btn-success btn-min-width mr-1 mb-1" name="btnAddMore" value="Edit Profile"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <!--<div class="col-md-4">
                                        <div class="profile-work">

                                        </div>
                                    </div>
                                    <br>-->
                                    <div class="col-md-12">
                                        <div class="container">
                                            <ul class="tabs">
                                                <li class="tab-link current" data-tab="tab-1">Education</li>
                                                <li class="tab-link" data-tab="tab-2">Employer</li>
                                                <li class="tab-link" data-tab="tab-3">Skill</li>
                                                <li class="tab-link" data-tab="tab-4">QP</li>
                                            </ul>

                                            <div id="tab-1" class="tab-content current">
                                                Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                            </div>
                                            <div id="tab-2" class="tab-content">
                                                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                            </div>
                                            <div id="tab-3" class="tab-content">
                                                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                                            </div>
                                            <div id="tab-4" class="tab-content">
                                                Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                            </div>

                                        </div><!-- container -->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function(){

        $('ul.tabs li').click(function(){
            var tab_id = $(this).attr('data-tab');

            $('ul.tabs li').removeClass('current');
            $('.tab-content').removeClass('current');

            $(this).addClass('current');
            $("#"+tab_id).addClass('current');
        })

    })

</script>






