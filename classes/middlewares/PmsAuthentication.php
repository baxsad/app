<?php

namespace Buff\classes\middlewares;

use DomainException;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\RequestHandlerInterface as RequestHandler;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Server\MiddlewareInterface;
use Slim\Http\Response as SlimResponse;

final class PmsAuthentication implements MiddlewareInterface
{
	protected $logger;
    protected $message;
    private $options = [
        "header" => "X-Sign",
        "regexp" => "/(.*)/",
        "attribute" => "token",
        "before" => null,
        "after" => null,
        "error" => null
    ];

    public function __construct(array $options = [])
    {
    	$this->hydrate($options);
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        try {
            $sign = $this->fetchSign($request);
        } catch (RuntimeException | DomainException $exception) {
            $response = new SlimResponse(401);
            return $this->processError($response, [
                "message" => $exception->getMessage()
            ]);
        }

        if ($this->options["attribute"]) {
            $request = $request->withAttribute($this->options["attribute"], $sign);
        }

        if (is_callable($this->options["before"])) {
            $response = new SlimResponse(200);
            $beforeRequest = $this->options["before"]($request, $params);
            if ($beforeRequest instanceof Request) {
                $request = $beforeRequest;
            }
        }

        $response = $handler->handle($request);

        if (is_callable($this->options["after"])) {
            $afterResponse = $this->options["after"]($response, $params);
            if ($afterResponse instanceof Response) {
                return $afterResponse;
            }
        }

        return $response;
    }

    private function fetchToken(Request $request): string
    {
    	$header = "";
        $message = "Using sign from request header";

        $headers = $request->getHeader($this->options["header"]);
        $header = isset($headers[0]) ? $headers[0] : "";

        if (preg_match($this->options["regexp"], $header, $matches)) {
            return $matches[1];
        }

    	throw new RuntimeException("Sign not found.");
    }

    private function processError(Response $response, array $arguments): Response
    {
        if (is_callable($this->options["error"])) {
            $handlerResponse = $this->options["error"]($response, $arguments);
            if ($handlerResponse instanceof Response) {
                return $handlerResponse;
            }
        }
        return $response;
    }

    private function hydrate($data = []): void
    {
        foreach ($data as $key => $value) {
            /* https://github.com/facebook/hhvm/issues/6368 */
            $key = str_replace(".", " ", $key);
            $method = lcfirst(ucwords($key));
            $method = str_replace(" ", "", $method);
            if (method_exists($this, $method)) {
                /* Try to use setter */
                call_user_func([$this, $method], $value);
            } else {
                /* Or fallback to setting option directly */
                $this->options[$key] = $value;
            }
        }
    }

    private function header(string $header): void
    {
        $this->options["header"] = $header;
    }

    private function regexp(string $regexp): void
    {
        $this->options["regexp"] = $regexp;
    }

    private function attribute(string $attribute): void
    {
        $this->options["attribute"] = $attribute;
    }

    private function before(Closure $before): void
    {
        $this->options["before"] = $before->bindTo($this);
    }

    private function after(Closure $after): void
    {
        $this->options["after"] = $after->bindTo($this);
    }

    private function error(Closure $error): void
    {
        $this->options["error"] = $error->bindTo($this);
    }
}