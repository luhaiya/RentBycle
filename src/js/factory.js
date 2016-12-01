app.factory("bycles",function($http){
	var bycles = function(){
		this.bycleList = [];
        this.loading = false;
		this.page = 0;
	};
	bycles.prototype.loadmore = function(){
		if (this.loading) return;
		this.loading = true;
		var reqbycle = new FormData();
		reqbycle.append('cid', 10001);
		reqbycle.append('uid', window.uid);
		reqbycle.append('token', window.token);
		reqbycle.append('page', this.page);
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