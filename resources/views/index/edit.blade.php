<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
</head>
<body>
	<form action="{{url('update/'.$data->c_id)}}" method="post">
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
</body>
</html>