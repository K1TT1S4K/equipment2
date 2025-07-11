@if($users->count() > 0)
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Username</th>
            <th>ชื่อ</th>
            <th>นามสกุล</th>
            <th>อีเมล</th>
            <th>ระดับผู้ใช้</th>
            <th>คำนำหน้า</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->username }}</td>
            <td>{{ $user->firstname }}</td>
            <td>{{ $user->lastname }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->user_type }}</td>
            <td>{{ $user->prefix->name ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- แสดง pagination --}}
<div>
    {!! $users->links('pagination::bootstrap-5') !!}
</div>

@else
<p>ไม่พบข้อมูลผู้ใช้</p>
@endif
