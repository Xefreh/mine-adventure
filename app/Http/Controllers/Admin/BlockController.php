<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BlockType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DestroyBlocksRequest;
use App\Http\Requests\Admin\ReorderBlocksRequest;
use App\Http\Requests\Admin\StoreBlockRequest;
use App\Http\Requests\Admin\UpdateBlockRequest;
use App\Models\Lesson;
use App\Models\LessonBlock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class BlockController extends Controller
{
    public function store(StoreBlockRequest $request, Lesson $lesson): RedirectResponse
    {
        DB::transaction(function () use ($request, $lesson) {
            $validated = $request->validated();

            $block = $lesson->blocks()->create([
                'type' => $validated['type'],
                'position' => $validated['position'],
            ]);

            $this->createBlockContent($block, $validated);
        });

        return redirect()->back()
            ->with('success', 'Block created successfully.');
    }

    public function update(UpdateBlockRequest $request, Lesson $lesson, LessonBlock $block): RedirectResponse
    {
        DB::transaction(function () use ($request, $block) {
            $validated = $request->validated();

            $block->update([
                'position' => $validated['position'] ?? $block->position,
            ]);

            $this->updateBlockContent($block, $validated);
        });

        return redirect()->back()
            ->with('success', 'Block updated successfully.');
    }

    public function destroy(Lesson $lesson, LessonBlock $block): RedirectResponse
    {
        $block->delete();

        return redirect()->back()
            ->with('success', 'Block deleted successfully.');
    }

    public function destroyMultiple(DestroyBlocksRequest $request, Lesson $lesson): RedirectResponse
    {
        LessonBlock::whereIn('id', $request->validated()['block_ids'])
            ->where('lesson_id', $lesson->id)
            ->delete();

        return redirect()->back()
            ->with('success', 'Blocks deleted successfully.');
    }

    public function reorder(ReorderBlocksRequest $request, Lesson $lesson): RedirectResponse
    {
        foreach ($request->validated()['blocks'] as $blockData) {
            LessonBlock::where('id', $blockData['id'])
                ->where('lesson_id', $lesson->id)
                ->update(['position' => $blockData['position']]);
        }

        return redirect()->back()
            ->with('success', 'Blocks reordered successfully.');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function createBlockContent(LessonBlock $block, array $data): void
    {
        match ($block->type) {
            BlockType::Video => $block->video()->create([
                'url' => $data['url'] ?? '',
                'duration' => $data['duration'] ?? null,
            ]),
            BlockType::Text => $block->text()->create([
                'content' => $data['content'] ?? '',
            ]),
            BlockType::Resources => $block->resource()->create([
                'links' => $data['links'] ?? [],
            ]),
            BlockType::Assignment => $block->assignment()->create([
                'instructions' => $data['instructions'] ?? '',
                'starter_code' => $data['starter_code'] ?? null,
                'language' => $data['language'] ?? 'php',
            ]),
            BlockType::Quiz => $block->quiz()->create([]),
        };
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function updateBlockContent(LessonBlock $block, array $data): void
    {
        match ($block->type) {
            BlockType::Video => $block->video?->update(array_filter([
                'url' => $data['url'] ?? null,
                'duration' => $data['duration'] ?? null,
            ], fn ($v) => $v !== null)),
            BlockType::Text => $block->text?->update([
                'content' => $data['content'] ?? $block->text->content,
            ]),
            BlockType::Resources => $block->resource?->update([
                'links' => $data['links'] ?? $block->resource->links,
            ]),
            BlockType::Assignment => $block->assignment?->update([
                'instructions' => $data['instructions'] ?? $block->assignment->instructions,
                'starter_code' => $data['starter_code'] ?? $block->assignment->starter_code,
                'language' => $data['language'] ?? $block->assignment->language,
            ]),
            BlockType::Quiz => null,
        };
    }
}
