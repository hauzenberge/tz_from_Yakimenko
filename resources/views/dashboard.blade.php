<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}

        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>

                <div id="app">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-8 mx-auto">
                                <h1 class="text-center mb-5">Task Manager</h1>

                                <!-- Display a list of tasks -->
                                <div v-for="(task, index) in tasks" :key="task.id" class="card mb-3">
                                    <div class="card-body">
                                        <h3 class="card-title">@{{ task.title }}</h3>
                                        <p class="card-text">@{{ task.description }}</p>

                                        <!-- Display a list of subtasks for each task -->
                                        <ul class="list-group mt-3">
                                            <li v-for="(subtask, index) in task.subtasks" :key="subtask.id" class="list-group-item">
                                                @{{ subtask.title }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Add a new task form -->
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h3 class="card-title">Add a New Task</h3>

                                        <form @submit.prevent="addTask">
                                            <div class="form-group">
                                                <label for="title">Title</label>
                                                <input type="text" class="form-control" id="title" v-model="newTask.title" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea class="form-control" id="description" v-model="newTask.description" rows="3" required></textarea>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Add Task</button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>


    </div>


    

    <script>
        const BASE_URL = '<?php echo url('/api/') ?>';
        const USER_ID = '<?php echo Auth::user()->id ?>';;

        new Vue({
                    el: '#app',
                    data: {
                        tasks: [],
                        newTask: {
                            title: '',
                            description: ''
                        },
                        editingTaskId: null,
                        editingSubtaskId: null,
                        editingTask: {
                            title: '',
                            description: ''
                        },
                        editingSubtask: {
                            title: '',
                            completed: false
                        },
                        subtasks: [],
                        newSubtask: {
                            title: '',
                            completed: false
                        }
                    },
                    created() {
                        this.fetchTasks();
                    },
                    methods: {
                        fetchTasks() {
                            axios
                                .get(`${BASE_URL}/tasks?user_id=${USER_ID}`)
                                .then(response => {
                                    this.tasks = response.data.data;
                                })
                                .catch(error => {
                                    console.log(error);
                                });
                        },
                        fetchSubtasks(taskId) {                          
                            axios
                                .get(`${BASE_URL}/tasks/${taskId}/subtasks`)
                                .then(response => {
                                    this.subtasks = response.data.data;
                                })
                                .catch(error => {
                                    console.log(error);
                                });
                        },
                        createTask() {
                            this.newTask.user_id = USER_ID;
                            axios
                                .post('${BASE_URL}/tasks', this.newTask)
                                .then(response => {
                                    this.tasks.push(response.data);
                                    this.newTask.title = '';
                                    this.newTask.description = '';
                                })
                                .catch(error => {
                                    console.log(error);
                                });
                        },
                        editTask(task) {
                            this.editingTaskId = task.id;
                            this.editingTask.title = task.title;
                            this.editingTask.description = task.description;
                        },
                        cancelEditTask() {
                            this.editingTaskId = null;
                            this.editingTask.title = '';
                            this.editingTask.description = '';
                        },
                        updateTask() {
                            axios
                                .put('${BASE_URL}/tasks/${this.editingTaskId}', this.editingTask)
                                .then(response => {
                                    const index = this.tasks.findIndex(task => task.id === response.data.id);
                                    this.tasks.splice(index, 1, response.data);
                                    this.editingTaskId = null;
                                    this.editingTask.title = '';
                                    this.editingTask.description = '';
                                })
                                .catch(error => {
                                    console.log(error);
                                });
                        },
                        deleteTask(taskId) {
                            axios
                                .delete('${BASE_URL}/tasks/${taskId}')
                                .then(() => {
                                    this.tasks = this.tasks.filter(task => task.id !== taskId);
                                })
                                .catch(error => {
                                    console.log(error);
                                });
                        },
                        createSubtask(taskId) {
                            this.newSubtask.task_id = taskId;
                            axios
                                .post('${BASE_URL}/tasks/${taskId}/subtasks', this.newSubtask)
                                .then(response => {
                                    this.subtasks.push(response.data);
                                    this.newSubtask.title = '';
                                    this.newSubtask.completed = false;
                                })
                                .catch(error => {
                                    console.log(error);
                                });
                        },
                        editSubtask(subtask) {
                            this.editingSubtaskId = subtask.id;
                            this.editingSubtask.title = subtask.title;
                            this.editingSubtask.description = subtask.description;
                        },
                        updateSubtask() {
                            axios
                                .put('${BASE_URL}/tasks/${this.selectedTask.id}/subtasks/${this.editingSubtaskId}', this.editingSubtask)
                                .then(response => {
                                    const index = this.selectedTask.subtasks.findIndex(subtask => subtask.id === response.data.id);
                                    this.selectedTask.subtasks.splice(index, 1, response.data);
                                    this.editingSubtaskId = null;
                                    this.editingSubtask.title = '';
                                    this.editingSubtask.description = '';
                                })
                                .catch(error => {
                                    console.log(error);
                                });
                        },
                        deleteSubtask(subtask) {
                            axios
                                .delete('${BASE_URL}/tasks/${this.selectedTask.id}/subtasks/${subtask.id}')
                                .then(response => {
                                    const index = this.selectedTask.subtasks.findIndex(s => s.id === subtask.id);
                                    this.selectedTask.subtasks.splice(index, 1);
                                })
                                .catch(error => {
                                    console.log(error);
                                });
                        },

                    }
                });
    </script>

</x-app-layout>