<div class="content-body" style="padding: 30px;">
<div class="row">
    <?php $this->load->view('documents/list', $data); ?>
<div class="col-md-12">
  <h3>Edit Skill</h3>
    <div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;margin-left: -25px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("candidatescontroller/show/".$data['fields']['candidate_id'],"Candidate Profile");?></li>
                <li class="breadcrumb-item active">Edit Skill</li>
            </ol>
        </div>
    </div>
</div>
</div>
<br>

<div id="Personal" class="w3-container info" style="background: white; padding: 25px; ">
<form action="<?php echo base_url('/documentscontroller/update/').$data['fields']['candidate_id'].'/'.$id;?>" method="POST" enctype="multipart/form-data">

<?php $this->load->view('documents/form_fields', $data); ?>

</form>

</div>
</div>

<br><br><br>
