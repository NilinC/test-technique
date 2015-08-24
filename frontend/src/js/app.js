angular.module('testTechnique', [])
    .controller('ToDoListController', function() {
        var todoslist = this;
        todoslist.todos = [
            { text: 'Tâche 1', done: true },
            { text: 'Tâche 2', done: false },
            { text: 'Tâche 3', done: false },
            { text: 'Tâche 4', done: false }
        ];

        todoslist.todoChecked = function(todo) {
            todo.done = !todo.done;
        };

        todoslist.addNewTodo = function() {
            todoslist.todos.push({ text: todoslist.todoText, done: false });
            todoslist.todoText = '';
        };

        todoslist.remaining = function() {
            var counter = 0;
            angular.forEach(todoslist.todos, function(todos) {
                counter += todos.done ? 0 : 1;
            });
            return counter;
        };
    });
