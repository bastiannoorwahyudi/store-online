<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;

// Panggil Form Category Request
use App\Http\Requests\Admin\CategoryRequest;

use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // cek apakah requestnya AJAX atau bukan jika ya
        if(request()->ajax())
        {
            $query = Category::query();
            
            return Datatables::of($query)
                ->addColumn('action', function($item) {
                    // membuat button dropdown update & delete di datatables server side
                    return '
                        <div class="btn-group">
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle mr-1 mb-1"
                                        type="button"
                                        data-toggle="dropdown">
                                        Aksi
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="' . route('category.edit', $item->id) . '">
                                        Sunting
                                    </a>
                                    <form action="'. route('category.destroy', $item->id) .'" method="POST">
                                        '. method_field('delete') . csrf_field() .'
                                        <button type="submit" class="dropdown-item text-danger">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    ';
                })
                ->editColumn('photo', function($item) {
                    return $item->photo ? '<img src="'. Storage::url($item->photo) .'" style="max-height: 40px;" />' : '';
                })
                ->rawColumns(['action','photo'])
                ->make();
        }

        return view ('pages.admin.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('pages.admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $data_category = $request->all();

        $data_category['slug'] = Str::slug($request->name);
        $data_category['photo'] = $request->file('photo')->store('assets/category', 'public');

        Category::create($data_category);

        return redirect()->route('category.index');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Category::findOrFail($id);

        return view ('pages.admin.category.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $data_category = $request->all();

        $data_category['slug'] = Str::slug($request->name);
        $data_category['photo'] = $request->file('photo')->store('assets/category', 'public');

        $item = Category::findOrFail($id);
        $item->update($data_category);

        return redirect()->route('category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Category::findOrFail($id);
        $item->delete();

        return redirect()->route('category.index');
    }
}
