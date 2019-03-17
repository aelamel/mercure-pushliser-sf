<?php

namespace App\Exception;


use Symfony\Component\HttpKernel\Exception\HttpException;

trait ControllerExceptionHandler
{
    protected function handleExceptionsIn($function)
    {
        try {
            return $function();
        } catch (HttpException $e) {
            return $this->reportError($e, $e->getStatusCode());
        } catch (\Exception $e) {
            return $this->reportError($e, 500);
        }
    }

    private function reportError(\Exception $e, $code)
    {
        return $this->view(
            ['code' => $code, 'status' => 'ERROR', 'message' => $e->getMessage()],
            $code
        );
    }
}