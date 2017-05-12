@extends('layouts.organisation')


@section('content')

	<style>
		.pay_options {
			display: none;
		}
		
		.flex {
			display: flex;
			text-align: left;
			-webkit-align-items: center;
			align-items: center;
		}
		
		.flex input[type="checkbox"] {
			width: 100px;
		}
		
		.flex span {
			width: 250px;
			font-size: 16px;
		}
		
		.form .input-container .flex input[type="text"] {
			border: 1px solid rgba(0, 0, 0, 0.2);
			box-shadow: none;
			height: inherit;
			font-size: 16px;
		}
	</style>

<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> New Coaching Sessions</h2> </div>
			</div>
			<div class="row">
				<div class="col-md-offset-2 col-md-8 bg-white pdtb-30 text-center">
					<form action="" class="form form-horizontal">
						<div class="content">
							<div class="input-container">
								<input type="text" id="session_title" required="required">
								<label for="session_title">Session Title</label>
								<div class="bar"></div>
							</div>
							<div class="input-container select two-col">
								<div>
									<label>Sport</label>
									<select class="" name="staff_role">
											@foreach(Helper::getAllSports() as $sport)

											@endforeach
									</select>
								</div>
							</div>
							<div class="input-container select two-col">
								<div>
									<label>Coach</label>
									<select class="" name="staff_role">
										@foreach($organisation->coaches as $staff)

										@endforeach
									</select>
								</div>
							</div>
							<div class="input-container two-col">
								<input type="text" id="start_date" required="">
								<label for="start_date">Start Date</label>
								<div class="bar"></div>
							</div>
							<div class="input-container two-col">
								<input type="text" id="end_date" required="">
								<label for="end_date">End Date</label>
								<div class="bar"></div>
							</div>
							<div class="input-container two-col">
						
							<div class="input-container">
								<input type="text" id="no_of_players" required="">
								<label for="no_of_players">Number of Players</label>
								<div class="bar"></div>
							</div>
							<div class="input-container select two-col">
								<div>
									<label>Payment method by</label>
									<select class="pmethod" name="payment_method">
										<option value="">Choose payment method</option>
										<option value="by_month">By duration in months</option>
										<option value="by_installment">By installments</option>
									</select>
								</div>
							</div>
							<div class="pay_options input-container by_month">
								<h5>Options to pay</h5>
								<div class="well">
									<div class="row">
										<div class="col-md-6">
											<div class="flex">
												<input type="checkbox" id="full_payment" /> <span for="full_payment">Yearly payment</span>
												<input type="text" placeholder="Amount" class="form-control" /> </div>
										</div>
										<div class="col-md-6">
											<div class="flex">
												<input type="checkbox" id="discount" /> <span for="discount" style="font-size: 12px;">Include discount of</span>
												<input type="text" placeholder="" class="form-control" style="width: 100px;" />&nbsp;&nbsp; <span for="discount" style="font-size: 16px;">%</span> </div>
										</div>
									</div>
								</div>
								<div class="well">
									<div class="row">
										<div class="col-md-6">
											<div class="flex">
												<input type="checkbox" id="full_payment" /> <span for="full_payment">Half yearly payment</span>
												<input type="text" placeholder="Amount" class="form-control" /> </div>
										</div>
										<div class="col-md-6">
											<div class="flex">
												<input type="checkbox" id="discount" /> <span for="discount" style="font-size: 12px;">Include discount of</span>
												<input type="text" placeholder="" class="form-control" style="width: 100px;" />&nbsp;&nbsp; <span for="discount" style="font-size: 16px;">%</span> </div>
										</div>
									</div>
								</div>
								<div class="well">
									<div class="row">
										<div class="col-md-6">
											<div class="flex">
												<input type="checkbox" id="full_payment" /> <span for="full_payment">Quarterly Payment</span>
												<input type="text" placeholder="Amount" class="form-control" /> </div>
										</div>
										<div class="col-md-6">
											<div class="flex">
												<input type="checkbox" id="discount" /> <span for="discount" style="font-size: 12px;">Include discount of</span>
												<input type="text" placeholder="" class="form-control" style="width: 100px;" />&nbsp;&nbsp; <span for="discount" style="font-size: 16px;">%</span> </div>
										</div>
									</div>
								</div>
								<div class="well">
									<div class="row">
										<div class="col-md-6">
											<div class="flex">
												<input type="checkbox" id="full_payment" /> <span for="full_payment">Monthly Payment</span>
												<input type="text" placeholder="Amount" class="form-control" /> </div>
										</div>
										<div class="col-md-6">
											<div class="flex">
												<input type="checkbox" id="discount" /> <span for="discount" style="font-size: 12px;">Include discount of</span>
												<input type="text" placeholder="" class="form-control" style="width: 100px;" />&nbsp;&nbsp; <span for="discount" style="font-size: 16px;">%</span> </div>
										</div>
									</div>
								</div>
							</div>
							<div class="pay_options input-container by_installment">
								<h5>Options to pay</h5>
								<div class="well">
									<div class="row">
										<div class="col-md-6">
											<div class="flex">
												<input type="checkbox" id="full_payment" /> <span for="full_payment">Full payment</span>
												<input type="text" placeholder="Amount" class="form-control" /> </div>
										</div>
										<div class="col-md-6">
											<div class="flex">
												<input type="checkbox" id="discount" /> <span for="discount" style="font-size: 12px;">Include discount of</span>
												<input type="text" placeholder="" class="form-control" style="width: 100px;" />&nbsp;&nbsp; <span for="discount" style="font-size: 16px;">%</span> </div>
										</div>
									</div>
								</div>
								<div class="well">
									<div class="row">
										<div class="col-md-6">
											<div class="flex">
												<input type="checkbox" id="full_payment" /> <span for="full_payment">Two half payment</span>
												<input type="text" placeholder="Amount" class="form-control" /> </div>
										</div>
										<div class="col-md-6">
											<div class="flex">
												<input type="checkbox" id="discount" /> <span for="discount" style="font-size: 12px;">Include discount of</span>
												<input type="text" placeholder="" class="form-control" style="width: 100px;" />&nbsp;&nbsp; <span for="discount" style="font-size: 16px;">%</span> </div>
										</div>
									</div>
								</div>
								<div class="well">
									<div class="row">
										<div class="col-md-6">
											<div class="flex">
												<input type="checkbox" id="full_payment" /> <span for="full_payment">Four quarter Payment</span>
												<input type="text" placeholder="Amount" class="form-control" /> </div>
										</div>
										<div class="col-md-6">
											<div class="flex">
												<input type="checkbox" id="discount" /> <span for="discount" style="font-size: 12px;">Include discount of</span>
												<input type="text" placeholder="" class="form-control" style="width: 100px;" />&nbsp;&nbsp; <span for="discount" style="font-size: 16px;">%</span> </div>
										</div>
									</div>
								</div>
							</div>
							<center>
								<input type="checkbox" id="parental_info" />
								<label for="parental_info">Parental information is mandatory for registration. </label>
								<div class="clearfix"></div>
								<button type="submit" class="btn btn-primary">Create</button>
							</center>
						</div>
					</form>
				</div>
			</div>
		</div>

@stop


@section('end_scripts')

<script type="">
$(document).ready(function () {
			$("select.pmethod").change(function () {
				$(this).find("option:selected").each(function () {
					var optionValue = $(this).attr("value");
					if (optionValue) {
						$(".pay_options").not("." + optionValue).hide();
						$("." + optionValue).show();
					}
					else {
						$(".pay_options").hide();
					}
				});
			}).change();
		});
</script>

@stop