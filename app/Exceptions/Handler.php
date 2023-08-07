<?php

namespace App\Exceptions;

use App\Responses\JsonApiValidationErrorResponse;
use Dotenv\Exception\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function invalidJson($request, \Illuminate\Validation\ValidationException $exception): JsonApiValidationErrorResponse
    {
        return new JsonApiValidationErrorResponse($exception);
//        $errors = [];
//        $title = $exception->getMessage();
//        foreach ($exception->errors() as $field => $message) {
//            $pointer = '/'.str_replace('.','/',$field);
//
//            $errors[] = [
//                'title' => $title,
//                'detail' => $message[0],
//                'source' => [
//                    'pointer' => $pointer
//                ]
//            ];
//        }
//        return response()->json([
//            'errors' => $errors
//        ], 422); // Primera forma

        // Segunda Forma
//        $errors = collect($exception->errors())
//            ->map(function ($message, $field) use ($title){
//                return [
//                    'title' => $title,
//                    'detail' => $message[0],
//                    'source' => [
//                        'pointer' => '/'.str_replace('.','/',$field)
//                    ]
//                ];
//            })->values();
//
//        return new JsonResponse([
//            'errors' => $errors
//        ], 422);
    }
}
