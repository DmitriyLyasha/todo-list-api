<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enums\PriorityEnum;

/**
 * @OA\Schema(
 *     description="Task DTO",
 *     type="object",
 *     required={"status", "priority", "title"}
 * )
 */
class TaskDTO
{
    /**
     * @OA\Property(type="integer", format="int64")
     */
    private $id;
    /**
     * @OA\Property(type="string", enum={"todo", "done"})
     */
    private $status;
    /**
     * @OA\Property(type="integer", minimum=1, maximum=5)
     */
    private $priority;
    /**
     * @OA\Property(type="string")
     */
    private $title;
    /**
     * @OA\Property(type="string")
     */
    private $description;
    /**
     * @OA\Property(type="string", format="date-time")
     */
    private $createdAt;
    /**
     * @OA\Property(type="string", format="date-time")
     */
    private $completedAt;

    /**
     * TaskDTO constructor.
     *
     * @param int $id
     * @param string $status
     * @param int $priority
     * @param string $title
     * @param string|null $description
     * @param string $createdAt
     * @param string|null $completedAt
     */
    public function __construct(
        int $id,
        string $status,
        int $priority,
        string $title,
        ?string $description,
        string $createdAt,
        ?string $completedAt
    ) {
        $this->id = $id;
        $this->status = $status;
        $this->priority = $priority;
        $this->title = $title;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->completedAt = $completedAt;
    }

    /**
     * Create a TaskDTO instance from an array of data.
     *
     * @param array $data
     * @return self
     * @OA\Post(
     *     path="/tasks/dto",
     *     tags={"Tasks"},
     *     summary="Create a TaskDTO instance from an array of data",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Task data array",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/TaskDTO"))
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="TaskDTO created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskDTO")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['status'],
            $data['priority'],
            $data['title'],
            $data['description'] ?? null,
            $data['created_at'],
            $data['completed_at'] ?? null
        );
    }

    /**
     * Convert the TaskDTO instance to an array.
     *
     * @return array
     * @OA\Post(
     *     path="/tasks/dto/array",
     *     tags={"Tasks"},
     *     summary="Convert the TaskDTO instance to an array",
     *     @OA\Response(
     *         response=200,
     *         description="TaskDTO converted to array successfully",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/TaskDTO"))
     *     )
     * )
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'priority' => $this->priority,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->createdAt,
            'completed_at' => $this->completedAt,
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

}
