<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Task;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TasksExport implements
    FromCollection,
    ShouldAutoSize,
    WithMapping,
    WithHeadings
{
    use Exportable;

    /**
     * Create a new TasksExport constructor.
     *
     * @param int $userId
     * @return void
     */
    public function __construct(
        protected int $userId
    )
    {}

    /**
     * @return \Illuminate\Support\LazyCollection
     */
    public function collection(): \Illuminate\Support\LazyCollection
    {
        return Task::query()
            ->belongsToOwnerId($this->userId)
            ->onlyTopLevel()
            ->getAllTasks()
            ->cursor();
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->uuid,
            $row->title,
            $row->body,
            strtoupper($row->state->name),
            $row->isSubTask() ? 'Yes' : 'No',
            $row->sort_order,
            Carbon::parse($row->created_at)->format('d/m/Y')
        ];
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return [
            'UUID',
            'Title',
            'Description',
            'Status',
            'Subtask',
            'Order',
            'Date'
        ];
    }
}
