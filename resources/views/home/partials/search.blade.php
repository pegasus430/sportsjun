<div class="searchWrapp total_menu_wrap">
    <form method="GET" action="{{route('public.search.list')}}">
        <div class="">
            <div class="serchIco"><img src="/themes/sportsjun/images/search_ico.png" width="28" height="28"></div>
            <div class="group">
                <input name="what" type="text" required value="{{ $what or '' }}"
                       class="jq-autocomplete"
                       data-source="{{route('public.search.guess')}}"
                >
                <span class="highlight"></span>
                <span class="bar"></span>
                <label>Enter your key word <span>( Tournaments, Matches, Player, Location )</span></label>
            </div>
            <input name="search" class="serachBt" value="SEARCH" type="submit">
        </div>
    </form>
</div>