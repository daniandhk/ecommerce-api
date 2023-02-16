<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get data from table orders
        $data = Order::with('product', 'user')->latest()->get();

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'List Data Order',
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
        //find order by ID
        try {
            $data = Order::findOrfail($id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order Not Found',
            ], 404);
        }

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Order',
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
            'user_id'   => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'address' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $product = Product::findOrfail($request->product_id);
            $product->update([
                'quantity' => $product->quantity - $request->quantity
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Found',
            ], 404);
        }

        //save to database
        $data = Order::create([
            'user_id'     => $request->user_id,
            'product_id'   => $request->product_id,
            'quantity'   => $request->quantity,
            'address'   => $request->address,
            'status'   => 'pending',
        ]);

        //success save to database
        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'Order Created',
                'data'    => $data
            ], 201);
        }

        //failed save to database
        return response()->json([
            'success' => false,
            'message' => 'Order Failed to Save',
        ], 409);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $order
     * @return void
     */
    public function update(Request $request, $id)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //find order by ID
        try {
            $order = Order::findOrfail($id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order Not Found',
            ], 404);
        }

        try {
            $product = Product::findOrfail($order->product_id);
            if ($request->status == 'declined') {
                $product->update([
                    'quantity' => $product->quantity + $order->quantity
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Found',
            ], 404);
        }

        $order->update([
            'status'   => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order Updated',
            'data'    => $order
        ], 200);

        //data order not found
        return response()->json([
            'success' => false,
            'message' => 'Order Not Found',
        ], 404);
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        //find order by ID
        try {
            $order = Order::findOrfail($id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order Not Found',
            ], 404);
        }

        try {
            $product = Product::findOrfail($order->product_id);
            if ($order->status == 'pending') {
                $product->update([
                    'quantity' => $product->quantity + $order->quantity
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Found',
            ], 404);
        }

        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order Deleted',
        ], 200);

        //data order not found
        return response()->json([
            'success' => false,
            'message' => 'Order Not Found',
        ], 404);
    }

    public function getByUserId($id)
    {
        //get data from table orders
        $data = Order::with('product')->where('user_id', $id)->latest()->get();

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'List Data Order',
            'data'    => $data
        ], 200);
    }
}
