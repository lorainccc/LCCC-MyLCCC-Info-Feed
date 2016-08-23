//This function creates the Angular APP
var wpApp = new angular.module( 'wpAngularEventWidget', ['ui.router', 'ngResource'] );

//This function creates the Angular APP FEED
wpApp.factory( 'Posts', function( $resource ) {
	return $resource( appInfo.api_url + 'lccc_events/:ID', {
		ID: '@id'
	})
});

wpApp.factory( 'LCCC-Events', function( $resource ) {
	return $resource( 'https://test.lorainccc.edu/wp-json/wp/v2/lccc_events', {
		ID: '@id'
	})
});

wpApp.factory( 'LCCC--Athletic-Events', function( $resource ) {
	return $resource( 'https://test.lorainccc.edu/athletics/wp-json/wp/v2/lccc_events', {
		ID: '@id'
	})
});

wpApp.controller( 'ListCtrl', ['$scope', 'LCCC--Athletic-Events', function( $scope, Posts ) {
	console.log('ListCtrl');
	$scope.page_title = 'Blog Listing Page';

	Posts.query(function( res ) {
		$scope.posts = res;
	});
	
}]);

wpApp.controller( 'DetailCtrl', ['$scope', '$stateParams', 'Posts', function( $scope, $stateParams, Posts ) {
	console.log( $stateParams );
	Posts.get( { ID: $stateParams.id}, function(res){
		$scope.post = res;
	})
}])

//This routes the templates loaded based on the URL you are on
wpApp.config( function( $stateProvider, $urlRouterProvider){
	$urlRouterProvider.otherwise('/');
// The states below will load the controller based on what state the visitor is. The state is defined by the URl the visitor is on
	$stateProvider
		.state( 'home-list', {
			url: '/',
			controller: 'ListCtrl',
			templateUrl: appInfo.template_directory + 'templates/list.html'
		})
		.state( 'detail', {
			url: '/posts/:id',
			controller: 'DetailCtrl',
			templateUrl: appInfo.template_directory + 'templates/detail.html'
		})
});

wpApp.filter( 'to_trusted', ['$sce', function( $sce ){
	return function( text ) {
		return $sce.trustAsHtml( text );
	}
}])