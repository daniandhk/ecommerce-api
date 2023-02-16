<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get data from table products
        $data = Product::latest()->get();

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'List Data Product',
            'data'    => $data
        ], 200);
    }

    /**
     * show
     *
     * @param  mixed $id
     * @return void
     */
    public function show($id)
    {
        //find product by ID
        try {
            $data = Product::findOrfail($id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Found',
            ], 404);
        }

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Product',
            'data'    => $data
        ], 200);
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name'   => 'required',
            'description' => 'required',
            'price' => 'required',
            'quantity' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //find product by name with soft delete
        $product = Product::withTrashed()->where('name', $request->name)->first();

        if ($product) {
            //restore soft delete
            $product->restore();

            //update product
            $product->update([
                'description' => $request->description,
                'price' => $request->price,
                'quantity' => $request->quantity,
            ]);

            //success update product
            return response()->json([
                'success' => true,
                'message' => 'Product Updated',
                'data'    => $product
            ], 200);
        } else {
            //save to database
            $data = Product::create([
                'name'     => $request->name,
                'description'   => $request->description,
                'price'     => $request->price,
                'quantity'   => $request->quantity,
            ]);

            //success save to database
            if ($data) {

                return response()->json([
                    'success' => true,
                    'message' => 'Product Created',
                    'data'    => $data
                ], 201);
            }
        }

        //failed save to database
        return response()->json([
            'success' => false,
            'message' => 'Product Failed to Save',
        ], 409);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $product
     * @return void
     */
    public function update(Request $request, $id)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name'   => 'required',
            'description' => 'required',
            'price' => 'required',
            'quantity' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //find product by ID
        try {
            $product = Product::findOrfail($id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Found',
            ], 404);
        }

        if ($product->name != $request->name) {
            $validator = Validator::make($request->all(), [
                'name'   => 'unique:products',
            ]);

            //response error validation
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
        }

        $product->update([
            'name'     => $request->name,
            'description'   => $request->description,
            'price'     => $request->price,
            'quantity'   => $request->quantity,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product Updated',
            'data'    => $product
        ], 200);
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        //find product by ID
        try {
            $product = Product::findOrfail($id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Found',
            ], 404);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product Deleted',
        ], 200);
    }
}
