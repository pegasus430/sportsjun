<?php
 $sports_name = strtolower(object_get ($tournament,'sport.sports_name',''));
?>

<div class="groupstageWrapp">
    @if ($sports_name)
        @include('tournaments.player_stats.'.$sports_name)
    @endif
</div>