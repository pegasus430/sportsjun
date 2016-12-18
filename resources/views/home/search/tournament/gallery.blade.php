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
                </a>
            </div>
        @endif

        @foreach ($tournament->albums as $album)
            <?php
            $photo = $album->photos->first();
            ?>
            @if ($photo)
                <div class="col-md-3 div-col-sm-4 col-xs-12">
                    <a href="#form_gallery">

                        <img src="{{ Helper::getImagePath($photo->url,'tournaments') }}"
                             class="img img-responsive"/>
                    </a>
                </div>
            @endif
        @endforeach
    </div>

</div>