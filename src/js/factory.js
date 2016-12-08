app.factory("bycles",function($http){
	var bycles = function(){
		this.bycleList = [];
        this.loading = false;
        this.keyword = "";
		this.page = 0;
	};
	bycles.prototype.search = function(){
		this.keyword = bycles.keyword;
		this.page = 0;
		var reqbycle = new FormData();
		reqbycle.append('cid', 10001);
		reqbycle.append('uid', window.uid);
		reqbycle.append('token', window.token);
		reqbycle.append('page', this.page);
		reqbycle.append('keyword', this.keyword);
		$http({
			method:"post",
			url:"./data/",
			data:reqbycle,
			headers: {'Content-Type':undefined},
			transformRequest: angular.identity
		})
		.success(function(data){
			this.bycleList = data;
			this.loading = false;
			this.page += 1;
		}.bind(this));
	}
	bycles.prototype.loadmore = function(){
		if (this.loading) return;
		this.loading = true;
		var reqbycle = new FormData();
		reqbycle.append('cid', 10001);
		reqbycle.append('uid', window.uid);
		reqbycle.append('token', window.token);
		reqbycle.append('page', this.page);
		reqbycle.append('keyword', this.keyword);
		$http({
			method:"post",
			url:"./data/",
			data:reqbycle,
			headers: {'Content-Type':undefined},
			transformRequest: angular.identity
		})
		.success(function(data){
			this.bycleList = data;
			this.loading = false;
			this.page += 1;
		}.bind(this));
	};
	return bycles;
});

app.factory("selfbycles",function($http){
	var selfbycles = function(){
		this.bycleList = [];
		this.page = 0;
		if(this.page==0){
			this.page = 1;
			var reqbycle = new FormData();
			reqbycle.append('cid', 10008);
			reqbycle.append('uid', window.uid);
			reqbycle.append('token', window.token);
			reqbycle.append('type', 0);
			$http({
				method:"post",
				url:"./data/",
				data:reqbycle,
				headers: {'Content-Type':undefined},
				transformRequest: angular.identity
			})
			.success(function(data){
				this.bycleList = data;
			}.bind(this));
		}
	};
	selfbycles.prototype.prePage = function(){
		var reqbycle = new FormData();
		reqbycle.append('cid', 10008);
		reqbycle.append('uid', window.uid);
		reqbycle.append('token', window.token);
		reqbycle.append('type', 1);
		$http({
			method:"post",
			url:"./data/",
			data:reqbycle,
			headers: {'Content-Type':undefined},
			transformRequest: angular.identity
		})
		.success(function(data){
			this.bycleList = data;
		}.bind(this));
	};
	selfbycles.prototype.nextPage = function(){
		var reqbycle = new FormData();
		reqbycle.append('cid', 10008);
		reqbycle.append('uid', window.uid);
		reqbycle.append('token', window.token);
		reqbycle.append('type', 2);
		$http({
			method:"post",
			url:"./data/",
			data:reqbycle,
			headers: {'Content-Type':undefined},
			transformRequest: angular.identity
		})
		.success(function(data){
			this.bycleList = data;
		}.bind(this));
	};
	selfbycles.prototype.change = function(id){
		var reqbycle = new FormData();
		reqbycle.append('cid', 10007);
		reqbycle.append('uid', window.uid);
		reqbycle.append('token', window.token);
		reqbycle.append('bycleid', id);
		$http({
			method:"post",
			url:"./data/",
			data:reqbycle,
			headers: {'Content-Type':undefined},
			transformRequest: angular.identity
		})
		.success(function(data){
			//this.bycleList = data;
			location.reload();
		}.bind(this));
	};
	return selfbycles;
});