<?php

declare(strict_types=1);

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class CreateTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['uuid' => "string", 'title' => "string", 'body' => "string", 'state_id' => "integer"])]
    public function rules(): array
    {
        return [
            'uuid'          => 'sometimes|string|min:36|max:36',
            'title'         => 'required|string|max:255',
            'body'          => 'required|string|max:255',
            'state_id'      => 'required|integer',
        ];
    }
}
