<?php
namespace App\Http\Livewire\Tasks;

use App\Models\Category;
use App\Models\Image;
use App\Models\Task;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;


class Tasks extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $title, $content, $category, $task_id;
    public $tagids = array();
    public $photos = [];
    public $isOpen = 0;

    public function render()
    {
        return view('livewire.tasks.tasks', [
            'tasks' => Task::orderBy('id', 'desc')->paginate(),
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
            'photos.*' => 'image|max:1024',
        ]);

        // Update or Insert Task
        $task = Task::updateOrCreate(['id' => $this->task_id], [
            'title' => $this->title,
            'content' => $this->content,
            'category_id' => intVal($this->category),
            'author_id' => Auth::user()->id,
        ]);

        // Image upload and store name in db
        if (count($this->photos) > 0) {
            Image::where('task_id', $task->id)->delete();
            $counter = 0;
            foreach ($this->photos as $photo) {

                $storedImage = $photo->store('public/photos');

                $featured = false;
                if($counter == 0 ){
                    $featured = true;
                }
                Image::create([
                    'url' => url('storage'. Str::substr($storedImage, 6)),
                    'title' => '-',
                    'task_id' => $task->id,
                    'featured' => $featured
                ]);
                $counter++;
            }
        }

        // Task Tag mapping
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
        DB::table('task_tag')->where('task_id', $id)->delete();

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
        $this->title = null;
        $this->content = null;
        $this->category = null;
        $this->tagids = null;
        $this->photos = null;
        $this->task_id = null;
    }
}
