<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tag;

class TagController extends Controller
{
    public function index()
    {
        $tags_officials = Tag::officials()->get();
        $tags_unofficials = Tag::unofficials()->get();
        return view('admin.tag.index', compact('tags_unofficials', 'tags_officials'));
    }

    public function create()
    {
        return view('admin.tag.create');
    }

    public function store(Request $request)
    {
        $tag = Tag::create(['name' => $request->name]);

        $redirect_arr = [
            'edit' => route('admin.tag.edit', ['tag' => $tag->id]),
            'create' => route('admin.tag.create')
        ];

        return redirect($redirect_arr[$request->redirect]);
    }

    public function edit(Tag $tag)
    {
        // fetch the data for parent tag suggest box
        // select just tags of same type and those that are child tags (to avoid recursion)
        // $available_tags = Tag::where('parent_tag_id', null)->orWhere('parent_tag_id', '!=', $tag->id);
                           
        $available_tags = Tag::where('type', $tag->type)
                            ->where('id', '!=', $tag->id)
                            ->where('parent_tag_id', null)
                            ->get();
                            
        $parent_tag = $tag->parent_tag == NULL ? [] : [$tag->parent_tag];
        
        return view('admin.tag.edit', compact('tag', 'available_tags', 'parent_tag'));
    }

    public function destroy(Request $request, Tag $tag)
    {
        $tag->delete();

        if ($request->has("redirect")) {
            return redirect($request->redirect);
        }

        return redirect()->back();
    }

    public function update(Request $request, Tag $tag)
    {
        $tag->update($request->all());

        // no parent tag set, delete if there had been any association
        if ($request->parent_tag === NULL)
        {
            $tag->parent_tag()->dissociate();
            $tag->save();
        }
        else
        {
            $tag_identification = $request->parent_tag[0];
            $new_parent_tag = Tag::getByIdOrCreateWithName($tag_identification);

            $tag->parent_tag()->associate($new_parent_tag);
            $tag->save();
        }

        return redirect()->route('admin.tag.index');
    }
}
