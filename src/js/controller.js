app.controller('logininCtrl',function($scope,$http){
	$scope.login=function(){
		$scope.userInfo.cid = 10004;
		$http.post('./data/',$scope.userInfo)
		.success(function(data){
			if(data){
				window.uid = data[0].userid;
				window.token = data[0].token;
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
	if(window.uid==0||window.token==''){
		$scope.href = '#/login';
		$scope.userLogin = '登陆';
	}else{
		$scope.href = '#/rent';
		$scope.userLogin = '个人中心';
	}
});
app.controller('bycleinfoCtrl',function($scope){
	$scope.brandList = ['品牌1','品牌2','品牌3'];
	$scope.typeList = ['样式1','样式2','样式3'];
	$scope.choices = '';
	$scope.selectBrand = function(index){
		$scope.choices1 = $scope.brandList[index];
	}
	$scope.selectType = function(index){
		$scope.choices2 = $scope.typeList[index];
	}
});