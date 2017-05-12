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
										@foreach($organisation->staff as $staff)
											@if($staff->roleForOrganization($organisation->id)->name=='Coach')
											<option value="{{$staff->id}}">{{$staff->name}}</option>
											@endif
										@endforeach
									</select>
								</div>
							</div>
							<div class="input-container two-col">
								<input type="text" id="start_date" required="" class="datepicker">
								<label for="start_date">Start Date</label>
								<div class="bar"></div>
							</div>
							<div class="input-container two-col">
								<input type="text" id="end_date" required="" class="datepicker">
								<label for="end_date">End Date</label>
								<div class="bar"></div>
							</div>
							
							<div class="input-container">
								<input type="text" id="no_of_players" required="">
								<label for="no_of_players">Number of Players</label>
								<div class="bar"></div>
							</div>
							<div class="input-container select two-col">
								<div>
									<label>Payment method by</label>
									<select class="pmethod" name="payment_method" required="">
										<option value="">Choose payment method</option>
										@foreach($types as $key=>$type)
											<option value="{{$key}}">{{$type}}</option>
										@endforeach
									</select>
								</div>
							</div>
							@foreach($types as $key=>$type)
							<div class="pay_options input-container {{$key}}">
								<h5>Options to pay</h5>

								@foreach(Helper::get_subscription_methods($key) as $sm)
								<div class="well">
									<div class="row">
										<div class="col-md-6">
											<div class="flex">
												<input type="checkbox" id="full_payment" /> <span for="full_payment">{{$sm->title}}</span>
												<input type="text" placeholder="Amount" class="form-control" /> </div>
										</div>
										<div class="col-md-6">
											<div class="flex">
												<input type="checkbox" id="discount" /> <span for="discount" style="font-size: 12px;">Include discount of</span>
												<input type="text" placeholder="" class="form-control" style="width: 100px;" />&nbsp;&nbsp; <span for="discount" style="font-size: 16px;">%</span> </div>
										</div>
									</div>
								</div>

								@endforeach
							</div>

							@endforeach
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

<script type="text/javascript">
	  $(".datepicker").datepicker();
   
</script>

@stop