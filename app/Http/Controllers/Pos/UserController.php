<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function UserAll(Request $request)
{
    if ($request->ajax()) {
        // Apply filtering based on the role if it's provided in the request
        $query = User::query();

        if ($request->has('role') && !empty($request->role)) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->get();

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $editUrl = route('user.edit', $row->id);
                $deleteUrl = route('user.delete', $row->id);
                return '
                    <a href="' . $editUrl . '" class="btn bg-warning btn-sm">
                        <i class="fas fa-edit" style="color: #ca8a04"></i>
                    </a>
                    <a href="' . $deleteUrl . '" class="btn bg-danger btn-sm">
                        <i class="fas fa-trash-alt text-danger"></i>
                    </a>
                ';
            })
            ->make(true);
    }

    // Fetch distinct roles from the users table for filtering options
    $roles = User::select('role')->distinct()->get();

    return view('backend.user.user_all', compact('roles'));
}


    public function UserAdd(){
        return view('backend.user.user_add');
    } // End Method

    public function UserStore(Request $request)
    {
        // Validasi input termasuk file
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'role' => 'required|in:admin,supervisor,pegawai',
            'image' => 'nullable|image|mimes:png|max:2048',
            'signature' => 'nullable|image|mimes:png|max:2048',
        ]);
    
        // Simpan user tanpa path file terlebih dahulu untuk mendapatkan ID user
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'role' => $request->role,
            'password' => Hash::make('password'),
        ]);
    
        // Proses upload file
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Menyimpan file di direktori yang benar
            $imagePath = $image->storeAs('public/backend/assets/images/users', 'foto_' . $user->id . '.png');
            // Mengupdate path relatif untuk disimpan di database
            $imagePath = str_replace('public/', '', $imagePath);
        } else {
            $imagePath = null;
        }
    
        if ($request->hasFile('signature')) {
            $signature = $request->file('signature');
            // Menyimpan file di direktori yang benar
            $signaturePath = $signature->storeAs('public/backend/assets/images/users', 'ttd_' . $user->id . '.png');
            // Mengupdate path relatif untuk disimpan di database
            $signaturePath = str_replace('public/', '', $signaturePath);
        } else {
            $signaturePath = null;
        }
    
        // Update user dengan path file
        $user->update([
            'foto' => $imagePath,
            'ttd' => $signaturePath,
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

    // Validate the input including files
    $request->validate([
        'role' => 'required|in:admin,supervisor,pegawai',
        'image' => 'nullable|image|mimes:png|max:2048',
        'signature' => 'nullable|image|mimes:png|max:2048',
    ]);

    // Update user role
    $user->update([
        'role' => strtolower($request->role),
    ]);

    // Handle file upload for photo
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagePath = $image->storeAs('public/assets/images/users', 'foto_' . $user->id . '.png');
        $user->update([
            'foto' => str_replace('public/', 'backend/', $imagePath)
        ]);
    }

    // Handle file upload for signature
    if ($request->hasFile('signature')) {
        $signature = $request->file('signature');
        $signaturePath = $signature->storeAs('public/assets/images/users', 'ttd_' . $user->id . '.png');
        $user->update([
            'ttd' => str_replace('public/', 'backend/', $signaturePath)
        ]);
    }

    $notification = array(
        'message' => 'Data pengguna berhasil diperbarui.',
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
