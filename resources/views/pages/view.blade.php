<?php
$template = array_get(\App\Model\Page::$TEMPLATES, $page->template_id, 'pages.default_layout');
?>

@extends($template)

@section('content')
    @foreach ($page->pageBlocks as $block)
        {!! $block->data !!}
    @endforeach
@endsection


