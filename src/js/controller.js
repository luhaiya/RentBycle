app.controller('signinCtrl',function($scope,$http){
	$scope.signin=function(){
		$scope.userInfo.cid = 10005;
		$http.post('./data/',$scope.userInfo)
		.success(function(data){
			if(data){
				alert('您已成功注册，可以去登录了！');
				window.location = '#/login';
			}else{
				alert('注册失败');
			}
		})
		.error(function(data){
			alert('注册失败');
		})
	}
})
app.controller('logininCtrl',function($scope,$http){
	$scope.login=function(){
		$scope.userInfo.cid = 10004;
		$http.post('./data/',$scope.userInfo)
		.success(function(data){
			if(data){
				window.location = '#/rent';
			}else{
				alert('登录失败');
			}
		})
		.error(function(data){
			alert('登录失败');
		})
	}
});