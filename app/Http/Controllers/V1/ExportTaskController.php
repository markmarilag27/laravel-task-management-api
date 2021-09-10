<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Actions\ExportTaskAction;
use App\Enums\ContentType;
use App\Enums\ExportType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ExportTaskController extends Controller
{
    /**
     * Create a new ExportTaskController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function __invoke(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        /** @var $user */
        $user = $request->user();

        if (! $request->has('type')) {
            abort(Response::HTTP_BAD_REQUEST, trans('export.type'));
        }

        /** @var $type */
        $type = strtolower($request->type);

        /** @var $filename */
        $filename = $this->getFilename($type);

        /** @var $path */
        $path = (new ExportTaskAction($type))->execute($user->id, $filename);

        return Storage::download($path, $filename, [
            'Content-Type' => $this->getContentType($type)
        ]);
    }

    /**
     * Get the filename
     *
     * @param string $type
     * @return string
     */
    private function getFilename(string $type): string
    {
        /** @var $name */
        $name = 'tasks-' . Str::slug(now()->format('d-m-Y-') . time());

        if ($type === ExportType::JSON) {
            return "$name.json";
        }

        return match($type) {
            ExportType::EXCEL => "$name.xls",
            ExportType::CSV => "$name.csv"
        };
    }

    /**
     * Get content type
     *
     * @param string $type
     * @return string
     */
    private function getContentType(string $type): string
    {
        return match($type) {
            ExportType::EXCEL => ContentType::EXCEL,
            ExportType::CSV => ContentType::CSV,
            ExportType::JSON => ContentType::JSON,
        };
    }
}
