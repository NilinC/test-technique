angular.module('testTechnique', [])
    .controller('ToDoListController', function($http) {
        var todoslist = this;
        var url = 'http://localhost:8000';
        todoslist.todos = [];

        // Display the todolist
        $http.get(url + '/api/tasks').
            then(function(response) {
                todoslist.todos = response.data;
            }, function() {
                alert('An error occurred');
            });

        /**
         * Toggle from done to undone and vice versa
         * @param task
         */
        todoslist.taskChecked = function(task) {
            $http.patch(url + '/api/tasks/'+ task.id +'/check', {}).
                then(function() {
                    task.done = !task.done;
                }, function() {
                    alert('An error occurred');
                });
        };

        /**
         * Add a new task to the todolist
         */
        todoslist.addNewTask = function() {
            $http.post(url + '/api/tasks', { label: todoslist.taskText, done: 0 }).
                then(function(response) {
                    todoslist.todos.push(response.data);
                    todoslist.taskText = '';
                }, function() {
                    alert('An error occurred');
                });

        };

        /**
         * Update a task label
         * @param task
         */
        todoslist.updateTask = function(task) {
            $http.put(url + '/api/tasks/' + task.id, { label: task.label, done: task.done ? 1 : 0 }).
                then(function() {
                    task.updating = false;
                }, function() {
                    alert('An error occurred');
                });
        };

        /**
         * Delete a task from the todolist
         * @param task
         */
        todoslist.deleteTask = function(task) {
            $http.delete(url + '/api/tasks/' + task.id).
                then(function() {
                    var index = todoslist.todos.indexOf(task);
                    todoslist.todos.splice(index, 1);
            }, function() {
                alert('An error occurred');
            });
        };

        /**
         * Count how tasks to do left
         * @returns {number}
         */
        todoslist.remaining = function() {
            var counter = 0;
            angular.forEach(todoslist.todos, function(todos) {
                counter += todos.done ? 0 : 1;
            });
            return counter;
        };
    });
