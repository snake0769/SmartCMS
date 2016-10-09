<form method="POST" action="{{URL::to('/admin/auth/register')}}">
    {!! csrf_field() !!}

    <div>
        UserName
        <input type="text" name="username" value="{{ old('username') }}">
    </div>

    <div>
        Nickname
        <input type="text" name="nickname" value="{{ old('nickname') }}">
    </div>

    <div>
        Email
        <input type="email" name="email" value="{{ old('email') }}">
    </div>

    <div>
        Password
        <input type="password" name="password">
    </div>

    <div>
        Confirm Password
        <input type="password" name="password_confirmation">
    </div>

    <div>
        <button type="submit">Register</button>
    </div>

    <div>
        @if (count($errors) > 0)
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</form>