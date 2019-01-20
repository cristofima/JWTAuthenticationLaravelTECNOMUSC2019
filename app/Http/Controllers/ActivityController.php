<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Http\Requests\ActivityRequest;
use Illuminate\Http\Request;
use JWTAuth;

class ActivityController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        $activities = $this->user
            ->activities()
            ->get(['id', 'title', 'description', 'date', 'priority']);

        return response()->json([
            'success' => true,
            'activities' => $activities,
        ]);
    }

    public function show($id)
    {
        $activity = $this->user->activities()->find($id);

        if (!$activity) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, activity with id ' . $id . ' cannot be found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'activity' => $activity,
        ]);
    }

    public function store(ActivityRequest $request)
    {

        $activity = new Activity();
        $activity->fill($request->only(['title', 'description', 'date', 'priority']));

        if ($this->user->activities()->save($activity)) {

            return response()->json([
                'success' => true,
                'activity' => $activity,
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, activity could not be added',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $activity = $this->user->activities()->find($id);

        if (!$activity) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, activity with id ' . $id . ' cannot be found',
            ], 404);
        }

        $updated = $activity->fill($request->all())->save();

        if ($updated) {
            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, activity could not be updated',
            ], 500);
        }
    }

    public function destroy($id)
    {
        $activity = $this->user->activities()->find($id);

        if (!$activity) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, activity with id ' . $id . ' cannot be found',
            ], 404);
        }

        if ($activity->delete()) {
            return response()->json([], 204);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Activity could not be deleted',
            ], 500);
        }
    }
}
