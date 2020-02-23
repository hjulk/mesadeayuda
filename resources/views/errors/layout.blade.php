<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css?family=Cabin:400,700" rel="stylesheet">
    <link rel="stylesheet" href="{{asset("assets/dist/css/error.css")}}">
</head>

<body>

	<div id="notfound">
		<div class="notfound">
			<div class="notfound-404">
				<div></div>
				<h1>@yield('code')</h1>
			</div>
			<h2>@yield('message')</h2>
			<a href="javascript:history.back()">Volver</a>
		</div>
	</div>

</body>

</html>
