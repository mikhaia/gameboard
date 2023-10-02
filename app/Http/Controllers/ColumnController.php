<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Board;
use App\Models\Column;
use Illuminate\Support\Facades\Validator;

class ColumnController extends Controller
{
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'position' => 'number',
        ]);

        $board = Board::where('user_id', auth()->id())->find($request->input('board_id'));
        if (!$board)
            return redirect()->route('index')->with('error', 'Something went wrong');

        $data = $request->input();
        
        $column = Column::create($data);
        return redirect()->route('boards.show', [$board->id])->with('success', 'Column has been created successfully!');
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'position' => 'number',
        ]);

        $board = Board::where('user_id', auth()->id())->find($request->input('board_id'));
        if (!$board)
            return redirect()->route('index')->with('error', 'Something went wrong');

        $data = $request->input();
        unset($data['_method']);

        $column = Column::where('board_id', $board->id)->where('id', $id)->update($data);
        return redirect()->route('boards.show', [$board->id])->with('success', 'Column has been updated successfully!');
    }

    public function sort(Request $request, $boardId) {
        foreach($request->input() as $position => $id) {
            Column::find($id)->update(['position' => $position]);
        }
    }
}
