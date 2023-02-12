<?php

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use App\Models\Task;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Categorytasks extends Component
{
    use WithPagination;

    public $title, $content, $category, $task_id;
    public $tagids = array();
    public $isOpen = 0;

    public $cid;

    public function mount($id)
    {
        $this->cid = $id;
    }

    public function render()
    {
        return view('livewire.tasks.tasks', [
            'tasks' => Task::where('category_id', $this->cid)->orderBy('id', 'desc')->paginate(),
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ]);
    }

    public function store()
    {
        $this->validate([
            'title' => 'required',
            'content' => 'required',
            'category' => 'required',
        ]);

        $task = Task::updateOrCreate(['id' => $this->task_id], [
            'title' => $this->title,
            'content' => $this->content,
            'category_id' => intVal($this->category),
            'author_id' => Auth::user()->id,
        ]);

        if (count($this->tagids) > 0) {

            DB::table('task_tag')->where('task_id', $task->id)->delete();

            foreach ($this->tagids as $tagid) {
                DB::table('task_tag')->insert([
                    'task_id' => $task->id,
                    'tag_id' => intVal($tagid),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        session()->flash(
            'message',
            $this->task_id ? 'Task Updated Successfully.' : 'Task Created Successfully.'
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        Task::find($id)->delete();
        session()->flash('message', 'Task Deleted Successfully.');
    }

    public function edit($id)
    {
        $task = Task::with('tags')->findOrFail($id);

        $this->task_id = $id;
        $this->title = $task->title;
        $this->content = $task->content;
        $this->category = $task->category_id;
        $this->tagids = $task->tags->pluck('id');

        $this->openModal();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->title = '';
        $this->content = '';
        $this->category = null;
        $this->tagids = null;
        $this->task_id = '';
    }
}
