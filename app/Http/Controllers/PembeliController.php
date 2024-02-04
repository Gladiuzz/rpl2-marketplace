<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembeliController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pembeli = User::where('role', 'User')
            ->get();

        return view('admin.pembeli.index', compact('pembeli'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pembeli.manage');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => ['required'],
            'username' => ['required', 'unique:users,username'],
            'email' => ['required', 'unique:users,email', 'email'],
            'password' => ['required', 'min:6'],
            'no_telepon' => ['required', 'numeric'],
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $data = $request->except('_token');
        $data['password'] = bcrypt($data['password']);
        $data['role'] = 'User';
        $avatar = $request->file('avatar');


        if ($request->hasFile('avatar')) {
            $file_name = time() . "_" . $avatar->getClientOriginalName();
            $avatar->storeAs('public/user', $file_name);

            $data['avatar'] = $file_name;
            User::create($data);

            return redirect()->route('user-pembeli.index')->with('success', 'Pembeli Berhasil Ditambah');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pembeli = User::findorFail($id);

        return view('admin.pembeli.manage', compact('pembeli'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama' => ['required'],
            'username' => ['required', 'unique:users,username,' . $id],
            'email' => ['required', 'unique:users,email,' . $id, 'email'],
            'no_telepon' => ['required', 'numeric'],
            // 'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $pembeli = User::findorFail($id);

        $data = $request->except('_token');
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            $data['password'] = $pembeli->password;
        }

        $avatar = $request->file('avatar');


        if ($request->hasFile('avatar')) {
            $old_path = 'public/user/' . $pembeli->avatar;
            Storage::delete($old_path);

            $file_name = time() . "_" . $avatar->getClientOriginalName();
            $avatar->storeAs('public/user', $file_name);

            $data['avatar'] = $file_name;
        }

        $pembeli->update($data);

        return redirect()->route('user-pembeli.index')->with('success', 'Pembeli Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pembeli = User::findorFail($id);

        $avatar_path = 'public/user/' . $pembeli->avatar;
        Storage::delete($avatar_path);

        $pembeli->delete();

        return redirect()->route('user-pembeli.index')->with('success', 'Pembeli Berhasil Dihapus');
    }
}
