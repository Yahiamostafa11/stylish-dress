<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Testimonials — the "share your review" box on the React Reviews page
|--------------------------------------------------------------------------
|
| Not a WooCommerce product review (see $submitProductReviewHandler in
| web.php for those, which write straight into wp_comments). This is a
| general testimonial about the business, unattached to any product, and it
| doesn't publish itself — it's emailed to the owner, who curates it onto
| the Reviews page as a screenshot herself, same as the existing static
| ones there.
*/

$reviewsCors = function ($response) {
    return $response
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type');
};

Route::options('/api/reviews', function () use ($reviewsCors) {
    return $reviewsCors(response()->json([], 204));
});

Route::post('/api/reviews', function (Request $request) use ($reviewsCors) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:120'],
        'message' => ['required', 'string', 'min:8', 'max:2000'],
    ]);

    $to = (string) config('styliiiish.reviews.notify_email');

    try {
        Mail::raw(
            "From: {$validated['name']}\n\n{$validated['message']}",
            function ($mail) use ($validated, $to) {
                $mail->to($to)->subject('New review from ' . $validated['name'] . ' — Styliiiish');
            }
        );
    } catch (\Throwable $exception) {
        logger()->error('Reviews mail send failed', ['error' => $exception->getMessage()]);

        return $reviewsCors(response()->json([
            'message' => 'Could not send your review right now. Please try again later.',
        ], 502));
    }

    return $reviewsCors(response()->json([
        'message' => 'Thanks! Your review was sent.',
    ]));
})->middleware('throttle:reviews');

/*
| "Contact us" form (website + mobile app): emailed to the same inbox as the reviews, with the visitor's
| email as Reply-To when they gave one, so the team can just hit reply.
*/
Route::options('/api/contact', function () use ($reviewsCors) {
    return $reviewsCors(response()->json([], 204));
});

Route::post('/api/contact', function (Request $request) use ($reviewsCors) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:120'],
        'contact' => ['required', 'string', 'max:120'],
        'message' => ['required', 'string', 'min:5', 'max:3000'],
    ]);

    $to = (string) config('styliiiish.reviews.notify_email');
    $contact = trim($validated['contact']);

    try {
        Mail::raw(
            "From: {$validated['name']}\nContact: {$contact}\n\n{$validated['message']}",
            function ($mail) use ($validated, $to, $contact) {
                $mail->to($to)->subject('Contact form: ' . $validated['name'] . ' — Styliiiish');
                if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
                    $mail->replyTo($contact, $validated['name']);
                }
            }
        );
    } catch (\Throwable $exception) {
        logger()->error('Contact mail send failed', ['error' => $exception->getMessage()]);

        return $reviewsCors(response()->json([
            'message' => 'Could not send your message right now. Please try again later.',
        ], 502));
    }

    return $reviewsCors(response()->json(['message' => 'ok']));
})->middleware('throttle:reviews');
