<?php

namespace App\Http\Controllers;

use JWTAuth;
use Validator;
use Exception;
use App\Models\Item;
use Illuminate\Http\Request;

use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;


class ItemController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function index(Request $request)
    {

        $items = $this->user->items()->get();
        return response()->json([
            'status' => true,
            'count' => $items->count(),
            'items' => $items
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $data = $request->only('name', 'description', 'quantity');
        $validator = Validator::make($data, [
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:200',
            'quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->messages()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $item = $this->user->items()->create([
                'name' => $request->name,
                'description' => $request->description,
                'quantity' => $request->quantity
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Item created successfully',
                'data' => $item
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Could not create the item.',
                'error' => $e
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        $item = $this->user->items()->find($id);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry, item not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => $item
        ], Response::HTTP_OK);

        return $item;
    }

    public function update(Request $request, $id)
    {
        $item = $this->user->items()->find($id);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry, item not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        $data = $request->only('name', 'description', 'quantity');
        $validator = Validator::make($data, [
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:200',
            'quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->messages()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $item = $item->update([
                'name' => $request->name,
                'description' => $request->description,
                'quantity' => $request->quantity,

            ]);

            return response()->json([
                'status' => true,
                'message' => 'Item updated successfully',
                'data' => $data
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Could not update the item.',
                'error' => $e
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateQuantity(Request $request, $id)
    {
        $item = $this->user->items()->find($id);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry, item not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        $data = $request->only('quantity');
        $validator = Validator::make($data, [
            'quantity' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->messages()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $item = $item->update([
                'quantity' => $request->quantity
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Item quantity updated successfully',
                'data' => $data
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Could not update the item\'s quanity',
                'error' => $e
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        $item = $this->user->items()->find($id);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry, item not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $item->delete();

            return response()->json([
                'status' => true,
                'message' => 'Item deleted successfully'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Could not delete the item',
                'error' => $e
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
