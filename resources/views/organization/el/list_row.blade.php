<div class="t_details">
    <div class="row main_tour">
        <div class="col-md-2 col-sm-3 col-xs-12 text-center">
            @if(count($organization['photos']))
                @foreach($organization['photos'] as $p)
                    {!! Helper::Images((!empty($p['url'])?$p['url']:''),'organization',array('class'=>'img-circle img-border img-scale-down','height'=>90,'width'=>90) )!!}
                @endforeach
            @else
                {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border  img-scale-down','height'=>90,'width'=>90) )!!}
            @endif
        </div>
        <div class="col-md-10 col-sm-9 col-xs-12">
            <div class="sm-center">
                <div class="t_tltle">

                    <div class="pull-left">
                        <a href="{{ url('getorgteamdetails/'.$organization['id']) }}">{{
                                                                            !empty($organization['name'])?$organization['name']:''
                                                                            }}</a>
                        <p class="t_by">By <a target="_blank"
                                              href="{{ url('/editsportprofile/'.(!empty($id)?$id:0))}}">{{
                                                                                !empty($organization['user']['name'])?$organization['user']['name']:''
                                                                                }}</a></p>
                    </div>
                    @if(isset($userId) && ($userId == Auth::user()->id))
                        <div class="pull-right ed-btn">
                            <a href="{{ url('/organization/'.(!empty($organization['id'])?$organization['id']:0).'/edit') }}"
                               class="edit" title="Edit"
                               data-toggle="tooltip"
                               data-placement="top"><i
                                        class="fa fa-pencil"></i></a>
                            <a href="{{ url('/organization/delete/'.(!empty($organization['id'])?$organization['id']:0)).'/'.(empty($organization['isactive'])?'a':'d')}}"
                               class="delete" title="Deactivate"
                               data-toggle="tooltip"
                               data-placement="top">
                                {!! empty($organization['isactive'])?"<i
                                        class='fa fa-check'></i>":"<i
                                        class='fa fa-ban'></i>" !!}
                            </a>
                        </div>
                    @endif
                </div>
                <ul class="t_tags">
                    <li>Teams: <span
                                class="green">{{ !empty($organization['teamplayers'])?count($organization['teamplayers']):0 }}</span>
                    </li>

                </ul>
                <p class="lt-grey">{{
                                                                    !empty($organization['about'])?$organization['about']:''
                                                                    }}</p>
            </div>
        </div>
    </div>
</div>
