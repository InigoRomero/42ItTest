<html>
	<head>
		<title>Oauth2</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <style>
			table {
				font-family: arial, sans-serif;
				border-collapse: collapse;
				width: 100%;
			}
			
			td, th {
				border: 1px solid #dddddd;
				text-align: left;
				padding: 8px;
			}
			
			tr:nth-child(even) {
				background-color: #dddddd;
			}
		  </style>
	</head>
	<body>
	<div class="content text-center">
			<h1>¿42 Nos espía?</h1>
			<img src="https://raw.githubusercontent.com/InigoRomero/42ItTest/main/laravelPHP/captures/icon.png?token=AK5DQM4B34JJO3I3QVVI7ODATKWCU" widt=120 height=80>
			<p>by Iromero-</p>
		</div>
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-6 content text-center">
				<h3>¿Quieres saberlo todo de otro estudiante?</h3>
				<form role="form" method="post" action="/request">
					<div class="form-group">
						<input type="text" class="form-control" name="request" id="request">
					</div>
					<button type="submit" class="btn btn-default">Buscar</button>
				</form>
			</div>
			<div class="col-md-3"></div>
		</div>
		<div class="row">
			<div class="col-md-1"></div>
			<div class="col-md-2">
				<h3>SOBRE TÍ</h3>
				<img src="{{ $me['image_url'] }}" alt="your photo" width="200" height="300"> </img>
			</div>
			<div class="col-md-3">
				<br></br>
				<table>
					<tr> <td>id</td> <td>{{ $me["id"] }}</td> </tr>
					<tr> <td>email</td> <td>{{ $me["email"] }} </td> </tr>
					<tr> <td>login</td> <td>{{ $me["login"] }}</td>  </tr>
					<tr> <td>first_name</td> <td>{{ $me["first_name"] }}</td> </tr>
					<tr> <td>last_name</td> <td>{{ $me["last_name"] }}</td> </tr>
					<tr> <td>usual_first_name</td> <td>{{ $me["usual_first_name"] }}</td> </tr>
					<tr> <td>url</td> <td> <a href="{{'https://profile.intra.42.fr/users/' . $me['login'] }}" target="blanck">Intra</a></td> </tr>
					<tr> <td>phone</td> <td>{{ $me["phone"] }}</td> </tr>
				</table>
			</div>
			<div class="col-md-3">
				<br></br> 
				<table>
					<tr> <td>displayname </td> <td>{{ $me["displayname"] }}</td> </tr>
					<tr> <td>usual_full_name </td> <td>{{ $me["usual_full_name"] }}</td> </tr>
					<tr> <td>correction_point </td> <td>{{ $me["correction_point"] }}</td> </tr>
					<tr> <td>pool_month </td> <td>{{ $me["pool_month"] }}</td> </tr>
					<tr> <td>pool_year </td> <td>{{ $me["pool_year"] }}</td> </tr>
					<tr> <td>location </td> <td>{{ $me["location"] }}</td> </tr>
					<tr> <td>wallet </td> <td>{{ $me["wallet"] }}</td> </tr>
				</table>
			</div>
		</div>
	<br></br>
    @foreach($me["cursus_users"] as $cursus)
		<div class="row">
			<div class="col-md-1"></div>
			<div class="col-md-2">
				<h4>CURSUS {{ $cursus["cursus"]["name"] }}</h4>
				<table>
					<tr><td>Level</td> <td>{{ $cursus["level"] }}</td></tr>
					<tr><td>begin_at</td> <td>{{ $cursus["begin_at"] }}</td></tr>
					<tr><td>grade</td> <td>{{ $cursus["grade"] }}</td></tr>
					<tr><td>blackholed_at</td> <td>{{ $cursus["blackholed_at"] }}</td></tr>
				</table>
			</div>
			<div class="col-md-4">
				<h4>SKILLS {{ $cursus["cursus"]["name"] }}</h4>
				<table>
					<tr>
						<th>Name</th>
						<th>Level</th>
					</tr>
					@foreach($cursus["skills"] as $skill)
						<tr>
							<td> {{ $skill["name"] }} </td>
							<td> {{ $skill["level"] }} </td>
						</tr>
					@endforeach
				</table>
			</div>
		</div>
	@endforeach



    <div class="row">
		<div class="col-md-1"></div>
		<div class="col-md-3">
			<h3>Tus Proyectos</h3>
			<table>
				<tr>
					<th>Name</th>
					<th>Final Mark</th>
					<th>Status</th>
				</tr>
				@foreach($me["projects_users"] as $project)
					<tr>
						<td> {{ $project["project"]["name"] }} </td>
						<td> {{ $project["final_mark"] }} </td> 
						<td> {{ $project["status"] }} </td> 
					</tr>
				@endforeach
			</table>
		</div>
		<div class="col-md-7">
			<h3>Tus Logros</h3>
			<table>
				<tr>
					<th>Logo</th>
					<th>name</th>
					<th>description</th>
					<th>tier</th>
					<th>kind</th>
				</tr>
				@foreach($me["achievements"] as $achievement)
					<tr>
						<td> <img src="https://profile.intra.42.fr/{{ $achievement["image"] }}" alt="logo" width="100" height="70"> </img> </td>
						<td> {{ $achievement["name"] }} </td>
						<td> {{ $achievement["description"] }} </td>
						<td> {{ $achievement["tier"] }} </td>
						<td> {{ $achievement["kind"] }} </td>
					</tr>
					@endforeach
			</table>
		</div>
	</div>
</body>
</html>
