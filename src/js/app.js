var app=angular.module("buptBycle",['ui.router','infinite-scroll']);

app.config(function ($stateProvider, $urlRouterProvider) {
    $urlRouterProvider.otherwise('/rent');
    $stateProvider
        .state("home", {
            url: '/home',
            views: {
                '': {
                    templateUrl: "template/home.html"
                },
                'topbar@home': {
                    templateUrl: "template/topbar.html"
                },
                'lunbopic@home': {
                    templateUrl: "template/lunbopic.html"
                }
            }
        })
        .state('rent', {
            url: '/rent',
            views: {
		        '': {
		            templateUrl: "template/rent.html"
		        },
                'topbar@rent': {
                    templateUrl: 'template/topbar.html'
                },
                'bycleInfo@rent': {
                    templateUrl: 'template/bycleInfo.html'
                },
            }
        })
        .state('login',{
        	url:'/login',
        	views:{
        		'':{
        			templateUrl: "template/loginin.html"
        		},
                'topbar@login':{
                    templateUrl: "template/topbar.html"
                }
        	}
        })
        .state('about',{
        	url:'/about',
        	views:{
        		'':{
        			templateUrl: "template/about.html"
        		},
                'topbar@about':{
                    templateUrl: "template/topbar.html"
                },
                'descUs@about':{
                    templateUrl: "template/descUs.html"
                }
        	}
        })
        .state('self',{
        	url:'/self',
        	views:{
        		'':{
        			templateUrl: "template/self.html"
        		},
                'topbar@self':{
                    templateUrl: "template/topbar.html"
                }
        	}
        })
        .state('self.selfinfo',{
        	url:'/selfinfo',
        	templateUrl:"template/selfinfo.html"
        })
        .state('self.setbycleinfo',{
        	url:'/setbycleinfo',
        	templateUrl:"template/setbycleinfo.html"
        })
        .state('self.setting',{
        	url:'/setting',
        	templateUrl:"template/setting.html"
        })
});