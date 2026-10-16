<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttedanceResource;
use App\Models\Attedance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttedanceController extends Controller
{
    public function index(Request $request)
    {
        $attadances = Attedance::when($request->search, function ($query) use ($request) {
            $search = $request->search;

            $query->where(function ($query) use ($search) {
                $query->where('date', 'like', '%' . $search . '%')
                    ->orWhereHas('users', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        })
            ->when($request->date, function ($query) use ($request) {
                $query->whereDate('date', $request->date);
            })
            ->with('users')
            ->latest()
            ->paginate(10);

        $attadances->appends($request->only('search', 'date'));

        if ($attadances) {
            return new AttedanceResource(true, 'List Data  Attandances', $attadances);
        }

        return new AttedanceResource(false, 'Attendance data not available ', null);
    }


    public function checkin(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        $attedance = new Attedance();
        $attedance->user_id = $request->user()->id;
        $attedance->date = date('Y-m-d');
        $attedance->time_in = date('H:i:s');
        $attedance->latlon_in = $request->latitude . ','  . $request->longitude;
        $attedance->save();

        return new AttedanceResource(true, 'Checkin Successfully', $attedance);
    }


    public function checkout(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        $attedance = Attedance::where('user_id', $request->users()->id)->where('date', date('Y-m-d'))->first();

        if (!$attedance) {
            return response()->json(['message' => 'Checkin first'], 400);
        }

        // save checkout
        $attedance->time_out = date('H:i:s');
        $attedance->latlon_out = $request->latitude . ','  . $request->longitude;
        $attedance->save();

        return new AttedanceResource(true, 'Checkout Successfully', $attedance);
    }
}
