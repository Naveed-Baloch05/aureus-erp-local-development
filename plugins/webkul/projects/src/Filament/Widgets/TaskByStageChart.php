<?php

namespace Webkul\Project\Filament\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Carbon;
use Webkul\Project\Models\Task;
use Webkul\Project\Models\TaskStage;

class TaskByStageChart extends ChartWidget
{
    use HasWidgetShield, InteractsWithPageFilters;

    protected ?string $heading = 'Tasks By Stage';

    protected ?string $maxHeight = '250px';

    protected static ?int $sort = 1;

    protected static function getPagePermission(): ?string
    {
        return 'widget_project_task_by_stage_chart';
    }

    public function getHeading(): string|Htmlable|null
    {
        return __('projects::filament/widgets/task-by-stage.heading.title');
    }

    protected function getData(): array
    {
        $datasets = [
            'datasets' => [],
            'labels'   => [],
        ];

        $stageQuery = TaskStage::query();

        if (! empty($this->pageFilters['selectedProjects'])) {
            $stageQuery->whereIn('project_id', $this->pageFilters['selectedProjects']);
        }

        $stages = $stageQuery->get();

        foreach ($stages as $stage) {
            if (in_array($stage->name, $datasets['labels'])) {
                $datasets['labels'][] = $stage->name.' ('.$stage->project?->name.')';
            } else {
                $datasets['labels'][] = $stage->name;
            }

            $query = Task::query();

            if (! empty($this->pageFilters['selectedProjects'])) {
                $query->whereIn('project_id', $this->pageFilters['selectedProjects']);
            }

            if (! empty($this->pageFilters['selectedAssignees'])) {
                $query->whereHas('users', function ($q) {
                    $q->whereIn('users.id', $this->pageFilters['selectedAssignees']);
                });
            }

            if (! empty($this->pageFilters['selectedTags'])) {
                $query->whereHas('tags', function ($q) {
                    $q->whereIn('projects_task_tag.tag_id', $this->pageFilters['selectedTags']);
                });
            }

            if (! empty($this->pageFilters['selectedPartners'])) {
                $query->whereIn('parent_id', $this->pageFilters['selectedPartners']);
            }

            $startDate = ! is_null($this->pageFilters['startDate'] ?? null) ?
                Carbon::parse($this->pageFilters['startDate'])->startOfDay() :
                null;

            $endDate = ! is_null($this->pageFilters['endDate'] ?? null) ?
                Carbon::parse($this->pageFilters['endDate'])->endOfDay() :
                null;

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            } elseif ($startDate) {
                $query->where('created_at', '>=', $startDate);
            } elseif ($endDate) {
                $query->where('created_at', '<=', $endDate);
            }

            $datasets['datasets'][] = $query
                ->where('stage_id', $stage->id)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => __('projects::filament/widgets/task-by-stage.datasets.label'),
                    'data'  => $datasets['datasets'],
                ],
            ],
            'labels' => $datasets['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
