<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('resignations', function (Blueprint $table) {
            if (!Schema::hasColumn('resignations', 'exit_interview_reason')) {
                $table->text('exit_interview_reason')->nullable()->after('reason');
            }
            if (!Schema::hasColumn('resignations', 'feedback')) {
                $table->text('feedback')->nullable()->after('exit_interview_reason');
            }
            if (!Schema::hasColumn('resignations', 'last_day')) {
                $table->date('last_day')->nullable()->after('feedback');
            }
            if (!Schema::hasColumn('resignations', 'clearance_notes')) {
                $table->text('clearance_notes')->nullable()->after('last_day');
            }
            if (!Schema::hasColumn('resignations', 'clearance_completed')) {
                $table->boolean('clearance_completed')->default(false)->after('clearance_notes');
            }
            if (!Schema::hasColumn('resignations', 'clearance_completed_at')) {
                $table->timestamp('clearance_completed_at')->nullable()->after('clearance_completed');
            }
        });
    }

    public function down()
    {
        Schema::table('resignations', function (Blueprint $table) {
            $table->dropColumn([
                'exit_interview_reason',
                'feedback',
                'last_day',
                'clearance_notes',
                'clearance_completed',
                'clearance_completed_at'
            ]);
        });
    }
};