<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\WorkingTime;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\AutoClockOutJob;
use App\Exceptions\OutLaterThanInException;
use App\Exceptions\EndLaterThanStartException;
use App\Exceptions\BreakTimeException;

class AttendanceController extends Controller
{
    /**
     * 労働時間情報を配列に格納
     * @param int $id
     * @return array
     */
    protected function workingTime($id)
    {
        $totalBreakTimes = 0;
        $nightBreakTimes = 0;
        $nightWorkingTimes = 0;
        $nightWorkingTime = 0;
        $overtime = 0;
        $nightOvertime = 0;
        $totalNightBreakTimes = 0;
        $totalNightOvertime = 0;
        $workingTime = [];

        $attendance = Attendance::where('id', $id)->first();
        // UTCのタイムゾーンで時間を設定
        $in = Carbon::parse($attendance->clock_in, 'UTC');
        $out = Carbon::parse($attendance->clock_out, 'UTC');
        // Configのタイムゾーンで時間を取得
        $timezone = config('app.timezone');
        $clockIn = $in->setTimezone($timezone);
        $clockOut = $out->setTimezone($timezone);
        // AttendanceのIDでBreakTimeの配列を取得
        $breaks = BreakTime::where('attendance_id', $id)->get();
        // $clockInと同じ日の5:00,22:00,29:00を取得
        $earlyNightEnd = $clockIn->copy()->setTimezone($timezone)->setTime(5, 0);
        $nightStart = $clockIn->copy()->setTimezone($timezone)->setTime(22, 0);
        $nightEnd = $clockIn->copy()->setTimezone($timezone)->setTime(5, 0)->addDay();


        foreach ($breaks as $break) {
            // UTCのタイムゾーンで休憩開始時刻と終了時刻を設定した後Configのタイムゾーンで時間を取得
            $bStart = Carbon::parse($break->break_start, 'UTC');
            $bEnd = Carbon::parse($break->break_end, 'UTC');
            $breakStart = $bStart->setTimezone($timezone);
            $breakEnd = $bEnd->setTimezone($timezone);
            // 開始時刻と終了時刻の差を総休憩時間とする
            $totalBreakTimes += $breakStart->diffInMinutes($breakEnd);
            // 開始時刻が29:00より前で終了時刻が22:00より後のとき
            if ($breakStart->lt($nightEnd) && $breakEnd->gt($nightStart)) {
                // 開始時刻または22:00のより遅い時刻と終了時刻または29:00のより早い時刻の差を深夜休憩時間とする
                $start = $nightStart->gt($breakStart) ? $nightStart : $breakStart;
                $end = $nightEnd->lt($breakEnd) ? $nightEnd : $breakEnd;
                $nightBreakTimes += $start->diffInMinutes($end);
            }
            // 開始時刻が5:00より早いとき
            if ($breakStart->lt($earlyNightEnd)) {
                // 開始時刻と終了時刻または5:00のより早い時刻の差を深夜休憩時間とする
                $end = $breakEnd->lt($earlyNightEnd) ? $breakEnd : $earlyNightEnd;
                $nightBreakTimes += $breakStart->diffInMinutes($end);
            }
        }

        $totalWorking = $clockIn->diffInMinutes($clockOut) - $totalBreakTimes;
        // 出勤時刻が29:00より早く退勤時刻が22:00より遅いとき
        if ($clockIn->lt($nightEnd) && $clockOut->gt($nightStart)) {
            // 出勤時刻または22:00のより遅い時刻と退勤時刻または29:00のより早い時刻の差を深夜労働時間とする
            $start = $nightStart->gt($clockIn) ? $nightStart : $clockIn;
            $end = $nightEnd->lt($clockOut) ? $nightEnd : $clockOut;
            $nightWorkingTimes += $start->diffInMinutes($end);
        }
        // 出勤時刻が5:00より早いとき
        if ($clockIn->lt($earlyNightEnd)) {
            // 出勤時刻と退勤時刻または5:00のより早い時刻の差を深夜労働時間とする
            $end = $clockOut->lt($earlyNightEnd) ? $clockOut : $earlyNightEnd;
            $nightWorkingTimes += $clockIn->diffInMinutes($end);
        }

        $nightWorkingTime = $nightWorkingTimes - $nightBreakTimes;

        if ($totalWorking > 480) {
            $overtime = $totalWorking - 480;
            // 残業開始時刻を出勤時刻から8時間と休憩時間を加えた時刻とする
            $overtimeStrat = $clockIn->copy()->addHours(8)->addMinutes($totalBreakTimes);
            // 残業開始時刻が29:00より早く退勤時刻が22:00より遅いとき
            if ($overtimeStrat->lt($nightEnd) && $clockOut->gt($nightStart)) {
                // 残業開始時刻または22:00より遅い時刻と退勤時刻または29:00より早い時刻の差を深夜労働時間とする
                $start = $nightStart->gt($overtimeStrat) ? $nightStart : $overtimeStrat;
                $end = $nightEnd->lt($clockOut) ? $nightEnd : $clockOut;
                $nightOvertime = $start->diffInMinutes($end);
            }

            foreach ($breaks as $break) {
                $bStart = Carbon::parse($break->break_start, 'UTC');
                $bEnd = Carbon::parse($break->break_end, 'UTC');
                $breakStart = $bStart->setTimezone($timezone);
                $breakEnd = $bEnd->setTimezone($timezone);

                if ($overtimeStrat->lt($breakStart)) {
                    $totalNightBreakTimes += $breakStart->diffInMinutes($breakEnd);
                }
            }

            $totalNightOvertime = $nightOvertime - $totalNightBreakTimes;
        }

        $workingTime = [
            'totalWorking' => $totalWorking,
            'nightWorkingTime' => $nightWorkingTime,
            'overtime' => $overtime,
            'totalNightOvertime' => $totalNightOvertime,
        ];

        return $workingTime;
    }

    /**
     * clockビューを表示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function clock()
    {
        $timezone = config('app.timezone');
        $isActive = Attendance::where('user_id', auth()->id())
            ->where('is_active', 1)
            ->exists();
        if ($isActive) {
            $attendanceId = Attendance::where('user_id', auth()->id())
                ->where('is_active', 1)
                ->first()
                ->id;
            $isBreak = BreakTime::where('attendance_id', $attendanceId)
                ->whereNull('break_end')
                ->exists();
            if ($isBreak) {
                return view('attendance.clock', compact('timezone', 'isActive', 'isBreak'));
            }
            return view('attendance.clock', compact('timezone', 'isActive'));
        }
        return view('attendance.clock', compact('timezone'));
    }

    /**
     * 出勤
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ClockIn()
    {
        try {
            $isExsitAttendance = Attendance::where('user_id', auth()->id())
                ->whereNull('clock_out')
                ->exists();
            // clock_outがnullのレコードが存在しないときに出勤時刻をテーブルに保存
            if (!$isExsitAttendance) {
                DB::transaction(function () {
                    $attendance = Attendance::create([
                        'user_id' => auth()->id(),
                        'clock_in' => now('UTC'),
                        'is_active' => 1,
                    ]);
                    // 24時間後に自動退勤ジョブをスケジュール
                    AutoClockOutJob::dispatch($attendance->id)->delay(Carbon::now()->addHours(24));
                });
            } else {
                return redirect()->route('attendance.clock')->with('error', __('error.occurred'));
            }
            return redirect()->route('attendance.clock')->with('msg', __('message.clockIn'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->route('attendance.clock')->with('error', __('error.occurred'));
        }
    }

    /**
     * 退勤
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ClockOut()
    {
        try {
            $isAttendance = Attendance::where('user_id', auth()->id())
                ->whereNotNull('clock_in')
                ->whereNull('clock_out')
                ->exists();
            // clock_inがnullでなくclock_outがnullのレコードが存在するとき
            if ($isAttendance) {
                $attendance = Attendance::where('user_id', auth()->id())
                    ->whereNotNull('clock_in')
                    ->whereNull('clock_out')
                    ->first();

                $id = $attendance->id;

                $isExistBreak = BreakTime::where('attendance_id', $id)
                    ->whereNotNull('break_start')
                    ->whereNull('break_end')
                    ->exists();
                //　break_endがnullのとき現在の時刻をbreak_endとしてレコードに保存
                if ($isExistBreak) {
                    DB::transaction(function () use ($id) {
                        BreakTime::where('attendance_id', $id)
                            ->whereNotNull('break_start')
                            ->whereNull('break_end')
                            ->update([
                                'break_end' => now('UTC'),
                                'is_break' => 0,
                            ]);
                    });
                }
                // 退勤時刻をレコードに保存
                if ($attendance) {
                    DB::transaction(function () use ($attendance) {
                        $attendance->update([
                            'clock_out' => now('UTC'),
                            'is_active' => 0,
                        ]);
                    });
                } else {
                    return redirect()->route('attendance.clock')->with('error', 'error.occurred');
                }

                $workingTime = $this->workingTime($id);
                // 取得した労働時間情報でWorkingTimeテーブルのレコードを作成
                DB::transaction(function () use ($id, $workingTime) {
                    WorkingTime::create([
                        'attendance_id' => $id,
                        'total_working' => $workingTime['totalWorking'],
                        'night_working' => $workingTime['nightWorkingTime'],
                        'overtime' => $workingTime['overtime'],
                        'night_overtime' => $workingTime['totalNightOvertime'],
                    ]);
                });

                return redirect()->route('attendance.clock')->with('msg', __('message.clockOut'));
            } else {
                return redirect()->route('attendance.clock')->with('error', __('error.occurred'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->route('attendance.clock')->with('error', __('error.occurred'));
        }
    }

    /**
     * 休憩開始
     * @return \Illuminate\Http\RedirectResponse
     */
    public function breakStart()
    {
        try {
            // is_activeが1のとき出勤中,0のとき退勤後
            $isExistActive = Attendance::where('user_id', auth()->id())
                ->where('is_active', 1)
                ->exists();

            if ($isExistActive) {

                $attendanceId = Attendance::where('user_id', auth()->id())
                    ->where('is_active', 1)
                    ->first()
                    ->id;

                $isExistBreak = BreakTime::where('attendance_id', $attendanceId)
                    ->whereNull('break_end')
                    ->exists();
                //　break_endがnullのレコードが存在しないときテーブルにレコードを作成
                if (!$isExistBreak) {
                    DB::transaction(function () use ($attendanceId) {
                        BreakTime::create([
                            'attendance_id' => $attendanceId,
                            'break_start' => now('UTC'),
                            'is_break' => 1,
                        ]);
                    });
                } else {
                    return redirect()->route('attendance.clock')->with('error', __('error.occurred'));
                }
                return redirect()->route('attendance.clock')->with('msg', __('message.breakStart'));
            } else {
                return redirect()->route('attendance.clock')->with('error', __('error.occurred'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->route('attendance.clock')->with('error', __('error.occurred'));
        }
    }

    /**
     * 休憩終了
     * @return \Illuminate\Http\RedirectResponse
     */
    public function breakEnd()
    {
        try {
            // is_activeが1のとき出勤中,0のとき退勤後
            $isExistActive = Attendance::where('user_id', auth()->id())
                ->where('is_active', 1)
                ->exists();

            if ($isExistActive) {
                $attendanceId = Attendance::where('user_id', auth()->id())
                    ->where('is_active', 1)
                    ->first()
                    ->id;

                $isExistBreak = BreakTime::where('attendance_id', $attendanceId)
                    ->whereNotNull('break_start')
                    ->whereNull('break_end')
                    ->exists();
                // break_endがnullのレコードが存在するときbreak_endをレコードに保存
                if ($isExistBreak) {
                    DB::transaction(function () use ($attendanceId) {
                        BreakTime::where('attendance_id', $attendanceId)
                            ->whereNotNull('break_start')
                            ->whereNull('break_end')
                            ->update([
                                'break_end' => now('UTC'),
                                'is_break' => 0,
                            ]);
                    });
                } else {
                    return redirect()->route('attendance.clock')->with('error', __('error.occurred'));
                }
                return redirect()->route('attendance.clock')->with('msg', __('message.breakEnd'));
            }
            return redirect()->route('attendance.clock')->with('error', __('error.occurred'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->route('attendance.clock')->with('error', __('error.occurred'));
        }
    }

    /**
     * tableビューを表示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function table()
    {
        $attendances = Attendance::where('user_id', auth()->id())
            ->whereNotNull('clock_out')
            ->paginate(5);

        return view('attendance.table', compact('attendances'));
    }

    /**
     * tableビューを表示
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function search(Request $request)
    {
        $query = $request->yearAndMonth;
        $attendances = Attendance::where('user_id', auth()->id())
            ->where('clock_in', 'like', "%$query%")
            ->paginate(5);

        return view('attendance.table', compact('attendances', 'query'));
    }

    /**
     * selectビューを表示\
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function select()
    {
        $users = User::all();

        return view('attendance.select', compact('users'));
    }

    /**
     * editビューを表示
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Request $request)
    {
        $attendances = Attendance::where('user_id', $request->id)->get();
        return view('attendance.edit',  compact('attendances'));
    }

    /**
     * 勤怠情報を変更
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        try {
            $attendanceId = $request->attendance_id;
            $clockIn = Carbon::parse($request->clock_in)->setTimezone('UTC');
            $clockOut = Carbon::parse($request->clock_out)->setTimezone('UTC');
            if ($clockIn->gt($clockOut)) {
                throw new OutLaterThanInException();
            }
            //　attendanceテーブルを更新
            DB::transaction(function () use ($attendanceId, $clockIn, $clockOut) {
                Attendance::find($attendanceId)->update([
                    'clock_in' => $clockIn,
                    'clock_out' => $clockOut,
                ]);
            });

            $breaks = $request->break;
            //　break_timeテーブルを更新
            if ($breaks) {
                foreach ($breaks as $break) {
                    $breakId = $break[0];
                    $breakStart = Carbon::parse($break[1])->setTimezone('UTC');
                    $breakEnd = Carbon::parse($break[2])->setTimezone('UTC');
                    if ($breakStart->gt($breakEnd)) {
                        throw new EndLaterThanStartException();
                    }
                    if ($clockIn->gt($breakStart) || $clockOut->lt($breakEnd)) {
                        throw new BreakTimeException();
                    }
                    DB::transaction(function () use ($breakId, $breakStart, $breakEnd) {
                        BreakTime::find($breakId)->update([
                            'break_start' => $breakStart,
                            'break_end' => $breakEnd,
                        ]);
                    });
                }
            }

            $workingTime = $this->workingTime($attendanceId);
            //　working_timeテーブルを更新
            DB::transaction(function () use ($attendanceId, $workingTime) {
                WorkingTime::where('attendance_id', $attendanceId)
                    ->update([
                        'total_working' => $workingTime['totalWorking'],
                        'night_working' => $workingTime['nightWorkingTime'],
                        'overtime' => $workingTime['overtime'],
                        'night_overtime' => $workingTime['totalNightOvertime'],
                    ]);
            });
            return redirect()->route('attendance.select')->with('msg', __('message.edit'));
        } catch (OutLaterThanInException $e) {
            DB::rollBack();
            return redirect()->route('attendance.select')->with('error', __('error.outLaterThanIn'));
        } catch (EndLaterThanStartException $e) {
            DB::rollBack();
            return redirect()->route('attendance.select')->with('error', __('error.endLaterThanstart'));
        } catch (BreakTimeException $e) {
            DB::rollBack();
            return redirect()->route('attendance.select')->with('error', __('error.breakTime'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('attendance.select')->with('error', __('error.occurred'));
        }
    }

    /**
     * totalビューを表示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function total()
    {
        return view('attendance.total');
    }

    /**
     * totalTableビューを表示
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function totalTable(Request $request)
    {
        $query = $request->yearAndMonth;
        $users = User::all();
        return view('attendance.totalTable', compact('users', 'query'));
    }
}
