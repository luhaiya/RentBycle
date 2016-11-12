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
	if(window.uid==0||window.token==''){
		$scope.href = '#/login';
		$scope.userLogin = '登陆';
	}else{
		$scope.href = '#/self';
		$scope.userLogin = '个人中心';
	}
});
app.controller('bycleinfoCtrl',function($scope,$http){
	$scope.brandList = ['品牌1','品牌2','品牌3'];
	$scope.typeList = ['样式1','样式2','样式3'];
	$scope.bycleList = [
		{id:1,src:'./src/imgs/by1.jpg',desc:'邦德富士达21速自行车山地车禧玛诺铝合金车架双线碟刹26寸悦动S7 S7白蓝',price:'￥699.00'},
		{id:2,src:'./src/imgs/by2.jpg',desc:'邦德富士达21速自行车山地车禧玛诺铝合金车架双线碟刹26寸悦动S7 S7白蓝',price:'￥699.00'},
		{id:3,src:'./src/imgs/by3.jpg',desc:'邦德富士达21速自行车山地车禧玛诺铝合金车架双线碟刹26寸悦动S7 S7白蓝',price:'￥699.00'},
		{id:4,src:'./src/imgs/by3.jpg',desc:'邦德富士达21速自行车山地车禧玛诺铝合金车架双线碟刹26寸悦动S7 S7白蓝',price:'￥699.00'},
	];
	$scope.choices = '';
	$scope.selectBrand = function(index){
		$scope.choices1 = $scope.brandList[index];
	}
	$scope.selectType = function(index){
		$scope.choices2 = $scope.typeList[index];
	}
	$scope.rentTo = function(id){
		if(window.uid&&window.token){
			if(window.tel){
				alert('已经给车主发送请求了，请等待回电');
			}else{
				alert('请您先到个人中心填写一下手机号');
			}
		}else{
			alert("您请先从微信公众号登陆我们！");
		}
	}
	
});
app.controller('selfCtrl',function($scope,$http){
	$scope.modify = function(){
		if(window.uid){
			if($scope.userInfo.pwd==$scope.userInfo.sure_pwd){
				$scope.userInfo.cid = 10005;
				$scope.userInfo.uid = window.uid;
				$scope.userInfo.token = window.token;
				$http.post('./data/',$scope.userInfo)
				.success(function(data){
					if(data){alert("设置成功");}else{alert("设置失败");}
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