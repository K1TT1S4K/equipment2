@forelse ($users as $user)
    <tr>
        <td><input type="checkbox" name="selected_users[]" value="{{ $user->id }}" class="user-checkbox"></td>
        <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
        <td>{{ $user->username }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->firstname }} {{ $user->lastname }}</td>
        <td>{{ $user->user_type }}</td>
        <td>{{ $user->deleted_at->format('d/m/Y') }}</td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center text-muted">ไม่พบผู้ใช้</td>
    </tr>
@endforelse
