<div class="searchWrapp total_menu_wrap">
    <form method="GET" action="{{route('public.search.list')}}">
        <div class="col-sm-9 col-xs-12">
            <div class="serchIco"><img src="/themes/sportsjun/images/search_ico.png" width="28" height="28"></div>
            <div class="group">
                <input name="what" type="text" required value="{{ $what or '' }}"
                       class="jq-autocomplete"
                       data-source="{{route('public.search.guess')}}"
                >
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Enter your keyword <span>( Tournaments, Matches, Player, Location, Organization )</span></label>
            </div>
        </div>
        <div class="col-sm-3 col-xs-12">
            <input name="search" class="serachBt" value="SEARCH" type="submit">
        </div>
    </form>
</div>