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
        $attedances = Attedance::query()
            ->whereHas('users.employeeAssignments', function ($assignmentQuery) use ($request) {
                $assignmentQuery->where('status', 'active')
                    ->when($request->company_id, function ($query) use ($request) {
                        $query->where('company_id', $request->company_id);
                    })
                    ->when($request->company_name, function ($query) use ($request) {
                        $query->whereHas('company', function ($companyQuery) use ($request) {
                            $companyQuery->where('name', $request->company_name);
                        });
                    });
            })
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;

                $query->whereHas('users', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                });
            })
            ->when($request->date, function ($query) use ($request) {
                $query->whereDate('date', $request->date);
            })
            ->with([
                'users.employeeAssignments' => function ($query) use ($request) {
                    $query->where('status', 'active')
                        ->when($request->company_id, function ($assignmentQuery) use ($request) {
                            $assignmentQuery->where('company_id', $request->company_id);
                        })
                        ->when($request->company_name, function ($assignmentQuery) use ($request) {
                            $assignmentQuery->whereHas('company', function ($companyQuery) use ($request) {
                                $companyQuery->where('name', $request->company_name);
                            });
                        });
                },
                'users.employeeAssignments.company',
                'users.employeeAssignments.departemen',
                'users.employeeAssignments.position',
            ])
            ->latest()
            ->paginate(10);

        $attedances->appends(
            $request->only('company_id', 'company_name', 'search', 'date')
        );

        return new AttedanceResource(
            true,
            'List Data Attendances',
            $attedances
        );
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

        $attedance = Attedance::where('user_id', $request->user()->id)->where('date', date('Y-m-d'))->first();

        if (!$attedance) {
            return response()->json(['message' => 'Checkin first'], 400);
        }

        // save checkout
        $attedance->time_out = date('H:i:s');
        $attedance->latlon_out = $request->latitude . ','  . $request->longitude;
        $attedance->save();

        return new AttedanceResource(true, 'Checkout Successfully', $attedance);
    }


    // check is checkedin

    public function isCheckedin(Request $request)
    {
        $attedance = Attedance::where('user_id', $request->user()->id)
            ->where('date', date('Y-m-d'))
            ->first();

        return response()->json([
            'checkedin' => $attedance ? true : false,
            'attedance' => $attedance
        ], 200);
    }
}
