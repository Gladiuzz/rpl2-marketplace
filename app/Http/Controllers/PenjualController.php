<?php

namespace App\Http\Controllers;

use App\Models\Penjual;
use App\Models\User;
use Illuminate\Http\Request;

class PenjualController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $penjual = Penjual::all();

        return view('admin.penjual.index', compact('penjual'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::where('role', '!=', 'Admin')
            ->doesntHave('penjual')
            ->get();

        return view('admin.penjual.manage', compact('user'));
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
            'id_user' => ['required'],
            'nama_toko' => ['required'],
            'alamat_toko' => ['required'],
        ]);

        $data = $request->except('_token');
        $user = User::where('id', $data['id_user'])->first();
        $penjual = Penjual::where('id_user', $user->id);

        if ($penjual->exists() == true) {
            return redirect()->route('user-penjual.index')->with('error', 'User tersebut sudah memiliki toko');
        }

        Penjual::create($data);

        if ($user->role != 'Admin') {
            $user->role = 'Penjual';
            $user->update();
        }

        return redirect()->route('user-penjual.index')->with('success', 'User ' . $user->nama . ' Berhasil Menjadi penjual');
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
        $user = User::where('role', '!=', 'Admin')
            ->get();
        $penjual = Penjual::findorFail($id);

        $data = array(
            'user' => $user,
            'penjual' => $penjual,
        );

        return view('admin.penjual.manage', $data);
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
            'id_user' => ['required'],
            'nama_toko' => ['required'],
            'alamat_toko' => ['required'],
        ]);

        $data = $request->except('_token');
        $penjual = Penjual::findorFail($id);

        $penjual->update($data);

        return redirect()->route('user-penjual.index')->with('success', 'toko Penjual ' . $penjual->user->nama . ' Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $penjual = Penjual::findorFail($id);
        $user = User::findorFail($penjual->id_user);

        if ($user->role != 'Admin') {
            $user->role = 'Pembeli';
            $user->update();
        }

        $penjual->delete();

        return redirect()->route('user-penjual.index')->with('success', 'toko Penjual ' . $user->nama . ' Berhasil Dihapus');
    }
}
