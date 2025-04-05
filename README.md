$userTypes = ['admin' => 'Admin', 'staff' => 'Staff', 'user' => 'User'];
    return view('users.create', compact('userTypes'));
