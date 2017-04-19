@extends('layouts.organisation')

@section('content')
<div class="container">
           
            <div class="row">
                <div class="col-md-12">
                    <h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Marketplace</h2> 

                     <div class="create-btn-link"><a href="/marketplace/create" class="wg-cnlink" >New Item</a></div>
                    </div>
            </div>
            <div class="row">

    <div class="sportsjun-forms sportsjun-container wrap-2">
        

            @if(count($items))
                
                


             @else

                    <div class="sj-alert sj-alert-info sj-alert-sm message_new_for_team">
                        Post your Old/New Sports Equipment, Buying and Selling made easy.
                        </div>
                        <div class="intro_list_container">
                                <ul class="intro_list_on_empty_pages">
                                        <span class="steps_to_follow">Steps to follow:</span>
                                        <li>Click on the <span class="bold">Create New +</span> button on the top left side, select <span class="bold">Market Place.</span></li>
                                        <li><span class="bold">Upload</span> images and <span class="bold">fill</span> all the details related to item</li>
                                        <li>Your item will be posted, after approval by the Admin</li>
                                </ul>
                    </div>

                @endif
                        <!-- .col-lg-4.col-sm-6 -->
                 
                        <!-- .col-lg-4.col-sm-6 -->
                    </div>
                </div>
            </div>
        </div>

@stop


@section('end_scripts')
    <script src="/org/js/bootstrap.slider.min.js"></script>
@stop