<script>
function selectspocmodal(company_id)
{
  var track_url=base_url+'partner/companySpocDetails/'+<?= ($fields['company_id']=='')? $company['id'] : $fields['company_id'] ?>;
  $.ajax({
        url : track_url,
        type: "GET",
        dataType: "JSON",        
        success: function(data)
        {
            var customer_detail_html='';
            if(data.status)
            {
                var varInputCount =  $('input[name*=spoc_phone').length;

                var employer=data.employer_detail;
                var slno=1;
                customer_detail_html += "<div  style='margin-bottom: 10px'>Company Name: <span style='font-weight: bold;'>"+employer.company_name+"</span></div>"; 
                
                customer_detail_html += '<div class="row">';
                customer_detail_html += '<div class="col-sm-12 col-md-12" style="overflow-x: auto; ">';
                customer_detail_html += '<table id="tblApplicationTrackerDetails" class="table table-bordered display responsive nowrap">';
                customer_detail_html += '<thead>';
                customer_detail_html += '<tr><th></th><th>Spoc Name</th><th>Spoc Email</th><th>Spoc Phone</th><th>Spoc Designation</th></tr>';
                customer_detail_html += '<tbody id="tbodySpocs">';
                customer_detail_html += '</thead>';
                $.each(data.customer_detail,function(a,b)
                {
                    let id="checkbox_"+slno;
                    var varCheckBox = '<input id='+id+ ' class="checkevent" type="checkbox" name="spoc">';
                    var varDuplicateStatus = false;

                    if (GetSpocSelectedStatus(b.spoc_phone))
                    {
                        varCheckBox = '';
                    }

                    /*for(var i=0; i<varInputCount; i++)
                    {
                        if (b.spoc_phone == $('input[name="spoc_detail['+i+'][spoc_phone]"]').val())
                        {
                            varCheckBox = '';
                            break;
                        }                        
                    }*/
                  
                    customer_detail_html += '<tr id="trSpocs"><td class="spocchecktd">'+varCheckBox+'</td><td class="spocnametd">'+b.spoc_name+'</td><td class="spocemailtd">'+b.spoc_email+'</td><td class="spocphonetd">'+b.spoc_phone+'</td><td class="spocdesignationtd">'+b.spoc_designation+'</td></tr>';
                    slno++;
                });
                //<td><a class="btn btn-success mr-1 mb-1" onclick="ShowOpportunityDetails('+b.id+')">'+b.opportunity_count+'</a></td>
                customer_detail_html += '</tbody>';
                customer_detail_html += '</table>';
                customer_detail_html += '</div></div>'; 
            }
            $('.candidate_job_status').html(customer_detail_html);

            $("#tblCustomerDetails").DataTable();
            
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
    $('#selectSpocModal').modal('show'); // show bootstrap modal when complete loaded
}

function GetSpocSelectedStatus(varSpocPhone)
{
    var varInputs =  $('input[name*=spoc_phone');
    if(varInputs.length > 0)
    {
        for(var i=0; i<varInputs.length; i++)
        {
            if ($(varInputs[i]).val().trim() == varSpocPhone.trim())
            {
                return true;
            }
        }
    }

    return false;
}



function btnSelect_OnClick()
{   
    //let x =  $('#spoc-field-container').children().length;
    var varInputs =  $('input[name*=spoc_phone');        
    var varInputCount =  varInputs.length;
    if (varInputCount > 0) varInputCount--;
    var x =varInputCount;

    var varSpocArray = GetSpocArray(); 
    for(let i=0;i<varSpocArray.length;i++)
    {
        let spoc_json=varSpocArray[i];

        var varIndex = '';
        var varMaxNumber = 0;
        var varFilledInExisting = false;

        if (varInputs.length > 0)
        {            
            for(var j=0; j<varInputs.length; j++)
            {
                //alert(varInputs[j].name);

                var varIndex = ExtractNumberFromText(varInputs[j].name);
                if (varIndex != '')
                {
                    if (varMaxNumber < parseInt(varIndex)) varMaxNumber=parseInt(varIndex);                    

                    var varChkSpocPhones = document.getElementsByName('spoc_detail['+varIndex+'][spoc_phone]');
                    if (varChkSpocPhones.length > 0)
                    {
                        var varChkSpocNames = document.getElementsByName('spoc_detail['+varIndex+'][spoc_name]');
                        var varChkSpocEmails = document.getElementsByName('spoc_detail['+varIndex+'][spoc_email]');
                        var varChkSpocDesigs = document.getElementsByName('spoc_detail['+varIndex+'][spoc_designation]');
                        
                        //alert(varIndex);
                        if ($(varChkSpocNames[0]).val().trim()=='' 
                            && $(varChkSpocEmails[0]).val().trim()=='' 
                            && $(varChkSpocPhones[0]).val().trim()=='' 
                            && $(varChkSpocNames[0]).val().trim()=='')
                            {
                                $(varChkSpocNames[0]).val(varSpocArray[i].SpocName);
                                $(varChkSpocEmails[0]).val(varSpocArray[i].SpocEmail);
                                $(varChkSpocPhones[0]).val(varSpocArray[i].SpocPhone);
                                $(varChkSpocDesigs[0]).val(varSpocArray[i].SpocDesignation);
                                varFilledInExisting = true;
                                break;
                            }
                    }
                }                
            }

            if (!varFilledInExisting)
            {
                varMaxNumber++;
                fieldSET = '<div class="form-group row" id="spoc_'+varMaxNumber+'">';
                fieldSET += '<div class="col-xs-3">\
                                <div class="input-group">\
                                    <input type="text" class="form-control" name="spoc_detail['+varMaxNumber+'][spoc_name]" value="'+varSpocArray[i].SpocName+'" placeholder="Enter Spoc Name" readonly/>\
                                </div>\
                        </div>\
                                            <div class="col-xs-3">\
                                <div class="input-group">\
                                    <input type="email" class="form-control" name="spoc_detail['+varMaxNumber+'][spoc_email]" value="'+varSpocArray[i].SpocEmail+'" placeholder="Enter Spoc Email" readonly/>\
                                </div>\
                        </div>\
                                            <div class="col-xs-2">\
                                <div class="input-group">\
                                    <input type="text" maxlength="10" class="form-control" name="spoc_detail['+varMaxNumber+'][spoc_phone]" value="'+varSpocArray[i].SpocPhone+'" placeholder="Enter Spoc Phone" readonly/>\
                                </div>\
                        </div>\
                    <div class="col-xs-3">\
                        <div class="input-group">\
                            <input type="text" maxlength="30" class="form-control" name="spoc_detail['+varMaxNumber+'][spoc_designation]" value="'+varSpocArray[i].SpocDesignation+'" placeholder="Spoc Designation" readonly/>\
                        </div>\
                        </div>\
                <div class="col-xs-1">\
                        <span class="input-group-btn"><button class="btn btn-danger remove_div" data-value='+varMaxNumber+' type="button"><i class="fa fa-trash"></i></button></span>\
                        </div>\
                </div>';

                $('#spoc-field-container').append(fieldSET); // Add field html
            }
        }
        else
        {
            for(var j=0; j<100;j++)
            {
                var varChkSpocPhones = document.getElementsByName('spoc_detail['+j+'][spoc_phone]');
                if (varChkSpocPhones.length < 1)
                {
                    fieldSET = '<div class="form-group row" id="spoc_'+j+'">';
                    fieldSET += '<div class="col-xs-3">\
                                    <div class="input-group">\
                                        <input type="text" class="form-control" name="spoc_detail['+j+'][spoc_name]" value="'+varSpocArray[i].SpocName+'" placeholder="Enter Spoc Name" readonly/>\
                                    </div>\
                            </div>\
                                                <div class="col-xs-3">\
                                    <div class="input-group">\
                                        <input type="email" class="form-control" name="spoc_detail['+j+'][spoc_email]" value="'+varSpocArray[i].SpocEmail+'" placeholder="Enter Spoc Email" readonly/>\
                                    </div>\
                            </div>\
                                                <div class="col-xs-2">\
                                    <div class="input-group">\
                                        <input type="text" maxlength="10" class="form-control" name="spoc_detail['+j+'][spoc_phone]" value="'+varSpocArray[i].SpocPhone+'" placeholder="Enter Spoc Phone" readonly/>\
                                    </div>\
                            </div>\
                        <div class="col-xs-3">\
                            <div class="input-group">\
                                <input type="text" maxlength="30" class="form-control" name="spoc_detail['+j+'][spoc_designation]" value="'+varSpocArray[i].SpocDesignation+'" placeholder="Spoc Designation" readonly/>\
                            </div>\
                            </div>\
                    <div class="col-xs-1">\
                            <span class="input-group-btn"><button class="btn btn-danger remove_div" data-value='+j+' type="button"><i class="fa fa-trash"></i></button></span>\
                            </div>\
                    </div>';

                    $('#spoc-field-container').append(fieldSET); // Add field html
                    break;
                }
            }
        }
        





        /*
         //console.log(spoc_json.SpocName);
         for(var j=0; j<100;j++)
         {
            var varChkSpocNames = document.getElementsByName('spoc_detail['+j+'][spoc_name]');
            var varChkSpocEmails = document.getElementsByName('spoc_detail['+j+'][spoc_email]');
            var varChkSpocPhones = document.getElementsByName('spoc_detail['+j+'][spoc_phone]');
            var varChkSpocDesigs = document.getElementsByName('spoc_detail['+j+'][spoc_designation]');

            if (varChkSpocNames.length > 0)
            {
                if ($(varChkSpocNames[0]).val().trim()=='' 
                    && $(varChkSpocEmails[0]).val().trim()=='' 
                    && $(varChkSpocPhones[0]).val().trim()=='' 
                    && $(varChkSpocNames[0]).val().trim()=='')
                    {
                        $(varChkSpocNames[0]).val(varSpocArray[i].SpocName);
                        $(varChkSpocEmails[0]).val(varSpocArray[i].SpocEmail);
                        $(varChkSpocPhones[0]).val(varSpocArray[i].SpocPhone);
                        $(varChkSpocDesigs[0]).val(varSpocArray[i].SpocDesignation);
                        break;
                    }
            }
            else
            {
                
            }
         }

         */

        /*var varChkInputs = document.getElementsByName('spoc_detail['+x+'][spoc_name]');
        if (varChkInputs.length > 0)
        {
            $(varChkInputs[0]).val(varSpocArray[i].SpocName);
            $('input[name="spoc_detail['+x+'][spoc_email]"]').val(varSpocArray[i].SpocEmail);
            $('input[name="spoc_detail['+x+'][spoc_phone]"]').val(varSpocArray[i].SpocPhone);
            $('input[name="spoc_detail['+x+'][spoc_designation]"]').val(varSpocArray[i].SpocDesignation);
        }
        else
        {                
            fieldSET = '<div class="form-group row" id="spoc_'+x+'">';
		    fieldSET += '<div class="col-xs-3">\
					        <div class="input-group">\
					            <input type="text" class="form-control" name="spoc_detail['+x+'][spoc_name]" value="'+varSpocArray[i].SpocName+'" placeholder="Enter Spoc Name" readonly/>\
					        </div>\
					 </div>\
                                         <div class="col-xs-3">\
					        <div class="input-group">\
					            <input type="email" class="form-control" name="spoc_detail['+x+'][spoc_email]" value="'+varSpocArray[i].SpocEmail+'" placeholder="Enter Spoc Email" readonly/>\
					        </div>\
					 </div>\
                                         <div class="col-xs-2">\
					        <div class="input-group">\
					            <input type="text" maxlength="10" class="form-control" name="spoc_detail['+x+'][spoc_phone]" value="'+varSpocArray[i].SpocPhone+'" placeholder="Enter Spoc Phone" readonly/>\
					        </div>\
					 </div>\
                <div class="col-xs-3">\
                    <div class="input-group">\
                        <input type="text" maxlength="30" class="form-control" name="spoc_detail['+x+'][spoc_designation]" value="'+varSpocArray[i].SpocDesignation+'" placeholder="Spoc Designation" readonly/>\
                    </div>\
                    </div>\
            <div class="col-xs-1">\
                    <span class="input-group-btn"><button class="btn btn-danger remove_div" data-value='+x+' type="button"><i class="fa fa-trash"></i></button></span>\
                    </div>\
            </div>';

            $('#spoc-field-container').append(fieldSET); // Add field html
        }

           x++; */
      
    }
     
    //alert(JSON.stringify(varSpocArray));
}

function ExtractNumberFromText(varText) {
    var matches = varText.match(/(\d+)/); 
        
    if (matches) {
        return matches[0];
    } 

    return '';
} 

function GetSpocArray()
{
    var varSpocArray = [];
    var varColumnS = ["Select", "SpocName", "SpocEmail", "SpocPhone", "SpocDesignation"]
    var varTBody = document.getElementById('tbodySpocs');
    var varTrList = varTBody.getElementsByTagName('tr');
    for(var i=0; i < varTrList.length; i++)
    {
        var varTdList = varTrList[i].getElementsByTagName('td');
        var varChkList = varTrList[i].getElementsByTagName('input');

        if (varChkList != undefined && varChkList.length > 0)
        {
            if (varChkList[0].checked)
            {
                var varSpoc = {};
                for(var j=1; j < varTdList.length; j++)
                {        
                    varSpoc[varColumnS[j]] = varTdList[j].innerText;
                }
                varSpocArray.push(varSpoc);
            }
        }        
    }

    return varSpocArray;
}

</script>




<div id="selectSpocModal" class="modal fade bs-example-modal-xl" role="dialog" style="color: black;">
    <div class="modal-dialog modal-xl" role="document" >
        <div class="modal-content">
            <div class="modal-header" style="border-bottom:hidden;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Company Spoc List</h3>
            </div>
            <div class="modal-body candidate_job_status">
                -No records found-
            </div>
           
            <div class="modal-footer">
                 <button type="button" class="btn btn-primary" onclick="btnSelect_OnClick();" data-dismiss="modal">Select</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
            </div>
        </div>
    </div>
</div>