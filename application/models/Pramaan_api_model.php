<?php
/**
 * API :: Policy api model  
 * @author Sangamesh 
**/
class Pramaan_api_model extends CI_Model
{
	/**
	 * Default Constructor
	 *
	 * @return m_common
	 */

	public function __construct()
    {
        parent::__construct();
    }
	

	function get_user_list($franchise_id=0, $search_string='',$pg=0,$limit=10)
	{
		$cond=''; $order_by='';
		if($franchise_id)
	    {
	        $cond=" and a.franchise_id=$franchise_id  ";
	        $order_by=" order by a.created_on desc limit $pg,$limit";
	    }

	    $search_string=mysql_real_escape_string(urldecode(trim($search_string)));
		if($search_string)
		{
			$cond = " and (a.order_id='".$search_string."' or g.mobile_number='".$search_string."' ) ";
			$order_by=" order by a.created_on desc";
		}
		
	    $policy_order_detail_rec=$this->db->query("SELECT count(distinct a.id) as total_rec
			                          from t_policy_details a
			                          left join m_policy_types b on b.id=a.policy_type_id
			                          left join t_personal_details c on c.id=a.policy_holder_id
			                          left join m_premium_slabs d on d.id=a.premium_slab_id
			                          left join m_clients e on e.client_id=c.client_id
			                          left join m_nominee f on f.policy_order_id=a.order_id
			                          left join t_contact_details g on g.policy_order_id=a.order_id
			                          where 1 $cond")or die('<pre>'.mysql_error().'</pre>');

	    $total_records=$policy_order_detail_rec->row()->total_rec;

	    if(!$total_records)
			return array('status'=>'error','error_code'=>2002,'error_msg'=>"No transactions found");

		else
		{
			
			$policy_order_detail_info=$this->db->query("SELECT a.id,ifnull(a.pb_policy_number,'') as pb_policy_number,a.premium_slab_id,a.premium_amount,a.payment_amount,if(a.payment_status=1,'Paid','Unpaid') as payment_status,ifnull(a.payment_ref_no,'') as payment_ref_no,ifnull(a.payment_partner,'') as payment_partner,a.order_id, date_format(a.created_on,'%d-%b-%Y') as created_on,a.appl_form_type,a.appl_form_status,a.appl_filled_type,
                          a.policy_type_id,a.app_form_status,ifnull(a.physical_doc_path,'') as physical_doc_path,ifnull(a.digital_copy_link,'') as digital_copy_link,b.policy_name,concat(c.name_of_insured,' ',ifnull(c.lastname_of_insured,''))  as insured_name, c.name_of_insured as insured_firstname,c.lastname_of_insured as insured_lastname,c.gender as insured_gender,date_format(c.dob,'%d-%b-%Y')as insured_dob,if(c.marital_status=1,'Yes','No') as marital_status,c.father_husband_name,d.coverage_amount,ifnull(g.address1,'') as address1 ,ifnull(g.address2,'') as address2,g.state,g.district,
                          g.city,g.pin,g.mobile_number,c.email_id,ifnull(c.physical_deformity_detail,'') as physical_deformity_detail,ifnull(c.Illness_disease_detail,'') as Illness_disease_detail,if(a.status=0,'pending',if(c.status=1,'Approved','Rejected')) as status_msg,e.name as client_name,
                          ifnull(f.nominee_name,'') as nominee_name,IFNULL(DATE_FORMAT(f.nominee_dob,'%d-%b-%Y'),'') AS nominee_dob, IFNULL(f.nominee_relationship,'') AS nominee_relationship
                          from t_policy_details a
                          left join m_policy_types b on b.id=a.policy_type_id
                          left join t_personal_details c on c.id=a.policy_holder_id
                          left join m_premium_slabs d on d.id=a.premium_slab_id
                          left join m_clients e on e.client_id=c.client_id
                          left join m_nominee f on f.policy_order_id=a.order_id
                          left join t_contact_details g on g.policy_order_id=a.order_id
                          where 1 $cond group by a.id $order_by") or die('<pre>'.mysql_error().'</pre>');

			$policy_data=array();
			$index=0;
			foreach ($policy_order_detail_info->result_array() as $po)
			{
				$dependants_rec=$this->db->query("SELECT a.id,a.name,ifnull(date_format(a.dob,'%d-%b-%Y'), '') as dob,ifnull(a.relationship,'') as relationship,ifnull(a.dependant_gender,'') as dependant_gender
															from t_dependants a
															left join t_policy_details b on b.order_id=a.policy_order_id
															where b.id=? order by a.dob,FIELD(relationship,'husband','wife','father','mother')",$po['id'])or die('<pre>'.mysql_error().'</pre>');
				$dependants_details=$dependants_rec->result_array();
				$dependant_data=array();
				$i=1;
				foreach ($dependants_details as $do) 
				{
					$title1='';
					if(strtolower($do['relationship'])=='wife' or strtolower($do['relationship'])=='husband')
						$title1='Spouse';
					else if($i<=2)
						$title1="Child_1";
					else
						$title1="Child_2";
					$dependants=array('id'=>$do['id'],$title1."_name"=>$do['name'],$title1."_gender"=>$do['dependant_gender'],$title1."_dob"=>$do['dob'], $title1."_relationship"=>$do['relationship']);
					$dependant_data[]=$dependants;
				$i++;
				}

				$policy_data[$index]['policy_details']=$po;
				$policy_data[$index]['dependants_details']=$dependant_data;
			$index++;
			}

			$ttl_res_curr= $policy_order_detail_rec->num_rows();
			
			$pg_count_msg = "Showing ".(1+$pg)." to ".($ttl_res_curr+$pg)." of ".$total_records;
			
			return array('status'=>'success','rdata'=>array('total_records'=>$total_records,'policy_list'=>$policy_data,'pg'=>$pg,'limit'=>$limit,'pg_count_msg'=>$pg_count_msg,'file_path'=>base_url() ));

		}


	}	

	


}
?>