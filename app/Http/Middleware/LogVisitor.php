<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class LogVisitor
{
    public function handle(Request $request, Closure $next)
    {
        if (!app()->runningInConsole() && !app()->runningUnitTests() && $this->shouldTrack($request)) {
            $ip = (string) ($request->ip() ?? 'unknown');
            $userAgent = (string) ($request->userAgent() ?? '');
            $dedupeKey = sprintf(
                'visitor:home:%s:%s:%s',
                now()->format('YmdHi'),
                sha1($ip),
                sha1($userAgent)
            );

            // Record at most once per minute per IP + user-agent to reduce write pressure.
            if (Cache::add($dedupeKey, true, now()->addMinutes(1))) {
                try {
                    Visitor::create([
                        'ip_address' => $ip,
                        'user_agent' => $userAgent,
                        'url' => $request->fullUrl(),
                    ]);
                } catch (Throwable $exception) {
                    // Never break page rendering because analytics logging fails.
                    Log::warning('Visitor logging skipped due to database issue.', [
                        'message' => $exception->getMessage(),
                    ]);
                }
            }
        }

        return $next($request);
    }

    private function shouldTrack(Request $request): bool
    {
        if (!$request->isMethod('GET') || $request->expectsJson()) {
            return false;
        }

        return $request->path() === '/';
    }
}
