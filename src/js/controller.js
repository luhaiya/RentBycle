app.controller('logininCtrl',function($scope,$http){
	$scope.login=function(){
		$scope.userInfo.cid = 10004;
		$http.post('./data/',$scope.userInfo)
		.success(function(data){
			if(data){
				window.uid = data[0].userid;
				window.token = data[0].token;
				window.tel = data[0].tel;
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
app.controller('topbarCtrl',function($scope){
	if(window.uid&&window.token){
		$scope.href = '#/self/selfinfo';
		$scope.userLogin = '个人中心';
	}else{
		$scope.href = '#/login';
		$scope.userLogin = '登陆';
	}
});
app.controller('bycleinfoCtrl',function($scope,$http,bycles){
	$scope.bycles = new bycles();
	$scope.rentTo = function(id){
		if(window.uid&&window.token){
			if(window.tel){
				$scope.request = {cid:10006,bycleid:id,uid:window.uid,token:window.token,tel:window.tel};
				$http.post('./data/',$scope.request)
				.success(function(data){
					if(data){
						alert("已经向车主发起请求，请等待！！！");
					}else{
						alert("请求失败");
					}
				})
				.error(function(data){alert("没有更多车辆信息");})
			}else{
				alert('请您先到个人中心填写一下手机号');
				window.location="#/self";
			}
		}else{
			alert("您请先从微信公众号登陆我们！");
		}
	}
});
app.controller('selfinfoCtrl',function($scope){
	$scope.usertype = '车主';
	$scope.tel = window.tel;
});
app.controller('setbycleinfoCtrl',function($scope,$http){
	$scope.grade = function(){
		if(window.uid&&window.token){
			var fd = new FormData();
			fd.append('img', $scope.mypic);
			fd.append('cid', 10000);
			fd.append('uid', window.uid);
			fd.append('token', window.token);
			fd.append('tags', $scope.mydesc);
			fd.append('price', $scope.myprice);
			$http({
				method:"post",
				url:"./data/",
				data:fd,
				headers: {'Content-Type':undefined},
				transformRequest: angular.identity
			})
			.success(function(data){
				if(data){alert("提交成功！");window.location.reload();}else{alert("提交失败1");}
			})
			.error(function(data){alert("提交失败2");})
		}else{
			alert("请从微信公众号登录");
		}
	}
});
app.controller('settingCtrl',function($scope,$http){
	$scope.modify = function(){
		if(window.uid){
			if($scope.userInfo.pwd==$scope.userInfo.sure_pwd){
				$scope.userInfo.cid = 10005;
				$scope.userInfo.uid = window.uid;
				$scope.userInfo.token = window.token;
				$http.post('./data/',$scope.userInfo)
				.success(function(data){
					if(data){alert("设置成功,请重新登录");window.location="#/login";}else{alert("设置失败");}
				})
				.error(function(data){alert("设置失败");})
			}else{
				alert("密码两次不匹配");
			}
		}else{
			alert("请您重新登录一下！");
		}
	}
});
app.controller("selfbycleinfoCtrl",function($scope,$http,selfbycles){
	$scope.selfbycles = new selfbycles();
});