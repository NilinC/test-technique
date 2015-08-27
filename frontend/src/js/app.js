angular.module('testTechnique', [])
    .controller('ToDoListController', function($http) {
        var todoslist = this;
        todoslist.todos = [];

        $http.get('http://localhost:8000/api/tasks').
            then(function(response) {
                todoslist.todos = response.data;
            }, function() {
                alert('An error occurred');
            });

        todoslist.todoChecked = function(todo) {
            $http.patch('http://localhost:8000/api/tasks/'+ todo.id +'/check', {}).
                then(function() {
                    todo.done = !todo.done;
                }, function() {
                    alert('An error occurred');
                });
        };

        todoslist.addNewTodo = function() {
            $http.post('http://localhost:8000/api/tasks', { label: todoslist.todoText, done: 0 }).
                then(function(response) {
                    todoslist.todos.push(response.data);
                    todoslist.todoText = '';
                }, function() {
                    alert('An error occurred');
                });

        };

        todoslist.remaining = function() {
            var counter = 0;
            angular.forEach(todoslist.todos, function(todos) {
                counter += todos.done ? 0 : 1;
            });
            return counter;
        };
    });
