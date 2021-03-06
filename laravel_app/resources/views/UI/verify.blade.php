<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>MyApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://use.fontawesome.com/releases/v5.3.1/js/all.js" defer ></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
</head>
<body>
<div id="root" class="is-fullwidth">
    @if ($result == 'fail')
        <div>認証に失敗しました</div>
    @elseif ($result == 'success')
        <div>認証が完了しました</div>
        <a href="/mylogin">ログインページへ</a>
    @endif

</div>
</body>
</html>
