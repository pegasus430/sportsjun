<div class="row deletePhoto">
    <div class="col-sm-12">
		<div class="jFiler jFiler-theme-default">
<div class="jFiler-items jFiler-row">
@foreach($photos as $photo)
<ul class="jFiler-items-list jFiler-items-grid">
  <li class="jFiler-item remove_li_{{ (!empty($photo->id)?$photo->id:"")}}" data-jfiler-index="0" style="">
 <div class="jFiler-item-container">
  <div class="jFiler-item-inner">
		  		  
<div class="jFiler-item-thumb"> 

 {!! Helper::Images((!empty($photo->url)?$photo->url:''),$type,array('class'=>'jFiler-item-thumb') )!!}
<div class="jFiler-item-status"></div>
 <div class="jFiler-item-info">   <span class="jFiler-item-title">
<b title="{{ $photo->title }}">Created date:  {{ date("d F Y",strtotime($photo->created_at)) }}</b>
</span>

</div>
<div class="jFiler-item-thumb-image"> </div></div>
	<div class="jFiler-item-assets jFiler-row">
<ul class="list-inline pull-left">
<li>
<div class="jFiler-jProgressBar" style="display: none;">
<div class="bar" style="width: 100%;"></div>
</div>
<div class="jFiler-item-others text-success" style="">
<i class="icon-jfi-check-circle"></i>
Success
</div>
</li>
</ul>
<ul class="list-inline pull-right">
<li>

@if(!empty($photo->id))
  <a href="javascript:void(0);" class="deleteimage_{{ (!empty($photo->id)?$photo->id:"")}}"><div class="icon-jfi-trash" ></div></a>
  @else
	  <a class="icon-jfi-trash jFiler-item-trash-action"  ></a>
  @endif
</li>
</ul>
</div>
   

</div>
</li>
</ul>
@endforeach
</div>
</div>
</div>
</div>