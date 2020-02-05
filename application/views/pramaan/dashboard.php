<?php
	setlocale(LC_MONETARY, 'en_IN');
	$user_group_id = $this->session->userdata('usr_authdet')['user_group_id'];
?>

<div class="content-body" style="overflow-x: hidden !important;"><!-- CRM stats -->
	<div class="row">
		<!-- fitness target -->

		<div class="col-xs-12" style="width:99%;">
			<?php $this->load->view('layouts/errors');?>
			<div class="card">
				<div class="card-body">
					<div class="row">
					<div class="col-xl-2 col-lg-6 col-md-12 border-right-blue-grey border-right-lighten-5">
							<div class="my-1 text-xs-center">
								<div class="card-header mb-2 pt-0"style="border-bottom: none;">
									<span class="deep-orange" style="font-weight: bolder; font-size: 16px;">Companies</span>
									<h3 class="font-large-2 text-bold-200"><a href="<?= (in_array($user_group_id, customer_view_roles())) ? base_url('companiescontroller/index') : '#';?>"><?php echo number_format($total_companies); ?> </a></h3>
								</div>
								<div class="card-body">
									<!-- <input type="text" value="70" class="knob hide-value responsive angle-offset" data-angleOffset="0" data-thickness=".15" data-linecap="round" data-width="130" data-height="130" data-inputColor="#e1e1e1" data-readOnly="true" data-fgColor="#FF5722" data-knob-icon="icon-users">
									<ul class="list-inline clearfix mt-1 mb-0">
										<li class="border-right-grey border-right-lighten-2 pr-2">
											<h2 class="grey darken-1 text-bold-400">60%</h2>
											<span class="success">Completed</span>
										</li>
										<li class="pl-2">
											<h2 class="grey darken-1 text-bold-400">40%</h2>
											<span class="danger">Remaining</span>
										</li>
									</ul> -->
								</div>
							</div>
						</div>
						<div class="col-xl-2 col-lg-6 col-md-12 border-right-blue-grey border-right-lighten-5">
							<div class="my-1 text-xs-center">
								<div class="card-header mb-2 pt-0" style="border-bottom: none;">
									<span class="success" style="font-weight: bolder; font-size: 16px;">Opportunities</span>
									<h3 class="font-large-2 text-bold-200"><a href="<?= (in_array($user_group_id, lead_view_roles())) ? base_url('opportunitiescontroller/index') : '#' ;?>"> <?php echo $total_opportunities; ?></a></h3>
								</div>
								<div class="card-body">
									<!-- <input type="text" value="81" class="knob hide-value responsive angle-offset" data-angleOffset="20" data-thickness=".15" data-linecap="round" data-width="130" data-height="130" data-inputColor="#e1e1e1" data-readOnly="true" data-fgColor="#009688" data-knob-icon="icon-search6">
									<ul class="list-inline clearfix mt-1 mb-0">
										<li class="border-right-grey border-right-lighten-2 pr-2">
											<h2 class="grey darken-1 text-bold-400">85%</h2>
											<span class="success">Completed</span>
										</li>
										<li class="pl-2">
											<h2 class="grey darken-1 text-bold-400">15%</h2>
											<span class="danger">Remaining</span>
										</li>
									</ul> -->
								</div>
							</div>
						</div>

						<div class="col-xl-2 col-lg-6 col-md-12 border-right-blue-grey border-right-lighten-5">
							<div class="my-1 text-xs-center">
								<div class="card-header mb-2 pt-0" style="border-bottom: none;">
									<span class="success" style="font-weight: bolder; font-size: 16px;">Contracts</span>
									<h3 class="font-large-2 text-bold-200"><a href="<?= (in_array($user_group_id, lead_view_roles())) ? base_url('pramaan/contracts') : '#' ;?>"> <?php echo $total_contracts; ?></a></h3>
								</div>
								<div class="card-body">
									<!-- <input type="text" value="81" class="knob hide-value responsive angle-offset" data-angleOffset="20" data-thickness=".15" data-linecap="round" data-width="130" data-height="130" data-inputColor="#e1e1e1" data-readOnly="true" data-fgColor="#009688" data-knob-icon="icon-search6">
									<ul class="list-inline clearfix mt-1 mb-0">
										<li class="border-right-grey border-right-lighten-2 pr-2">
											<h2 class="grey darken-1 text-bold-400">85%</h2>
											<span class="success">Completed</span>
										</li>
										<li class="pl-2">
											<h2 class="grey darken-1 text-bold-400">15%</h2>
											<span class="danger">Remaining</span>
										</li>
									</ul> -->
								</div>
							</div>
						</div>

						<div class="col-xl-2 col-lg-6 col-md-12 border-right-blue-grey border-right-lighten-5">
							<div class="my-1 text-xs-center">
								<div class="card-header mb-2 pt-0" style="border-bottom: none;">
									<span class="info" style="font-weight: bolder; font-size: 16px;">Jobs</span>
									<h3 class="font-large-2 text-bold-200"><a href="<?= (in_array($user_group_id, job_view_roles())) ? base_url('/pramaan/pramaan_jobs') : '#';?>"> <?php echo number_format($total_jobs); ?></a></h3>
								</div>
								<!-- <div class="card-body">
									<input type="text" value="65" class="knob hide-value responsive angle-offset" data-angleOffset="40" data-thickness=".15" data-linecap="round" data-width="130" data-height="130" data-inputColor="#e1e1e1" data-readOnly="true" data-fgColor="#00BCD4" data-knob-icon="icon-user5">
									<ul class="list-inline clearfix mt-1 mb-0">
										<li class="border-right-grey border-right-lighten-2 pr-2">
											<h2 class="grey darken-1 text-bold-400">65%</h2>
											<span class="success">Completed</span>
										</li>
										<li class="pl-2">
											<h2 class="grey darken-1 text-bold-400">35%</h2>
											<span class="danger">Remaining</span>
										</li>
									</ul>
								</div> -->
							</div>
						</div>

						<div class="col-xl-2 col-lg-6 col-md-12 border-right-blue-grey border-right-lighten-5">
							<div class="my-1 text-xs-center">
								<div class="card-header mb-2 pt-0" style="border-bottom: none;">
									<span class="warning" style="font-weight: bolder; font-size: 16px;">Candidates</span>
									<h3 class="font-large-2 text-bold-200" style="margin-left: -15px;"><a href="<?= (in_array($user_group_id, candidate_view_roles())) ? base_url('/partner/candidates') : '#'; ?>"> <?php echo $total_candidates; ?></a></h3>
								</div>
								<div class="card-body">
									<!-- <input type="text" value="81" class="knob hide-value responsive angle-offset" data-angleOffset="20" data-thickness=".15" data-linecap="round" data-width="130" data-height="130" data-inputColor="#e1e1e1" data-readOnly="true" data-fgColor="#009688" data-knob-icon="icon-search6">
									<ul class="list-inline clearfix mt-1 mb-0">
										<li class="border-right-grey border-right-lighten-2 pr-2">
											<h2 class="grey darken-1 text-bold-400">85%</h2>
											<span class="success">Completed</span>
										</li>
										<li class="pl-2">
											<h2 class="grey darken-1 text-bold-400">15%</h2>
											<span class="danger">Remaining</span>
										</li>
									</ul> -->
								</div>
							</div>
						</div>

                        <div class="col-xl-2 col-lg-6 col-md-12 border-right-blue-grey border-right-lighten-5">
							<div class="my-1 text-xs-center">
								<div class="card-header mb-2 pt-0" style="border-bottom: none;">
									<span class="info" style="font-weight: bolder; font-size: 16px;">Vacancies</span>
										<h3 class="font-large-2 text-bold-200"><a href="<?= (in_array($user_group_id, job_view_roles())) ? base_url('/pramaan/pramaan_jobs') : '#';?>"> <?php echo ($total_openings); ?></a></h3>
								</div>
								<!-- <div class="card-body">
									<input type="text" value="65" class="knob hide-value responsive angle-offset" data-angleOffset="40" data-thickness=".15" data-linecap="round" data-width="130" data-height="130" data-inputColor="#e1e1e1" data-readOnly="true" data-fgColor="#00BCD4" data-knob-icon="icon-user5">
									<ul class="list-inline clearfix mt-1 mb-0">
										<li class="border-right-grey border-right-lighten-2 pr-2">
											<h2 class="grey darken-1 text-bold-400">65%</h2>
											<span class="success">Completed</span>
										</li>
										<li class="pl-2">
											<h2 class="grey darken-1 text-bold-400">35%</h2>
											<span class="danger">Remaining</span>
										</li>
									</ul>
								</div> -->
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>



		<div class="row">
			<div class="col-xs-12">
				<div class="">
					<div class="card-body">
						<div class="card-block">
							<div class="media">
				<h3> <strong>Candidate Application Statuses</strong> </h3>
			</div>
		</div>
		</div>
		</div>
		</div>
		</div>


			<div class="row" style="width:99.9%;">
				<div class="col-xl-3 col-lg-6 col-xs-12">
					<div class="card">
						<div class="card-body">
							<div class="card-block">
								<div class="media">
									<div class="media-left media-middle">
										<i class="icon-forward cyan font-large-2 float-xs-left"></i>
									</div>
									<div class="media-body text-xs-right">
										<h3><?= $interested_candidates; ?></h3>
										<span>Interested</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-lg-6 col-xs-12">
					<div class="card">
						<div class="card-body">
							<div class="card-block">
								<div class="media">
									<div class="media-left media-middle">
										<i class="icon-eye deep-orange font-large-2 float-xs-left"></i>
									</div>
									<div class="media-body text-xs-right">
										<h3><?= $pending_candidates; ?></h3>
										<span>Pending Review</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-lg-6 col-xs-12">
					<div class="card">
						<div class="card-body">
							<div class="card-block">
								<div class="media">
									<div class="media-left media-middle">
										<i class="icon-calendar teal font-large-2 float-xs-left"></i>
									</div>
									<div class="media-body text-xs-right">
										<h3><?= $profiled_candidates; ?></h3>
										<span>Profiled</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-lg-6 col-xs-12">
					<div class="card">
						<div class="card-body">
							<div class="card-block">
								<div class="media">
									<div class="media-left media-middle">
										<i class="icon-android-star pink font-large-2 float-xs-left"></i>
									</div>
									<div class="media-body text-xs-right">
										<h3><?= $interview_candidates; ?></h3>
										<span>Schedule Interview</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<div class="row" style="width:99.9%;">
			<div class="col-xl-3 col-lg-6 col-xs-12">
				<div class="card">
					<div class="card-body">
						<div class="card-block">
							<div class="media">
								<div class="media-left media-middle">
									<i class="icon-flash pink font-large-2 float-xs-left"></i>
								</div>
								<div class="media-body text-xs-right">
									<h3><?= $selected_candidates; ?></h3>
									<span>Selected</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-lg-6 col-xs-12">
				<div class="card">
					<div class="card-body">
						<div class="card-block">
							<div class="media">
								<div class="media-left media-middle">
									<i class="icon-happy cyan font-large-2 float-xs-left"></i>
								</div>
								<div class="media-body text-xs-right">
									<h3><?= $offered_candidates; ?></h3>
									<span>Offered</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-lg-6 col-xs-12">
				<div class="card">
					<div class="card-body">
						<div class="card-block">
							<div class="media">
								<div class="media-left media-middle">
									<i class="icon-ios-pricetags deep-orange font-large-2 float-xs-left"></i>
								</div>
								<div class="media-body text-xs-right">
									<h3><?= $joined_candidates; ?></h3>
									<span>Joined</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-lg-6 col-xs-12">
				<div class="card">
					<div class="card-body">
						<div class="card-block">
							<div class="media">
								<div class="media-left media-middle">
									<i class="icon-thumbsdown teal font-large-2 float-xs-left"></i>
								</div>
								<div class="media-body text-xs-right">
									<h3><?= $not_joined_candidates; ?></h3>
									<span>Not Joined</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

<div class="row">
	<div class="col-xs-12">
		<div class="">
			<div class="card-body">
				<div class="card-block">
					<div class="media">
		<h3> <strong>Job Statuses</strong> </h3>
	</div>
</div>
</div>
</div>
</div>
</div>

	<div class="row" style="width:99.9%;">
		<div class="col-xl-3 col-lg-6 col-xs-12">
			<div class="card">
				<div class="card-body">
					<div class="card-block">
						<div class="media">
							<div class="media-left media-middle">
								<i class="fa fa-hand-stop-o danger font-large-2 float-xs-left"></i>
							</div>
							<div class="media-body text-xs-right">
								<h3><?= $on_hold_jobs; ?></h3>
								<span>Jobs on Hold</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-lg-6 col-xs-12">
			<div class="card">
				<div class="card-body">
					<div class="card-block">
						<div class="media">
							<div class="media-left media-middle">
								<i class="fa fa-times-circle red font-large-2 float-xs-left"></i>
							</div>
							<div class="media-body text-xs-right">
								<h3><?= $closed_jobs; ?></h3>
								<span>Closed Jobs</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-lg-6 col-xs-12">
			<div class="card">
				<div class="card-body">
					<div class="card-block">
						<div class="media">
							<div class="media-left media-middle">
								<i class="icon-ios-pricetags deep-orange font-large-2 float-xs-left"></i>
							</div>
							<div class="media-body text-xs-right">
								<h3><?= $open_jobs; ?></h3>
								<span>Open Jobs</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-lg-6 col-xs-12">
			<div class="card">
				<div class="card-body">
					<div class="card-block">
						<div class="media">
							<div class="media-left media-middle">
								<i class="icon-folder-o teal font-large-2 float-xs-left"></i>
							</div>
							<div class="media-body text-xs-right">
								<h3><?= $drafted_jobs; ?></h3>
								<span>Jobs in Draft</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



	<div class="row">
		<div class="col-xs-12">
			<div class="">
				<div class="card-body">
					<div class="card-block">
						<div class="media">
								<h3> <strong>Opportunity Statuses</strong> </h3>
						</div>
				</div>
			</div>
			</div>
		</div>
	</div>

		<div class="row" style="width:99.9%;">
			<div class="col-xl-3 col-lg-6 col-xs-12">
				<div class="card">
					<div class="card-body">
						<div class="card-block">
							<div class="media">
								<div class="media-left media-middle">
									<i class="fa fa-user warning font-large-2 float-xs-left"></i>
								</div>
								<div class="media-body text-xs-right">
									<h3><?= $lead_identified; ?></h3>
									<span>Opportunity Identified</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-lg-6 col-xs-12">
				<div class="card">
					<div class="card-body">
						<div class="card-block">
							<div class="media">
								<div class="media-left media-middle">
									<i class="fa fa-handshake-o red font-large-2 float-xs-left"></i>
								</div>
								<div class="media-body text-xs-right">
									<h3><?= $initial_meeting_schedule; ?></h3>
									<span>Client Met</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
                    <div class="col-xl-3 col-lg-6 col-xs-12">
						<div class="card">
							<div class="card-body">
								<div class="card-block">
									<div class="media">
										<div class="media-left media-middle">
											<i class="icon-flash pink font-large-2 float-xs-left"></i>
										</div>
										<div class="media-body text-xs-right">
											<h3><?= $follow_up_meeting_schedual; ?></h3>
											<span>Client Follow up Meeting</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
                    <div class="col-xl-3 col-lg-6 col-xs-12">
						<div class="card">
							<div class="card-body">
								<div class="card-block">
									<div class="media">
										<div class="media-left media-middle">
											<i class="fa fa-share-alt green font-large-2 float-xs-left"></i>
										</div>
										<div class="media-body text-xs-right">
											<h3><?= $proposal_shared; ?></h3>
											<span>Clients Proposal Status</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
<!--			<div class="col-xl-3 col-lg-6 col-xs-12">
				<div class="card">
					<div class="card-body">
						<div class="card-block">
							<div class="media">
								<div class="media-left media-middle">
									<i class="fa fa-calendar-check-o primary font-large-2 float-xs-left"></i>
								</div>
								<div class="media-body text-xs-right">
									<h3><?//= $initial_meeting_completed; ?></h3>
									<span>Intital Meeting Completed</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>-->
<!--			<div class="col-xl-3 col-lg-6 col-xs-12">
				<div class="card">
					<div class="card-body">
						<div class="card-block">
							<div class="media">
								<div class="media-left media-middle">
									<i class="icon-thumbsdown teal font-large-2 float-xs-left"></i>
								</div>
								<div class="media-body text-xs-right">
									<h3><?//= $op_lost_at_entry_level; ?></h3>
									<span>Opp. Lost at Entry Level</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>-->
		</div>


				<div class="row" style="width:99.9%;">

<!--					<div class="col-xl-3 col-lg-6 col-xs-12">
						<div class="card">
							<div class="card-body">
								<div class="card-block">
									<div class="media">
										<div class="media-left media-middle">
											<i class="fa fa-check-square cyan font-large-2 float-xs-left"></i>
										</div>
										<div class="media-body text-xs-right">
											<h3><?//= $follow_up_meeting_completed; ?></h3>
											<span>Client Follow up Meeting Completed</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>-->
<!--					<div class="col-xl-3 col-lg-6 col-xs-12">
						<div class="card">
							<div class="card-body">
								<div class="card-block">
									<div class="media">
										<div class="media-left media-middle">
											<i class="icon-ios-pricetags deep-orange font-large-2 float-xs-left"></i>
										</div>
										<div class="media-body text-xs-right">
											<h3><?//= $op_lost_at_follow_up_level; ?></h3>
											<span>Opp. Lost at Followup Level</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>-->

				</div>



								<div class="row" style="width:99.9%;">
<!--									<div class="col-xl-3 col-lg-6 col-xs-12">
										<div class="card">
											<div class="card-body">
												<div class="card-block">
													<div class="media">
														<div class="media-left media-middle">
															<i class="fa fa-eye blue font-large-2 float-xs-left"></i>
														</div>
														<div class="media-body text-xs-right">
															<h3><?//= $proposal_under_review; ?></h3>
															<span>Proposal Under Review</span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>-->
<!--									<div class="col-xl-3 col-lg-6 col-xs-12">
										<div class="card">
											<div class="card-body">
												<div class="card-block">
													<div class="media">
														<div class="media-left media-middle">
															<i class="fa fa-file-o pink font-large-2 float-xs-left"></i>
														</div>
														<div class="media-body text-xs-right">
															<h3><?//= $proposal_under_rfe; ?></h3>
															<span>Proposal Under RFE</span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>-->
									<div class="col-xl-3 col-lg-6 col-xs-12">
										<div class="card">
											<div class="card-body">
												<div class="card-block">
													<div class="media">
														<div class="media-left media-middle">
															<i class="fa fa-gear blue-grey font-large-2 float-xs-left"></i>
														</div>
														<div class="media-body text-xs-right">
															<h3><?= $negotiation_count; ?></h3>
															<span>Clients on Negotiation </span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xl-3 col-lg-6 col-xs-12">
										<div class="card">
											<div class="card-body">
												<div class="card-block">
													<div class="media">
														<div class="media-left media-middle">
															<i class="fa fa-thumbs-up amber font-large-2 float-xs-left"></i>
														</div>
														<div class="media-body text-xs-right">
															<h3><?= $proposal_accepted_count; ?></h3>
															<span>Proposal Accepted </span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
<!--                                                                    <div class="col-xl-3 col-lg-6 col-xs-12">
                                                                                <div class="card">
                                                                                        <div class="card-body">
                                                                                                <div class="card-block">
                                                                                                        <div class="media">
                                                                                                                <div class="media-left media-middle">
                                                                                                                        <i class="fa fa-file-text warning font-large-2 float-xs-left"></i>
                                                                                                                </div>
                                                                                                                <div class="media-body text-xs-right">
                                                                                                                        <h3><?= $contract_signed; ?></h3>
                                                                                                                        <span>Company convert to Client Status</span>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                </div>
                                                                        </div>-->
                                 <div class="col-xl-3 col-lg-6 col-xs-12">
																		<div class="card">
																			<div class="card-body">
																				<div class="card-block">
																					<div class="media">
																						<div class="media-left media-middle">
																							<i class="fa fa-hand-stop-o danger font-large-2 float-xs-left"></i>
																						</div>
																						<div class="media-body text-xs-right">
																							<h3><?= $on_hold; ?></h3>
																							<span> Clients On Hold Status</span>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>

																	<div class="col-xl-3 col-lg-6 col-xs-12">
																		<div class="card">
																			<div class="card-body">
																				<div class="card-block">
																					<div class="media">
																						<div class="media-left media-middle">
																							<i class="fa fa-thumbs-o-down pink font-large-2 float-xs-left"></i>
																						</div>
																						<div class="media-body text-xs-right">
																							<h3><?= $opportunity_lost; ?></h3>
																							<span>Opportunity Lost</span>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>

								</div>



																<div class="row" style="width:99.9%;">

																	<div class="col-xl-3 col-lg-6 col-xs-12">
																		<div class="card">
																			<div class="card-body">
																				<div class="card-block">
																					<div class="media">
																						<div class="media-left media-middle">
																							<i class="fa fa-check success font-large-2 float-xs-left"></i>
																						</div>
																						<div class="media-body text-xs-right">
																							<h3><?= $legal_approved; ?></h3>
																							<span>Legal Approval</span>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>

																	<div class="col-xl-3 col-lg-6 col-xs-12">
																		<div class="card">
																			<div class="card-body">
																				<div class="card-block">
																					<div class="media">
																						<div class="media-left media-middle">
																							<i class="fa fa-times danger font-large-2 float-xs-left"></i>
																						</div>
																						<div class="media-body text-xs-right">
																							<h3><?= $legal_rejected; ?></h3>
																							<span>Legal Rejected</span>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>

																	<div class="col-xl-3 col-lg-6 col-xs-12">
																		<div class="card">
																			<div class="card-body">
																				<div class="card-block">
																					<div class="media">
																						<div class="media-left media-middle">
																							<i class="fa fa-file warning font-large-2 float-xs-left"></i>
																						</div>
																						<div class="media-body text-xs-right">
																							<h3><?= $contract_completed; ?></h3>
																							<span>Contract Completed</span>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>

																</div>

	<!--/ fitness target -->
		<!-- bar charts -->

		<!--/ bar charts -->

		<!-- pie charts -->
		<!-- <div class="col-xl-12 col-lg-12 col-md-12" style="margin-bottom: 30px;">
			<div class="card">
				<div class="card-body">
					<div id="container2" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
				</div>
			</div>
		</div> -->
		<!--/ pie charts -->


	</div>
	<!--/ CRM stats -->


</div>
