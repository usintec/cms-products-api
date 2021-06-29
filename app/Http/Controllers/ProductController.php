<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Library\apiHelpers;

class ProductController extends Controller
{
    use apiHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //show all the products
        return Product::all();
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
                'category_id' => 'required',
                'price' => 'required',        
            ]);
            //store product in the databse
            $product = Product::create($request->all());
            return $this->onSuccess('success', $product, 200);
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
        //show a particular product with the supplied id
        return Product::find($id);
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
            //update a product with the suplied id
            $product = Product::find($id);
            if($product){
                $product->update($request->all());
            }
            return $this->onSuccess('success', $product, 200);
        }
        return $this->onError('Unauthourized', 401);
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
            //delete a product with the supplied id
            $res = Product::destroy($id);
            return $this->onSuccess('success', $res, 200);
        }
        return $this->onError('Unauthourized', 401);
    }

    public function search(Request $request){
        $fields = $request->validate([
            'name' => 'required|string'
        ]);
        $products = Product::where('name', 'like', '%' . $fields['name'] . '%')->get();
        return $this->onSuccess('success', $products, 200);
    }
    
    public function productByCategory(Request $request){
        $fields = $request->validate([
            'category_id' => 'required'
        ]);
        $products = Product::where('category_id', $fields['category_id'])->get();
        return $this->onSuccess('success', $products, 200);
    }
}
