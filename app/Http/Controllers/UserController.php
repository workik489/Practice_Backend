<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserCacheService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct(private UserCacheService $userCache)
    {
    }

    // GET ALL USERS
    public function index(Request $request)
    {
        if ($this->shouldReturnJson($request)) {
            return response()->json($this->userCache->apiUsers());
        }

        $sort = $request->string('sort', 'created_at')->toString();
        $direction = $request->string('direction', 'desc')->toString() === 'asc' ? 'asc' : 'desc';
        $search = trim($request->string('search')->toString());
        $allowedSorts = ['name', 'email', 'username', 'address', 'created_at'];
        $sort = in_array($sort, $allowedSorts, true) ? $sort : 'created_at';

        $allUsers = collect($this->userCache->webUsers());

        if ($search !== '') {
            $needle = mb_strtolower($search);
            $allUsers = $allUsers->filter(function (array $user) use ($needle) {
                return str_contains(mb_strtolower($user['name'] ?? ''), $needle)
                    || str_contains(mb_strtolower($user['email'] ?? ''), $needle)
                    || str_contains(mb_strtolower($user['username'] ?? ''), $needle)
                    || str_contains(mb_strtolower($user['address'] ?? ''), $needle);
            });
        }

        $allUsers = $allUsers
            ->sortBy(fn (array $user) => mb_strtolower((string) ($user[$sort] ?? '')), SORT_REGULAR, $direction === 'desc')
            ->values();

        $perPage = in_array($request->integer('per_page', 10), [10, 25, 50, 100], true)
            ? $request->integer('per_page', 10)
            : 10;
        $page = max(1, $request->integer('page', 1));
        $users = new LengthAwarePaginator(
            $allUsers->forPage($page, $perPage)->values(),
            $allUsers->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('users.index', compact('users', 'search', 'sort', 'direction', 'perPage'));
    }

    public function create()
    {
        return view('users.create');
    }

    // CREATE USER
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'username' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'] ?? null,
            'password' => Hash::make($validated['password']),
            'address' => $validated['address'] ?? null,
        ]);

        if (! $this->shouldReturnJson($request)) {
            return redirect()->route('users.index')->with('status', 'User created successfully.');
        }

        return response()->json([
            'message' => 'User created',
            'data' => $user
        ]);
    }

    // GET SINGLE USER
    public function show($id)
    {
        return response()->json(User::find($id));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // UPDATE USER
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $validated = $request->validate($this->shouldReturnJson($request) ? [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'username' => ['sometimes', 'nullable', 'string', 'max:255'],
            'password' => ['sometimes', 'nullable', 'string', 'min:8'],
            'address' => ['sometimes', 'nullable', 'string', 'max:255'],
        ] : [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'username' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $data = [
            'name' => $validated['name'] ?? $user->name,
            'email' => $validated['email'] ?? $user->email,
            'username' => array_key_exists('username', $validated) ? $validated['username'] : $user->username,
            'address' => array_key_exists('address', $validated) ? $validated['address'] : $user->address,
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        if (! $this->shouldReturnJson($request)) {
            return redirect()->route('users.index')->with('status', 'User updated successfully.');
        }

        return response()->json([
            'message' => 'User updated',
            'data' => $user
        ]);
    }

    // DELETE USER
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        if (! $this->shouldReturnJson(request())) {
            return redirect()->route('users.index')->with('status', 'User deleted successfully.');
        }

        return response()->json(['message' => 'User deleted']);
    }

    private function shouldReturnJson(Request $request): bool
    {
        return $request->is('api/*') || $request->expectsJson();
    }
}
