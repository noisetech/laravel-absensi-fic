<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $companies = Company::when(request()->search, function ($companies) {
            $companies = $companies->where('name', 'like', '%' . request()->search . '%');
        })->paginate(10);


        $companies->appends(['search' => request()->search]);


        return new CompanyResource(true, 'List Data  Companies', $companies);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:companies',
            'address' => 'required|string|max:255',
            'latitude' => 'required',
            'longitude' => 'required',
            'radius_km' => 'required',
            'time_in' => 'required',
            'time_out' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $companies = Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius_km' => $request->radius_km,
            'time_in' => $request->time_in,
            'time_out' => $request->time_out,
        ]);

        if ($companies) {
            return new CompanyResource(true, 'Data Company Successfully Added!', $companies);
        }

        return new CompanyResource(false, 'Data Company Failed to Add!', null);
    }

    public function show($id)
    {
        $companies = Company::find($id);

        if ($companies) {
            return new CompanyResource(true, 'Data Company Found!', $companies);
        }

        return new CompanyResource(false, 'Data Company Not Found!', null);
    }

    public function update(Request $request, Company $company)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:companies' . $company->id,
            'address' => 'required|string|max:255',
            'latitude' => 'required',
            'longitude' => 'required',
            'radius_km' => 'required',
            'time_in' => 'required',
            'time_out' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $company->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius_km' => $request->radius_km,
            'time_in' => $request->time_in,
            'time_out' => $request->time_out,
        ]);

        return new CompanyResource(true, 'Data Company Successfully Updated!', $company);
    }

    public function destroy(Company $company)
    {
        if ($company->delete()) {
            return new CompanyResource(true, 'Data Company Successfully Deleted!', null);
        }

        return new CompanyResource(false, 'Data Company Failed to Delete!', null);
    }
}
