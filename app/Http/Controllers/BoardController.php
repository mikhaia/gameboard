<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Board;
use App\Models\Column;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class BoardController extends Controller
{
    public function create() {
        return Inertia::render('Board');
    }

    public function show($id) {
        $board = Board::find($id);
        if (!$board || ($board->user_id !== auth()->id() && !$board->public))
            return Inertia::render(404);

        $columns = Column::where('board_id', $id)
            ->with(['cards' => function($q) { $q->orderBy('position', 'asc'); }])
            ->orderBy('position', 'asc')
            ->get();
        return Inertia::render('Board', [
            'board' => $board,
            'columns' => $columns,
            'editable' => $board->user_id === auth()->id()
        ]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'icon' => 'image|mimes:jpg,jpeg,png|max:3072',
            'background' => 'image|mimes:jpg,jpeg,png|max:3072',
            'public' => 'boolean',
            'dark' => 'boolean'
        ]);

        $data = $request->input();
        $data['user_id'] = auth()->id();
        $data['position'] = Board::where('user_id', auth()->id())->max('position') + 1;
        // Icon
        $data['icon'] = $this->changeImage('icon', 50, 50, $request, null, true);
        // Background
        $data['background'] = $this->changeImage('background', 1280, 720, $request);
        
        $board = Board::create($data);
        return redirect()->route('boards.show', [$board->id])->with('success', 'Welcome to your new board!');
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'icon' => 'image|mimes:jpg,jpeg,png|max:3072',
            'background' => 'image|mimes:jpg,jpeg,png|max:3072',
            'public' => 'boolean',
            'dark' => 'boolean'
        ]);

        $data = $request->input();
        unset($data['_method']);
        unset($data['background']);
        unset($data['icon']);

        $model = Board::find($id);
        if (!$model || $model->user_id !== auth()->id())
            return Inertia::render(404);

        // Icon
        $this->changeImage('icon', 50, 50, $request, $model, true);
        // Background
        $this->changeImage('background', 1280, 720, $request, $model);

        $board = Board::where('id', $id)->where('user_id', auth()->id())->update($data);
        return redirect()->route('boards.show', $id)->with('success', 'Your board has been updated!');
    }

    public function switch(Request $request, $boardId) {
        Board::find($boardId)->update(['dark' => $request->input('dark')]);
        $msg = ($request->input('dark') ? 'Dark' : 'Light') . ' mode is on!';
        return ['success' => $msg];
    }

    public function sort(Request $request) {
        foreach($request->input() as $position => $id) {
            Board::where('user_id', auth()->id())->where('id', $id)->update(['position' => $position]);
        }
    }

    public function destroy(Request $request, $id) {
        $model = Board::find($id);
        if ($model->user_id) {
            if ($model->icon) {
                File::delete(public_path($model->icon));
            }
            if ($model->background) {
                File::delete(public_path($model->background));
            }
            foreach($model->columns as $column) {
                foreach($column->cards as $card) {
                    if ($card->cover) {
                        File::delete(public_path($card->cover));
                    }
                    $card->delete();
                }
                $column->delete();
            }

            $model->delete();
            return redirect()->route('index')->with('success', 'Board was deleted successfully!');
        }
        return redirect()->route('index')->with('error', 'Board does not exist');
    }
}
