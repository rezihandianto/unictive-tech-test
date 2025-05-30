<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
   public function index(Request $request)
    {
        $query = User::with('hobbies');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->paginate(5);
        return view('users.index', compact('users'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'hobbies' => 'array',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        foreach ($request->hobbies as $hobby) {
            if ($hobby) {
                $user->hobbies()->create(['name' => $hobby]);
            }
        }

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::with('hobbies')->findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        $user->hobbies()->delete();
        foreach ($request->hobbies as $hobby) {
            if ($hobby) {
                $user->hobbies()->create(['name' => $hobby]);
            }
        }

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
    }
}
