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
                    for(var i=1; i<varInputCount; i++)
                    {
                        if (b.spoc_phone == $('input[name="spoc_detail['+i+'][spoc_phone]"]').val())
                        {
                            varCheckBox = '';
                            break;
                        }                        
                    }
                  
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




function btnSelect_OnClick()
{   
    //let x =  $('#spoc-field-container').children().length;
    var varInputCount =  $('input[name*=spoc_phone').length;
    var x =varInputCount;
    var varSpocArray = GetSpocArray(); 
    for(let i=0;i<varSpocArray.length;i++)
     {
         let spoc_json=varSpocArray[i];
         //console.log(spoc_json.SpocName);
                
        fieldSET = '<div class="form-group row" id="spoc_'+x+'">';
		fieldSET +=           '<div class="col-xs-3">\
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
         x++;    
      
    }
     
    //alert(JSON.stringify(varSpocArray));
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
                 <button type="button" class="btn btn-primary" onclick="btnSelect_OnClick();removediv();" data-dismiss="modal">Select</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
            </div>
        </div>
    </div>
</div>