<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
</head>
<body>
	<form action="{{url('save')}}" method="post">
		第一节课：
			<select name="c_one">
				<option>php</option>
				<option>java</option>
				<option>web</option>
			</select>
		<br>
		第二节课：
			<select name="c_tow">
				<option>语文</option>
				<option>数学</option>
				<option>英语</option>
			</select>
		<br>
		第三节课：
			<select name="c_three">
				<option>语文</option>
				<option>数学</option>
				<option>英语</option>
			</select>
		<br>
		第四节课：
			<select name="c_four">
				<option>语文</option>
				<option>数学</option>
				<option>英语</option>
			</select>
		<br>
		<input type="submit" name="">
	</form>

	<table>
		<tr>
			<td>ID</td>
			<td>第一节课</td>
			<td>第二节课</td>
			<td>第三节课</td>
			<td>第四节课</td>
			<td>操作</td>
		</tr>
		@foreach($data as $v)
		<tr>
			<td>{{$v->c_id}}</td>
			<td>{{$v->c_one}}</td>
			<td>{{$v->c_tow}}</td>
			<td>{{$v->c_three}}</td>
			<td>{{$v->c_four}}</td>
			<td><a href="{{url('/edit/'.$v->c_id)}}">修改</a></td>
		</tr>
		@endforeach
	</table>

</body>
</html>