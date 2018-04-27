@extends("Home.foot")

@extends("Home.header")

@section('content')
	<style>
		.record-div{height: 400px;border: 1px solid #ccc;}
		.record-all{height: 330px;margin: 0px;overflow-y: scroll}
		.record{width:96%;margin:5px 2% 5px 2%;}
		.record h4{height: 30px;line-height: 30px;margin: 3px 0px 3px}
		.record p{text-indent: 30px}
		.user_one{color: #00a0e9}
		.user_two{color: #01C675}
		.show-tip{width: 100%;margin: 10px 0px 20px;text-align: center;height: 40px;line-height: 40px}
	</style>
<div class="main-body" style="width: 800px;margin: 0px auto">
	<div class="form-group record-div">
		<div class="record-all">

		</div>
		<div class="show-tip">
			<span>以上是历史消息</span>
		</div>
	</div>

	<form action="/doSendMsg" method="post" id="sendForm">
		<input type="hidden" type="text" name="_token" value="{{ csrf_token() }}">
		<input type="hidden" name="toUserId" value="{{$userInfo->id}}">
		<input type="hidden" name="messageType" value="1">
		<input type="hidden" name="pubserType" value="2">
	  	<div class="form-group">
        	<div class="input-group">
            	<input type="text" name="message" required="required" class="form-control">
            	<span class="input-group-btn">
                	<input type="button" class="btn btn-default sendmsg" value="发送">
            	</span>
        	</div>
	  	</div>
	</form>
</div>
@endsection