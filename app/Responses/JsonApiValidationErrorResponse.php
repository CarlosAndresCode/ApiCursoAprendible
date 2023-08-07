<?php

namespace App\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class JsonApiValidationErrorResponse extends JsonResponse
{
    /**
     * @param ValidationException $exception
     */
    public function __construct(ValidationException $exception)
    {
        parent::__construct($this->formatJsonApiErrors($exception), 422,);
    }

    /**
     * @param ValidationException $exception
     * @return array
     */
    private function formatJsonApiErrors($exception): array
    {
        $title = $exception->getMessage();
        return [
            'errors' => collect($exception->errors())
            ->map(function ($message, $field) use ($title) {
                return [
                    'title' => $title,
                    'detail' => $message[0],
                    'source' => [
                        'pointer' => '/data/attributes/'.$field
                    ]
                ];
            })->values()
        ];
    }
}
