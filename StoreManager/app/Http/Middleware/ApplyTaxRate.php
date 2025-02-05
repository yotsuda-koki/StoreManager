<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tax;
use Carbon\Carbon;

class ApplyTaxRate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $taxRate = $this->getApplicableTaxRate();

        $request->merge(['tax_rate' => $taxRate]);

        return $next($request);
    }

    private function getApplicableTaxRate()
    {
        $taxRate = Tax::where('effective_date', '<=', Carbon::now())
            ->orderBy('effective_date', 'desc')
            ->first();

        return $taxRate->tax_rate;
    }
}
