<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\User\AddressResource;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $user = apiUser();
        $address = Address::query()->where('user_id', $user->id)->get();
        return apiSuccess(AddressResource::collection($address));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'address' => 'required|max:255',
            'type' => 'required|in:' . Address::HOME . ',' . Address::WORK . ',' . Address::OTHER,
            'lat' => 'required',
            'lng' => 'required',
        ]);
        $data = $request->all();
        if ($request->has('default')) {
            apiUser()->addresses()->update([
                'default' => NO
            ]);
            $data['default'] = YES;
        } else {
            $data['default'] = NO;
        }
        $data['user_id'] = apiUser()->id;
        $address = Address::create($data);
        return apiSuccess(new AddressResource($address), api('Successfully Created'));
    }

    public function show($id)
    {
        $address = Address::query()->where('user_id', apiUser()->id)->findOrFail($id);
        return $this->sendResponse(new AddressResource($address));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'max:255',
            'address' => 'max:255',
            'type' => 'in:' . Address::HOME . ',' . Address::WORK . ',' . Address::OTHER,
            'lat' => 'nullable',
            'lng' => 'nullable',
            'default' => 'boolean',
        ]);
        $address = Address::query()->where('user_id', apiUser()->id)->findOrFail($id);
        $data = $request->all();

        if ($request->has('default')) {
            apiUser()->addresses()->where('id', '<>', $id)->update([
                'default' => NO
            ]);
            $data['default'] = YES;
        } else {
            $data['default'] = NO;
        }
        $data['user_id'] = apiUser()->id;

        $address->update($data);
        return apiSuccess(new AddressResource($address), api('Successfully Updated'));
    }

    public function destroy($id)
    {
        $address = Address::query()->where('user_id', apiUser()->id)->findOrFail($id);
        if ($address->orders()->count() > 0) return apiError(api('Can not delete this address because there is orders related with this address'));
        $address->delete();
        return apiSuccess(null, api('Successfully Deleted'));
    }
}
