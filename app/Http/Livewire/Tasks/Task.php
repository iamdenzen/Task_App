<?php

namespace App\Http\Livewire\Tasks;

use App\Models\Task as PostModel;
use Livewire\Component;

class Task extends Component
{

    public $task;

    public function mount($id)
    {
        $this->task = TaskModel::with(['author', 'comments', 'category', 'images', 'videos', 'tags'])->find($id);
    }

    public function render()
    {
        return view('livewire.tasks.task');
    }
}
