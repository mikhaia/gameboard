<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show($userId)
    {
      exit();
      /*
      return Inertia::render('Show', [
          'user' => Auth::user()
      ]);
      */
    }

    public function profile(Request $request) {
      $id = auth()->id();
      $rules = [
        'email' => 'required|email|unique:users,email,'.$id,
        'name' => 'required',
      ];
      if ($request->hasFile('avatar')) {
        $rules['avatar'] = 'image|mimes:jpg,jpeg,png|max:3072';
      }

      $data = $request->validate($rules);

      unset($data['avatar']);

      $model = User::find($id);
      // Avatar
      $this->changeImage('avatar', 100, 100, $request, $model, true);
      
      User::find($id)->update($data);
      return redirect()->back()->with('success', 'Your profile successfully updated!');
    }

    public function password(Request $request) {
      $id = auth()->id();
      $rules = [
        'old_password' => '',
        'new_password' => 'required|confirmed|min:6|max:15',
      ];
      $data = $request->validate($rules);

      if (!Hash::check($request->input('old_password'), Auth::user()->password)) {
        return redirect()->back()->withErrors(['msg'=>'Your old password doesn\'t match with your password']);
      }

      User::find($id)->update(['password' => bcrypt($data['new_password'])]);
      return redirect()->back()->with('success', 'Your password successfully updated!');
    }
}