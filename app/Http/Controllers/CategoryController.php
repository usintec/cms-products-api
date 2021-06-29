<?php
namespace App\Http\Controllers;

use App\Http\Library\apiHelpers;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use apiHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return all categories
        return Category::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($this->isAdmin(auth()->user())){
            $request->validate([
                'name' => 'required|string',
                'slug' => 'required|string',
            ]);
            //store a category
            $category = Category::create($request->all());
            return $this->onSuccess('success', $category, 200);
        } 
        return $this->onError('Unauthourized', 401);
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //display a category by id
        return Category::find($id);
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
        if($this->isAdmin(auth()->user())){
            //update a category
            $category = Category::find($id);
            if($category){
                $category->update($request->all());
            }
            return $this->onSuccess('success', $category, 200);
        }
        return $this->isError('Unauthourized', 401);
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->isAdmin(auth()->user())){
            //delete category
            $res = Category::destroy($id);
            return $this->onSuccess('success', $res, 200);
        }
        return $this->onError('Unauthourized', 401);

    }
}
