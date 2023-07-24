<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Validation\Rules;

class ManageUserController extends Controller
{
    function __construct(){
        $this->middleware(function ($request, $next) {
            if ($request->user()->role == 'user') {
                abort(401, 'This action is unauthorized.');
            }

            return $next($request);
        });
    }

    public function index(){
        $users = User::query()->select('id', 'name', 'email', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'foto_ktp')->where('role', '=', 'user')->get();
        return Inertia::render('User/Index', [
            'records' => $users
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required',
            'alamat' => 'required',
            'foto_ktp' => 'required',
            'jenis_kelamin' => 'required'
        ]);
        $file = $request->file('foto_ktp');
        Storage::disk('public')->putFileAs('/', $file, $file->getClientOriginalName());
        $fotoKtp = Storage::url($file->getClientOriginalName());
        $user = new User();
        $user->name = $request->nama;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'user';
        $user->tempat_lahir = $request->tempat_lahir;
        $user->tanggal_lahir = $request->tanggal_lahir;
        $user->alamat = $request->alamat;
        $user->foto_ktp = $fotoKtp;
        $user->jenis_kelamin = $request->jenis_kelamin;
        $user->save();

        return back();
    }

    public function delete(Request $request)
    {
        User::query()->where('id', '=', $request->id)->delete();

        return back();
    }

    public function edit(Request $request)
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required'
        ];

        if(!is_null($request->password))
            $rules['password'] = ['confirmed', Rules\Password::defaults()];
        $request->validate($rules);

        $form = $request->except(['id', 'foto_ktp', 'password', 'password_confirmation', 'nama']);
        $form['name'] = $request->nama;

        if($request->hasFile('foto_ktp')){
            $file = $request->file('foto_ktp');

            Storage::disk('public')->putFileAs('/', $file, $file->getClientOriginalName());
            $form['foto_ktp'] = Storage::url($file->getClientOriginalName());
        }

        if(!is_null($request->password))
            $form['password'] = Hash::make($request->password);

        User::query()->where('id', '=', $request->id)->update($form);

        return back();
    }

    public function indexStaff()
    {
        $users = User::query()->select('id', 'name', 'email', 'jenis_kelamin')->where('role', '=', 'staff')->get();
        return Inertia::render('Staff/List', [
            'records' => $users
        ]);
    }

    public function createStaff(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'jenis_kelamin' => 'required'
        ]);

        $user = new User();
        $user->name = $request->nama;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'staff';
        $user->jenis_kelamin = $request->jenis_kelamin;
        $user->save();

        return back();
    }

    public function editStaff(Request $request)
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required'
        ];

        if(!is_null($request->password))
            $rules['password'] = ['confirmed', Rules\Password::defaults()];
        $request->validate($rules);

        $form = $request->except(['id', 'password', 'password_confirmation', 'nama']);
        $form['name'] = $request->nama;

        if(!is_null($request->password))
            $form['password'] = Hash::make($request->password);

        User::query()->where('id', '=', $request->id)->update($form);

        return back();
    }
}
