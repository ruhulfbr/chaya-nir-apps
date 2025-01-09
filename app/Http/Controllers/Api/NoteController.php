<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Note\NoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $limit  = $this->getLimit($request);
        $search = $request->query('search');

        $note = Note::query();
        $note->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', '%' . $search . '%')
                  ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        });
        $note = $note->paginate($limit);

        return NoteResource::collection($note);
    }

    public function show(Note $note)
    {
        return new NoteResource($note);
    }

    public function store(NoteRequest $request)
    {
        try {
            Note::create($request->validated());
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to create note',
                'error'   => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Note created successfully',
        ], Response::HTTP_CREATED);
    }

    public function update(NoteRequest $request, Note $note)
    {
        try {
            $note->update($request->validated());
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to update note',
                'error'   => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Note updated successfully',
        ]);
    }

    public function destroy($ids)
    {
        $ids = explode(',', $ids);

        try {
            Note::whereIn('id', $ids)->delete();
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to delete note',
                'error'   => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Note deleted successfully',
        ], Response::HTTP_NO_CONTENT);
    }
}
