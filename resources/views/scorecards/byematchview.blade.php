@extends('layouts.app')
@section('content')
<div class="bymatch-wrap {{$sport_class}}">

	<div class="winner-box">
            <div id="output"></div>
            <div class="avatar">
            	@if($team_a_logo['url']!='')
              <!--    <img class="img-responsive img-circle" width="110" height="110" src="{{ url('/uploads/'.$upload_folder.'/'.$team_a_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
             {!! Helper::Images($team_a_logo['url'],$upload_folder,array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
             
                  @else
                 <!-- <img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
                 {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
                @endif
            </div>
            <div class="winning-text"> 
            	<h4>{{'Winner'}}<br><span>{{ $team_a_name }}</span></h4>
                <p>{{ date('jS F , Y',strtotime($match_data[0]['match_start_date'])).' - '.date("g:i a", strtotime($match_data[0]['match_start_time'])).(($match_data[0]['facility_name']!='')?' , '.$match_data[0]['facility_name']:'').(($match_data[0]['address']!='')?' , '.$match_data[0]['address']:'') }}</p>
            </div>
            <div class="bymatch-text">
            	Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
            </div>
        </div>
@endsection