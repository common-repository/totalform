<?php

namespace TotalFormVendors\TotalSuite\Foundation;
! defined( 'ABSPATH' ) && exit();


use Throwable;
use TotalFormVendors\TotalSuite\Foundation\Contracts\ExceptionHandler as ExceptionHandlerContract;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Http\Response;

/**
 * Class ExceptionHandler
 *
 * @package TotalFormVendors\TotalSuite\Foundation\Exceptions
 */
class ExceptionHandler implements ExceptionHandlerContract
{
    /**
     * @var Environment
     */
    protected $environment;

    /**
     * ExceptionHandler constructor.
     *
     * @param Environment $environment
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @param Throwable $exception
     *
     * @throws Throwable
     */
    public function handle(Throwable $exception)
    {
        if ($this->environment->isRest()) {
            (new Response())->withStatus($this->statusFromException($exception))->withJson(
                [
                    'success' => false,
                    'error' => $exception->getMessage(),
                    'data' => ($exception instanceof Exception) ? $exception->getErrors() : [],
                ]
            )->sendAndExit();
        }

        throw $exception;
    }

    /**
     * @param Throwable $exception
     *
     * @return int|mixed
     */
    protected function statusFromException(Throwable $exception)
    {
        if ($exception instanceof Exception) {
            return $exception->getCode();
        }

        return 500;
    }


}