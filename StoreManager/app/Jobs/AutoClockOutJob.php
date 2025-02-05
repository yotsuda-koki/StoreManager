<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Attendance;
use App\Models\BreakTime;

class AutoClockOutJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $attendanceId;
    /**
     * Create a new job instance.
     */
    public function __construct($attendanceId)
    {
        $this->attendanceId = $attendanceId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $attendance = Attendance::find($this->attendanceId);

        $isExistBreak = BreakTime::where('attendance_id', $this->attendanceId)
            ->whereNotNull('break_start')
            ->whereNull('break_end')
            ->exists();

        if ($isExistBreak) {
            BreakTime::where('attendance_id', $this->attendanceId)
                ->whereNotNull('break_start')
                ->whereNull('break_end')
                ->update([
                    'break_end' => $attendance->clock_in->copy()->addHours(24),
                    'is_break' => 0,
                ]);
        };

        if ($attendance && is_null($attendance->clock_out)) {
            $attendance->update([
                'clock_out' => $attendance->clock_in->copy()->addHours(24),
                'is_active' => 0,
            ]);
        }
    }
}
