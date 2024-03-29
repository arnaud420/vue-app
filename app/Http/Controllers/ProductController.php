<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();

        return response()->json([
            'products' => $products
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric'
        ]);

        $input = $request->all();

        $product = Product::create($input);

        return response()->json([
            'product' => $product,
            'message' => 'Success'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric'
        ]);

        $product = Product::findOrFail($id);
        /*$input = $request->all();
        $product_save = $product->save($input);*/
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');

        $product->save();

        if ($product) {
            return response()->json([
                'Product update with success !'
            ], 200);
        }
        else {
            return abort(500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *(
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $product = Product::find($id);
        $product->delete();
        return response()->json([
            'product' => $product
        ], 200);
    }
}
