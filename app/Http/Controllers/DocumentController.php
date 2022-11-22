<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Image;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $documents = Document::where([
            ['name', '!=', Null],
            [function ($query) use ($request) {
                if (($term = $request->term)) {
                    $query->orWhere('name', 'LIKE', '%' . $term . '%')->get();
                }
            }]
        ])
            ->orderBy('id', 'asc')
            ->paginate(10);
        return view('admincp.index', compact('documents'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admincp.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'required|unique:documents|max:255',
                // 'images' => 'image|mimes:jpg,png,gif,svg|max:2048|dimensions:min_width=100,min_height=100',
                'file' => 'mimes:doc,pdf,docx,zip',
            ],
            [
                'name.required' => 'Tên tài liệu phải có!',
                'name.unique' => 'Tên tài liệu đã có, xin điền tên khác!',
            ]
        );

        $doc = Document::create($data);
        if ($request->has('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = $data['name'] . '-image-' . time() . rand(1, 1000) . '.' . $image->extension();
                $image->move(public_path('uploads/document_images'), $imageName);
                Image::create([
                    'document_id' => $doc->id,
                    'image' => $imageName,
                ]);
            }
        }

        // $get_file = $request->file;

        // if ($get_file != null) {

        //     $path = 'public/uploads/files/';
        //     $get_name_file = $get_file->getClientOriginalName();
        //     $name_file = current(explode('.', $get_name_file));
        //     $new_file = $name_file . rand(0, 99) . '.' . $get_file->getClientOriginalExtension();
        //     $get_file->move($path, $new_file);
        //     $doc->files = $new_file;
        // } else {
        //     $doc->files = '';
        // }


        return back()->with('status', 'Thêm tài liệu thành công!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $doc = Document::find($id);
        if (!$doc) abort(404);
        $images = $doc->images;
        return view('admincp.images', compact('doc', 'images'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $doc = Document::find($id);
        return view('admincp.edit')->with(compact('doc'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $data = $request->validate(
            [
                'name' => 'required|max:255',
                'file' => 'mimes:doc,pdf,docx,zip',
            ],
            [
                'name.required' => 'Tên tài liệu phải có!',
                'name.unique' => 'Tên tài liệu đã có, xin điền tên khác!',

            ]
        );

        $doc = new Document();
        $doc->name = $data['name'];

        //them anh cao folder
        $get_image = $request->image;

        if ($get_image) {

            $path = 'public/uploads/images/' . $doc->image;

            if (file_exists($path)) {
                unlink($path);
            }

            $path = 'public/uploads/images/';
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . rand(0, 99) . '.' . $get_image->getClientOriginalExtension();
            $get_image->move($path, $new_image);
            $doc->images = $new_image;
        }

        $get_file = $request->file;

        $path = 'public/uploads/files/';
        $get_name_file = $get_file->getClientOriginalName();
        $name_file = current(explode('.', $get_name_file));
        $new_file = $name_file . rand(0, 99) . '.' . $get_file->getClientOriginalExtension();
        $get_file->move($path, $new_file);
        $doc->files = $new_file;

        $doc->save();

        return redirect()->back()->with('status', 'Thêm tài liệu thành công!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $doc = Document::find($id);
        $path = 'public/uploads/document_images/' . $doc->image;
        if (file_exists($path)) {
            unset($path);
        }
        Document::find($id)->delete();
        return redirect()->back()->with('status', 'Xóa tài liệu thành công!');
    }
}
