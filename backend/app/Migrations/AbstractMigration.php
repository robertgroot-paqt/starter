<?php

declare(strict_types=1);

namespace App\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Database\Schema\ForeignIdColumnDefinition;

class AbstractMigration extends Migration
{
    public function addPostFixedUlid(Blueprint $table, string $column): ColumnDefinition
    {
        return $table->ulid($column, length: 52);
    }

    public function addForeignPostFixedUlid(Blueprint $table, string $column): ForeignIdColumnDefinition
    {
        return $table->foreignUlid($column, length: 52);
    }

    public function addId(Blueprint $table): ColumnDefinition
    {
        return $this->addPostFixedUlid($table, 'id')->primary()->unique();
    }

    public function addResponsible(Blueprint $table): void
    {
        $table->timestamp('created_at')->nullable();
        $this->addPostFixedUlid($table, 'created_by')->nullable();

        $table->timestamp('updated_at')->nullable();
        $this->addPostFixedUlid($table, 'updated_by')->nullable();

        $table->index('created_at');
        $table->index('created_by');

        $table->index('updated_at');
        $table->index('updated_by');
    }

    public function addSoftDeleted(Blueprint $table): void
    {
        $table->softDeletes('deleted_at');
        $table->ulid('deleted_by')->nullable();

        $table->index('deleted_at');
        $table->index('deleted_by');
    }
}
