<div class="testimonialsWrapp">
    <h1>TESTIMONIALS</h1>
    <div class="container">
        <div class="testiWrapp testimornial arowSty">
            @foreach ($testimonials as $testimonial)
            <div class="testimornialBox ">
                <div class="profileRound">
                    <img src="{{Helper::getImagePath($testimonial->image,$testimonial->type)}}">
                </div>
                <div class="col-lg-7 col-md-7">
                    <p>{!! $testimonial->description !!}}</p>
                    <h4>-{{ $testimonial->name }} <span> {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i',array_get($testimonial->data,'date','2016-08-23 14:30'))->format('g:ia j M Y') }}</span></h4>
                </div>
            </div>
            @endforeach
        </div>
        <a href="#" class="readAll">READ ALL</a>
    </div>
</div>