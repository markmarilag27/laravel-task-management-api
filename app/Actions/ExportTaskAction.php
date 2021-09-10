<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\ContentType;
use App\Enums\ExportType;
use App\Exports\TasksExport;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel;

class ExportTaskAction
{
    /**
     * Create a new ExportTaskAction constructor.
     *
     * @param string $type
     */
    public function __construct(
        public string $type
    )
    {}

    /**
     * Execute this action
     */
    public function execute(int $userId, string $filename): string
    {
        if ($this->type === ExportType::JSON) {
            Storage::put($filename, $this->getTransformedTasks($userId), [ 'visibility' => 'public' ]);
        } else {
            (new TasksExport($userId))->store($filename, 's3', $this->getWriterType(), [
                'Content-Type' => match($this->type) {
                    ExportType::EXCEL => ContentType::EXCEL,
                    ExportType::CSV => ContentType::CSV
                },
                'visibility' => 'public',
            ]);
        }

        return Storage::path($filename);
    }

    /**
     * Get writer type
     *
     * @return string|null
     */
    private function getWriterType(): ?string
    {
        return match($this->type) {
            ExportType::EXCEL => Excel::XLS,
            ExportType::CSV => Excel::CSV,
            ExportType::JSON => null
        };
    }

    /**
     * @param int $userId
     * @return string
     */
    private function getTransformedTasks(int $userId): string
    {
        /** @var $tasks */
        $tasks = Task::query()
            ->belongsToOwnerId($userId)
            ->onlyTopLevel()
            ->getAllTasks()
            ->cursor();

        return collect($tasks)->transform(function ($task) {
            return [
                'uuid'          => $task->uuid,
                'title'         => $task->title,
                'description'   => $task->body,
                'status'        => $task->state->name,
                'subtask'       => $task->isSubTask() ? 'Yes' : 'No',
                'order'         => $task->sort_order,
                'date'          => Carbon::parse($task->created_at)->format('d/m/Y')
            ];
        })->toJson();
    }
}
