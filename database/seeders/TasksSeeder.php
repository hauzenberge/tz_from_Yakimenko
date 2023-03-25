<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Task;
use App\Models\Subtask;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $task1 = Task::create([
            'user_id' => 1, 
            'title' => 'Complete project',
            'due_date' => '2023-04-30',
            'status' => 'in_progress'
        ]);

        Subtask::insert([
            [
                'task_id' => $task1->id,
                'title' => 'Research',
                'description' => 'Research about the project requirements and constraints',
                'due_date' => '2023-04-05',
                'status' => 'completed'
            ],
            [
                'task_id' => $task1->id,
                'title' => 'Design',
                'description' => 'Design the architecture of the project',
                'due_date' => '2023-04-10',
                'status' => 'in_progress'
            ],
            [
                'task_id' => $task1->id,
                'title' => 'Development',
                'description' => 'Develop the project based on the requirements and design',
                'due_date' => '2023-04-25',
                'status' => 'not_started'
            ]
        ]);

        $task2 = Task::create([
            'user_id' => 1,
            'title' => 'Prepare presentation',
            'due_date' => '2023-05-15',
            'status' => 'not_started'
        ]);

        Subtask::insert([
            [
                'task_id' => $task2->id,
                'title' => 'Outline presentation',
                'description' => 'Prepare an outline for the presentation',
                'due_date' => '2023-05-01',
                'status' => 'not_started'
            ],
            [
                'task_id' => $task2->id,
                'title' => 'Prepare slides',
                'description' => 'Create the slides for the presentation',
                'due_date' => '2023-05-08',
                'status' => 'not_started'
            ],
            [
                'task_id' => $task2->id,
                'title' => 'Practice presentation',
                'description' => 'Practice presenting the slides for the presentation',
                'due_date' => '2023-05-12',
                'status' => 'not_started'
            ]
        ]);
    }
}
