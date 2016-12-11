<div class="ourClientsWrapp">
    <h1>OUR CLIENTS</h1>
    <div class="clientsSlide container arowSty ">
        @foreach ($our_clients as $client)
            <div class="clintBox">
                <div class="bgcc">
                    <img src="{{Helper::ImageFit(Helper::getImagePath($client->image,$client->type),102,102)}}">
                </div>
                <h2>{{$client->name}}</h2>
            </div>
        @endforeach
    </div>
</div>
