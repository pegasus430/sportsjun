<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PageStoreRequest;
use App\Http\Requests\Admin\PageUpdateRequest;
use App\Model\Page;
use App\Http\Controllers\Controller;
use App\Model\PageBlock;


class PagesController extends Controller
{

    function shareFormData()
    {
        $templates = Page::$TEMPLATES;
        \View::share(compact('templates'));
    }

    public function index()
    {
        $pages = Page::with('pageBlocks')->paginate(10);
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        $this->shareFormData();
        return view('admin.pages.form', ['item' => null]);
    }

    public function store(PageStoreRequest $request)
    {
        $page = Page::create([
            'title' => $request->title,
            'template_id' => $request->template_id,
            'linkname' => $request->slug,
            'publish_state' => Page::$STATE_PUBLISHED,
        ]);

        $blocks = $request->blocks;
        if (is_array($blocks))
            foreach ($blocks as $block) {
                PageBlock::create([
                    'page_id' => $page->id,
                    'title' => array_get($block, 'title'),
                    'type' => array_get($block, 'type'),
                    'data' => array_get($block, 'data')
                ]);

            }


        \Session::flash('message', 'Successfully created');
        return redirect()->route('admin.pages.index');
    }

    public function edit(Page $page)
    {
        $this->shareFormData();
        return view('admin.pages.form', ['item' => $page]);
    }

    public function update(PageUpdateRequest $request, Page $page)
    {

        $data = array_only($request->all(), ['title', 'template_id', 'linkname']);
        $page = $page->update($data);

        $blocks = $request->blocks;
        if (is_array($blocks))
            foreach ($blocks as $key => $block_data) {
                if ($key > 0) {
                    $block = PageBlock::whereId($key)->first();
                    if ($block) {
                        $block_data = array_only($block_data, ['title', 'type', 'data']);
                        $block->update($block_data);
                        $block->save();
                    }
                } else
                    PageBlock::create([
                        'page_id' => $page->id,
                        'title' => array_get($block, 'title'),
                        'type' => array_get($block, 'type'),
                        'data' => array_get($block, 'data')
                    ]);

            }
        \Session::flash('message', 'Successfully updated');
        return redirect()->route('admin.pages.index');

    }

    public function delete(Page $page)
    {

    }

    public function blocks()
    {
        $type = \Request::get('type');
        if ($type) {
            return view('admin.pages.block.form', compact('type'));
        }
    }

}

?>
