<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Cities;
use App\Models\Dysciplines;
use App\Models\User;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (auth()->check()) {
            $user = auth()->user();

            $cities = User::select('city')->groupBy('city')->get();
            $disciplines = Dysciplines::all();
            $checkedDisciplines = $user->disciplines()->get();
            $description = $user->description;
            $photos = $user->images()->get();
            $certificates = $user->certificates()->get();


            return view('trainer_dashboard\dashboard', compact('user', 'disciplines', 'checkedDisciplines', 'cities', 'photos', 'description', 'certificates'));
        } else
            return view('login');
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $user->firstName = $request->input('firstName');
        $user->secondName = $request->input('secondName');
        $user->city = $request->input('city');
        $user->phoneNumber = $request->input('phoneNumber');
        $user->disciplines()->sync($request->input('disciplines'));
        if($user->description != $request->input('description'))
            $user->description = $request->input('description');
        $user->save();

        return redirect('/trainer-dashboard');
    }

    public function updateDescription(Request $request)
    {
        $user = auth()->user();
        $user->description = $request->input('description');
        $user->save();
        return redirect('/trainer-dashboard');
    }

}
