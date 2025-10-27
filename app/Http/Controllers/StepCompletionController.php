<?php

namespace App\Http\Controllers;

use App\Models\Step;
use Illuminate\Http\Request;

class StepCompletionController extends Controller
{
    public function toggle(Request $request, Step $step)
    {
        if ($step->office_id !== $request->user()->office_id) {
            abort(403);
        }

        $request->user()->completedSteps()->toggle($step->id);

        return redirect()->back()->with('success', 'Status da etapa atualizado.');
    }
}
