<?php

namespace App\Http\Controllers\Web;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Artisan;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;

class UsersController extends Controller
{
    use ValidatesRequests;

    public function list(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('show_users')) abort(401);

        $query = User::select('*');

        if (auth()->user()->hasRole('Employee')) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', 'Customer');
            });
        }

        $query->when($request->keywords, fn($q) => $q->where("name", "like", "%$request->keywords%"));

        $users = $query->get();

        return view('users.list', compact('users'));
    }

    public function register(Request $request)
    {
        return view('users.register');
    }

    public function doRegister(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => ['required', 'string', 'min:5'],
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->input())->withErrors('Invalid registration information.');
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->credit = 0;
        $user->save();

        $user->assignRole('Customer');

        $title = "Verification Link";
        $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
        $link = route("verify", ['token' => $token]);
        Mail::to($user->email)->send(new VerificationEmail($link, $user->name));

        return redirect('/')->with('success', 'Account created! Please check your email to verify.');
    }

    public function login(Request $request)
    {
        return view('users.login');
    }

    public function doLogin(Request $request)
    {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');

        $user = User::where('email', $request->email)->first();
        if (!$user->email_verified_at)
            return redirect()->back()->withInput($request->input())
                ->withErrors('Your email is not verified.');

        return redirect('/');
    }

    public function doLogout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }

    public function profile(Request $request, User $user = null)
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return redirect('/login');
        }

        if (auth()->id() != $user->id) {
            if (!auth()->user()->hasPermissionTo('show_users')) {
                abort(401);
            }
        }

        $permissions = [];
        foreach ($user->permissions as $permission) {
            $permissions[] = $permission;
        }

        foreach ($user->roles as $role) {
            foreach ($role->permissions as $permission) {
                $permissions[] = $permission;
            }
        }

        return view('users.profile', compact('user', 'permissions'));
    }

    public function edit(Request $request, User $user = null)
    {
        $user = $user ?? auth()->user();
        if (auth()->id() != $user?->id) {
            if (!auth()->user()->hasPermissionTo('edit_users')) abort(401);
        }

        $roles = [];
        foreach (Role::all() as $role) {
            $role->taken = ($user->hasRole($role->name));
            $roles[] = $role;
        }

        $permissions = [];
        $directPermissionsIds = $user->permissions()->pluck('id')->toArray();
        foreach (Permission::all() as $permission) {
            $permission->taken = in_array($permission->id, $directPermissionsIds);
            $permissions[] = $permission;
        }

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function save(Request $request, User $user)
    {
        if (auth()->id() != $user->id) {
            if (!auth()->user()->hasPermissionTo('show_users')) abort(401);
        }

        $user->name = $request->name;
        $user->save();

        if (auth()->user()->hasPermissionTo('admin_users')) {
            $user->syncRoles($request->roles);
            $user->syncPermissions($request->permissions);
            Artisan::call('cache:clear');
        }

        return redirect(route('profile', ['user' => $user->id]));
    }

    public function delete(Request $request, User $user)
    {
        if (!auth()->user()->hasPermissionTo('delete_users')) abort(401);

        return redirect()->route('users');
    }

    public function editPassword(Request $request, User $user = null)
    {
        $user = $user ?? auth()->user();
        if (auth()->id() != $user?->id) {
            if (!auth()->user()->hasPermissionTo('edit_users')) abort(401);
        }

        return view('users.edit_password', compact('user'));
    }

    public function savePassword(Request $request, User $user)
    {
        if (auth()->id() == $user?->id) {
            $this->validate($request, [
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);

            if (!Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
                Auth::logout();
                return redirect('/');
            }
        } else if (!auth()->user()->hasPermissionTo('edit_users')) {
            abort(401);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return redirect(route('profile', ['user' => $user->id]));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'credit' => 0
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users')->with('success', 'Employee added!');
    }

    public function addCreditForm()
    {
        if (!auth()->user()->hasPermissionTo('add_credit')) abort(401);

        $customers = User::role('Customer')->get();

        return view('users.add_credit', compact('customers'));
    }

    public function storeCredit(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('add_credit')) abort(401);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $user = User::findOrFail($request->user_id);

        if (!$user->hasRole('Customer')) {
            return back()->withErrors('You can only add credit to customers.');
        }

        $user->credit += $request->amount;
        $user->save();

        return redirect()->route('users')->with('success', 'Credit added successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users')->with('success', 'User deleted successfully!');
    }

    public function verify(Request $request)
    {
        $decryptedData = json_decode(Crypt::decryptString($request->token), true);
        $user = User::find($decryptedData['id']);
        if (!$user) abort(401);
        $user->email_verified_at = Carbon::now();
        $user->save();
        return view('users.verified', compact('user'));
    }

    public function redirectToFacebook(Request $request)
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback(Request $request)
    {
        $userfacebook = Socialite::driver('facebook')->stateless()->user();
        $user = User::updateOrCreate(
            ['facebook_id' => $userfacebook->getId()],
            [
                'name' => $userfacebook->getName(),
                'email' => $userfacebook->getEmail(),
                'facebook_email' => $userfacebook->getEmail(),
            ]
        );

        Auth::login($user);
    }
}
