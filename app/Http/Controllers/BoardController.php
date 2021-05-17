<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\BoardUser;
use App\Models\User;
use App\Models\Task;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/**
 * Class BoardController
 *
 * @package App\Http\Controllers
 */
class BoardController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function boards()
    {
        /** @var User $user */
        $user = Auth::user();

        $boards = Board::with(['user', 'boardUsers']);

        if ($user->role === User::ROLE_USER) {
            $boards = $boards->where(function ($query) use ($user) {
                //Suntem in tabele de boards in continuare
                $query->where('user_id', $user->id)
                    ->orWhereHas('boardUsers', function ($query) use ($user) {
                        //Suntem in tabela de board_users
                        $query->where('user_id', $user->id);
                    });
            });
        }

        $boards = $boards->paginate(10);

        return view(
            'boards.index',
            [
                'boards' => $boards
            ]
        );
    }

     /**
     * @param  Request  $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function updateBoard(Request $request, $id)
    {
        $error = '';
        $success = '';

        if ($request->has('id')) {
            /** @var Board $board */
            $boards = Board::with(['boardUsers']);
            $board = $boards->find($request->get('id'));

            if ($board) {
               $board->name = $request->get('title');
              //
               $board_user = BoardUser::find($request->get('id'));
               $board->board_users->user_id = $request->get('members');
               $board->save();

               $error = 'Success';
            } else {
                $error = 'Board not found!';
            }
        } else {
            $error = 'Invalid request';
        }

        return redirect()->back()->with([
            'error' => $error, 'success' => $success
        ]);

    }



     /**
     * @param  Request  $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function deleteBoard(Request $request, $id): JsonResponse
    {
        $user = Auth::user();

        if ($user->role === User::ROLE_USER) {
            $boards = Board::with(['user', 'boardUsers']);

            $boards = $boards->where(function ($query) use ($user) {
                //Suntem in tabele de boards in continuare
                $query->where('user_id', $user->id)
                    ->orWhereHas('boardUsers', function ($query) use ($user) {
                        //Suntem in tabela de board_users
                        $query->where('user_id', $user->id);
                    });
            });
            $board = $boards->find($id);
        }else{
            $board = Board::find($id);
        }

        $error = '';
        $success = '';

        if ($board) {

            $board->boardUsers()->delete(); // first delete boardUsers

            $board->delete();

            $success = 'Board deleted';
        } else {
            $error = 'Board not found!';
        }

        return response()->json(['error' => $error, 'success' => $success]);
    }

    /**
     * @param $id
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function board($id)
    {
        /** @var User $user */
        $user = Auth::user();

        $boards = Board::query();

        $tasks = Task::with('user');

        if ($user->role === User::ROLE_USER) {
            $boards = $boards->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('boardUsers', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    });
            });
        }

        $board = clone $boards;
        $board = $board->where('id', $id)->first();

        $boards = $boards->select('id', 'name')->get();
        $tasks = $tasks->where('board_id',$board->id)->get();
        if (!$board) {
            return redirect()->route('boards.all');
        }

        return view(
            'boards.view',
            [
                'board' => $board,
                'boards' => $boards,
                'tasks' => $tasks
            ]
        );
    }
}
