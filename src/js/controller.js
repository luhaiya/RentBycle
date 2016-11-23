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
		$scope.href = '#/self';
		$scope.userLogin = '个人中心';
	}else{
		$scope.href = '#/login';
		$scope.userLogin = '登陆';
	}
});
app.controller('bycleinfoCtrl',function($scope,$http){
	$scope.brandList = ['品牌1','品牌2','品牌3'];
	$scope.typeList = ['样式1','样式2','样式3'];
	$scope.reqbycle = {cid:10001,uid:window.uid,token:window.token,next:0};
	$http.post('./data/',$scope.reqbycle)
	.success(function(data){
		if(data){
			$scope.bycleList = data;
		}else{
			alert("没有车辆信息");
		}
	})
	.error(function(data){alert("没有车辆信息");})
	$scope.choices = '';
	$scope.selectBrand = function(index){
		$scope.choices1 = $scope.brandList[index];
	}
	$scope.selectType = function(index){
		$scope.choices2 = $scope.typeList[index];
	}
	$scope.loadmore = function(){
		$scope.reqbycle = {cid:10001,uid:window.uid,token:window.token,next:1};
		$http.post('./data/',$scope.reqbycle)
		.success(function(data){
			if(data){
				$scope.bycleList = data;
			}else{
				alert("没有更多车辆信息");
			}
		})
		.error(function(data){alert("没有更多车辆信息");})
		.then(function(data){});
	}
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
app.controller('selfCtrl',function($scope,$http){
	$scope.usertype = '车主';
	$scope.tel = window.tel;
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