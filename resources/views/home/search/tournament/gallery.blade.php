<div class="tournamentDetailWrapp">
    <h1>Gallery</h1>
    <div class="row">
        <?php
        $form_gallery_count = $tournament->profile_album_photos->count();
        $form_gallery_photo = $tournament->profile_album_photos->first();
        ?>
        @if ($form_gallery_count)
            <div class="col-md-3 div-col-sm-4 col-xs-12">
                <a href="#form_gallery">
                    <img src="{{ Helper::getImagePath($form_gallery_photo->url,'tournaments') }}"
                         class="img img-responsive"/>
                    Form Gallery ({{$form_gallery_count}})
                </a>
            </div>
        @endif

        @if ($tournament->albums && $tournament->albums->count())
            <h2>Albums</h2>
            @foreach ($tournament->albums as $album)
                <?php
                $photo = $album->photos->first();
                ?>
                @if ($photo)
                    <div class="col-md-3 div-col-sm-4 col-xs-12">
                        <a href="#form_gallery">
                            <img src="{{ Helper::getImagePath($photo->url,'tournaments') }}"
                                 class="img img-responsive"/>
                            ({{ $album->photos->count() }})
                        </a>
                    </div>
                @endif
            @endforeach
        @endif
    </div>

</div>