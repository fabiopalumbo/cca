'use strict';

angular.module('newProject')
    .service('translationService', function($http) {
        this.getTranslation = function($scope, language) {
            var languageFilePath = '/assets/json/translations/translation_'+language+'.json';
            /*
            $resource(languageFilePath).get(function (data) {
                $scope.translation = data;
            });
            */
            $http.get(languageFilePath)
                .then(function(res){
                    $scope.translation = res.data;
                });

        };
});
