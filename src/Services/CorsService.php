<?php

namespace Zdk\Cors\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CorsService
{
    private $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;

        $this->normalizeOptions($options);
    }

    private function normalizeOptions(array $options = [])
    {
       if (in_array('*', $this->options['allowOrigins'])) {
           $this->options['allowOrigins'] = true;
       }

       if (in_array('*', $this->options['allowMethods'])) {
           $this->options['allowMethods'] = true;
       }

       if (in_array('*', $this->options['allowHeaders'])) {
           $this->options['allowHeaders'] = true;
       }
    }

    // 处理预检请求
    public function handlePreflightRequest(Request $request)
    {
        if (true !== $this->checkOrigin($request)
            || true !== $this->checkMethod($request)
            ||  true !== $this->checkHeaders($request)
        ) {
            return $this->createBadResponse('no perm', 403);
        }

        return $this->addActualResponse($request, new Response());
    }

    public function addActualResponse(Request $request, $response)
    {
        $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin'));
        $response->headers->set('Access-Control-Allow-Methods', $request->headers->get('Access-Control-Request-Headers'));
        $response->headers->set('Access-Control-Allow-Headers', $request->headers->get('Access-Control-Request-Headers'));

        return $response;

    }

    // 是否为预检请求
    public function isPreflightRequest(Request $request)
    {
        return $this->isCorsRequest($request)
            && $request->method() === 'OPTIONS'
            && $request->headers->has('Access-Control-Request-Method');
    }

    // 是否为跨域访问
    public function isCorsRequest(Request $request)
    {
        return $request->headers->has('Origin') && !$this->isSameOrigin($request);
    }

    // 是否为同一个域
    private function isSameOrigin(Request $request)
    {
        return $request->headers->get('Origin') === $request->getSchemeAndHttpHost();
    }

    private function createBadResponse($reason, $code)
    {
        return new Response($reason, $code);
    }

    // 检查方法是否允许
    private function checkMethod(Request $request)
    {
        if ($this->options['allowMethods'] === true) {
            return true;
        }

        return in_array(strtoupper($request->headers->get('Access-Control-Request-Method')), $this->options['allowMethods']);
    }

    private function checkOrigin(Request $request)
    {
        if ($this->options['allowOrigins'] === true) {
            return true;
        }

        return in_array($request->headers->get('Origin'), $this->options['allowOrigins']);
    }

    // 检查 header信息
    private function checkHeaders(Request $request)
    {
        if ($this->options['allowHeaders'] === true) {
            return true;
        }

        $headers = $request->headers->get('Access-Control-Request-Headers');
        $requestHeaders = explode(',', $headers);

        $allowHeaders = array_map('strtoupper', $this->options['allowHeaders']);
        foreach ($requestHeaders as $requestHeader) {
            if (!in_array(strtoupper($requestHeader), $allowHeaders)) {
                return false;
            }
        }

        return true;
    }

}