<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function UserAll(){
        $users = User::latest()->get();
        return view('backend.user.user_all', compact('users'));
    } // End Method

    public function UserAdd(){
        return view('backend.user.user_add');
    } // End Method

    public function UserStore(Request $request){
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:admin,supervisor,pegawai',
        ]);

        // Menyimpan pengguna dengan password default
        User::create([
            'name' => $request->name,
            'role' => $request->role,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make('password'), // Password default
        ]);

        $notification = array(
            'message' => "Pengguna berhasil ditambahkan.",
            'alert-type' => "success"
        );

        return redirect()->route('user.all')->with($notification);
    }

    public function UserEdit($id){
        $user = User::findOrFail($id);
        return view('backend.user.user_edit', compact('user'));
    }

    public function UserUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'role' => strtolower($request->role),
        ]);

        $notification = array(
            'message' => 'Role pengguna berhasil diganti.',
            'alert-type' => 'success'
        );

        return redirect()->route('user.all')->with($notification);
    }

    public function UserDelete($id){
        user::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Pengguna berhasil dihapus',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
