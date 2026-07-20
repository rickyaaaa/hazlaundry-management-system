<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::with('transaction')->latest()->paginate(15);

        return view('admin.feedback.index', compact('feedbacks'));
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return redirect()
            ->route('admin.feedback.index')
            ->with('success', 'Kritik & saran berhasil dihapus.');
    }
}
