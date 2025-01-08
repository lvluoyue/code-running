<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace app\middleware;

use DI\Attribute\Inject;
use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;

/**
 * Class StaticFile
 * @package app\middleware
 */
class StaticFile implements MiddlewareInterface
{

    #[Inject('ACCESS_TOKEN')]
    private readonly string $access_token;

    public function process(Request $request, callable $next): Response
    {
        // Access to files beginning with. Is prohibited
        if (strpos($request->path(), '/.') !== false) {
            return response('<h1>403 forbidden</h1>', 403);
        }

        $authorization = $request->header("Authorization");

        if(!$authorization || !str_starts_with($authorization, 'Basic ') ||
            ! ($authorization = base64_decode(substr($authorization, 6), true)) ||
            $authorization != 'admin:' . $this->access_token) {
            return  response('<h1>401 unauthorized</h1>', 401) ->withHeaders([
                'WWW-Authenticate' => 'Basic realm="Access Restricted"',
            ]);
        }

        /** @var Response $response */
        $response = $next($request);
        // Add cross domain HTTP header
        /*$response->withHeaders([
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Credentials' => 'true',
        ]);*/
        return $response;
    }
}
