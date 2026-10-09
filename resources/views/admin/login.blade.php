<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login — Gifts&Mo Ug</title>
<style>
  * { box-sizing: border-box; }
  body { margin: 0; font-family: -apple-system, Segoe UI, Roboto, sans-serif; background: #f4f4f6; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
  .box { background: #fff; padding: 28px 26px; border-radius: 12px; width: 100%; max-width: 340px; }
  h1 { font-size: 1.2rem; margin-top: 0; }
  label { display: block; font-size: 0.85rem; font-weight: 600; margin: 14px 0 4px; }
  input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem; }
  button { width: 100%; margin-top: 20px; padding: 12px; background: #d6336c; color: #fff; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; }
  .error-box { background: #fdecea; color: #c0392b; padding: 10px 14px; border-radius: 8px; margin-bottom: 10px; font-size: 0.85rem; }
</style>
</head>
<body>
<div class="box">
  <h1>Gifts&Mo Ug — Admin</h1>

  @if($errors->any())
    <div class="error-box">
      @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
    </div>
  @endif

  <form method="POST" action="{{ route('admin.login.attempt') }}">
    @csrf
    <label>Username</label>
    <input type="text" name="username" value="{{ old('username') }}" required autofocus>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit">Log in</button>
  </form>
</div>
</body>
</html>
