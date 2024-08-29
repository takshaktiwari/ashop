<?php

namespace Takshak\Ashop\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Takshak\Ashop\Http\Resources\AddressesResource;
use Takshak\Ashop\Models\Shop\Address;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::where('user_id', auth()->id())->get();
        return AddressesResource::collection($addresses);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:200',
            'mobile' => 'required|max:50',
            'address_line_1' => 'required|max:255',
            'address_line_2' => 'required|max:255',
            'landmark' => 'required|max:255',
            'city' => 'required|max:200',
            'pincode' => 'required|max:10',
            'state' => 'required|max:200',
            'country' => 'required|max:200',
            'default_addr' => 'nullable|boolean',
            'billing_addr' => 'nullable|boolean',
        ]);

        $address = Address::create($validated + ['user_id' => auth()->id()]);
        if ($request->boolean('default_addr')) {
            Address::where('id', '!=', $address->id)->update(['default_addr' => false]);
        }
        if ($request->boolean('billing_addr')) {
            Address::where('id', '!=', $address->id)->update(['billing_addr' => false]);
        }

        return AddressesResource::make($address);
    }

    public function show(Address $address)
    {
        abort_if($address->user_id != auth()->id(), 404, 'Address is not found');
        return AddressesResource::make($address);
    }

    public function update(Request $request, Address $address)
    {
        abort_if($address->user_id != auth()->id(), 404, 'Address is not found');

        $validated = $request->validate([
            'name' => 'required|max:200',
            'mobile' => 'required|max:50',
            'address_line_1' => 'required|max:255',
            'address_line_2' => 'required|max:255',
            'landmark' => 'required|max:255',
            'city' => 'required|max:200',
            'pincode' => 'required|max:10',
            'state' => 'required|max:200',
            'country' => 'required|max:200',
            'default_addr' => 'nullable|boolean',
            'billing_addr' => 'nullable|boolean',
        ]);

        $address->update($validated + ['user_id' => auth()->id()]);
        if ($request->boolean('default_addr')) {
            Address::where('id', '!=', $address->id)->update(['default_addr' => false]);
        }
        if ($request->boolean('billing_addr')) {
            Address::where('id', '!=', $address->id)->update(['billing_addr' => false]);
        }

        return AddressesResource::make($address);
    }

    public function delete(Address $address)
    {
        abort_if($address->user_id != auth()->id(), 404, 'Address is not found');
        $address->delete();

        return response()->json(['data' => ['message' => 'Address has been deleted']]);
    }
}
