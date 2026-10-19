<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Gzips HTML and JSON responses (panel revision: load fast on any network).
 * The admin pages are several hundred KB of HTML, which compresses to about
 * a tenth of that. Skipped when the client cannot accept gzip or something
 * upstream already compressed the response.
 */
class CompressResponse
{
    private const MIN_BYTES = 1024;

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof BinaryFileResponse || $response instanceof StreamedResponse
            || $response->headers->has('Content-Encoding')
            || !str_contains((string) $request->header('Accept-Encoding'), 'gzip')
            || !function_exists('gzencode')) {
            return $response;
        }

        $type = (string) $response->headers->get('Content-Type');
        if (!str_contains($type, 'text/html') && !str_contains($type, 'application/json')) {
            return $response;
        }

        $content = $response->getContent();
        if ($content === false || strlen($content) < self::MIN_BYTES) {
            return $response;
        }

        $compressed = gzencode($content, 6);
        if ($compressed === false) {
            return $response;
        }

        $response->setContent($compressed);
        $response->headers->set('Content-Encoding', 'gzip');
        $response->headers->set('Content-Length', (string) strlen($compressed));
        $response->setVary(array_unique(array_merge($response->getVary(), ['Accept-Encoding'])));

        return $response;
    }
}
