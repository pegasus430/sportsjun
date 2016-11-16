<div class="tournamentsGrayDown">
    <div class="ourClientsWrapp">
        <h1>OUR CLIENTS</h1>
        <div class="clientsSlide container arowSty ">
            @foreach ($our_clients as $client)
                <div class="clintBox">
                    <div class="bgcc">
                        <img src="{{Helper::getImagePath($client->image,$client->type)}}">
                    </div>
                    <h2>{{$client->name}}</h2>
                </div>
            @endforeach
        </div>
    </div>
</div>
