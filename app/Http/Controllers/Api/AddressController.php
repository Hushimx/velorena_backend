<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AddressController extends Controller
{
    /**
     * Get all addresses for authenticated user
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            
            $addresses = Address::where('user_id', $user->id)
                ->orderBy('is_default', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $addresses
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to fetch addresses', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch addresses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific address
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            
            $address = Address::where('user_id', $user->id)
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $address
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Failed to fetch address', [
                'error' => $e->getMessage(),
                'address_id' => $id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch address',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new address
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            
            $validator = Validator::make($request->all(), Address::getValidationRules());

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $addressData = $validator->validated();
            $addressData['user_id'] = $user->id;

            // Handle building image upload if present
            if ($request->hasFile('building_image')) {
                $image = $request->file('building_image');
                $path = $image->store('addresses/building-images', 'public');
                $addressData['building_image_url'] = Storage::url($path);
            }

            $address = Address::create($addressData);

            // If this is marked as default or it's the first address, set it as default
            if (($addressData['is_default'] ?? false) || Address::where('user_id', $user->id)->count() === 1) {
                $address->setAsDefault();
                $address->refresh();
            }

            return response()->json([
                'success' => true,
                'message' => 'Address created successfully',
                'data' => $address
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Failed to create address', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create address',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing address
     */
    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();
            
            $address = Address::where('user_id', $user->id)
                ->findOrFail($id);

            $validator = Validator::make($request->all(), Address::getValidationRules(true));

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $addressData = $validator->validated();

            // Handle building image upload if present
            if ($request->hasFile('building_image')) {
                // Delete old image if exists
                if ($address->building_image_url) {
                    $oldPath = str_replace('/storage/', '', parse_url($address->building_image_url, PHP_URL_PATH));
                    Storage::disk('public')->delete($oldPath);
                }

                $image = $request->file('building_image');
                $path = $image->store('addresses/building-images', 'public');
                $addressData['building_image_url'] = Storage::url($path);
            }

            $address->update($addressData);

            // If marked as default, set it
            if ($addressData['is_default'] ?? false) {
                $address->setAsDefault();
                $address->refresh();
            }

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully',
                'data' => $address
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Failed to update address', [
                'error' => $e->getMessage(),
                'address_id' => $id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update address',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an address
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();
            
            $address = Address::where('user_id', $user->id)
                ->findOrFail($id);

            $wasDefault = $address->is_default;

            // Delete building image if exists
            if ($address->building_image_url) {
                $oldPath = str_replace('/storage/', '', parse_url($address->building_image_url, PHP_URL_PATH));
                Storage::disk('public')->delete($oldPath);
            }

            $address->delete();

            // If deleted address was default, set another address as default
            if ($wasDefault) {
                $newDefault = Address::where('user_id', $user->id)->first();
                if ($newDefault) {
                    $newDefault->setAsDefault();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Address deleted successfully'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Failed to delete address', [
                'error' => $e->getMessage(),
                'address_id' => $id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete address',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set an address as default
     */
    public function setDefault($id)
    {
        try {
            $user = Auth::user();
            
            $address = Address::where('user_id', $user->id)
                ->findOrFail($id);

            $address->setAsDefault();
            $address->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Default address updated successfully',
                'data' => $address
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Failed to set default address', [
                'error' => $e->getMessage(),
                'address_id' => $id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to set default address',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

