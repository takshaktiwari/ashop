<?php

namespace Takshak\Ashop\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Takshak\Ashop\Models\Shop\Address;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::where('user_id', auth()->id())->orderBy('default_addr', 'DESC')->get();
        return View::first(['shop.user.address.index', 'ashop::shop.user.address.index'])->with([
            'addresses' => $addresses
        ]);
    }

    public function create()
    {
        return View::first(['shop.user.address.create', 'ashop::shop.user.address.create']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'address_id' => 'nullable|numeric',
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

        return to_route('shop.user.addresses.index')->withSuccess('New address has been added');
    }

    public function edit(Address $address)
    {
        return View::first(['shop.user.address.edit', 'ashop::shop.user.address.edit'])->with([
            'address' => $address
        ]);
    }

    public function update(Address $address, Request $request)
    {
        $validated = $request->validate([
            'address_id' => 'nullable|numeric',
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

        return to_route('shop.user.addresses.index')->withSuccess('Address has been updated');
    }

    public function destroy(Address $address)
    {

        if ($address->default_addr) {
            $otherAddr = Address::where('id', '!=', $address->id)->first();
            if ($otherAddr) {
                $otherAddr->update(['default_addr' => true]);
            }
        }
        if ($address->billing_addr) {
            $otherAddr = Address::where('id', '!=', $address->id)->first();
            if ($otherAddr) {
                $otherAddr->update(['billing_addr' => true]);
            }
        }
        $address->delete();
        return to_route('shop.user.addresses.index');
    }

    public function makeDefault(Address $address)
    {
        Address::where('user_id', auth()->id())->update(['default_addr' => false]);
        $address->update(['default_addr' => true]);
        return back();
    }
}
