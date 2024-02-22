<?php

namespace App\Helpers;

use Illuminate\Http\Response as BaseResponse;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Facades\Log;

class ApiResponse extends BaseResponse implements Jsonable
{
    /**
     * any data want to return with response
     * @var [type]
     */
    protected $data = [];

    /**
     * response message
     * @var [type]
     */
    protected $message;

    /**
     * response status code
     * @var [type]
     */
    protected $status;

    /**
     * This will return success with the given data and message in return.
     *
     * @param null|string $message
     * @param array $data
     * @param int $status
     * @return $this
     */
    public function success($message = null, $data = [], $status = BaseResponse::HTTP_OK)
    {
        $this->status = true;
        $this->data = $data;
        $this->message = $this->prepareMessage($message);

        $this->setStatusCode($status);

        return $this->setContent($this);
    }

    /**
     * This will return error in json response
     *
     * @param null $message
     * @param array $data
     * @param int $status
     * @param null $exception
     * @return $this
     */
    public function error($message = null, $data = [], $status = BaseResponse::HTTP_UNPROCESSABLE_ENTITY, $exception = null)
    {
        $this->status = false;
        $this->data = $data;
        $this->message = $message;

        $this->setStatusCode($status);

        if ($exception) {
            $this->logException($exception);
        }

        return $this->setContent($this);
    }

    /**
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->prepareData());
    }

    /**
     * @return array
     */
    private function prepareData()
    {
        $response = [
            'status' => $this->status,
            'message' => $this->message
        ];

        if ($this->data) {
            $response['data'] = $this->data;
        }

        return  $response;
    }

    /**
     * This will generate message based on the controller and action of the calling function
     *
     * @param $message
     * @return string
     */
    private function prepareMessage($message)
    {
        if (is_null($message)) {
            try {
                $trace = debug_backtrace(
                    DEBUG_BACKTRACE_PROVIDE_OBJECT,
                    7
                )[6];

                $action = $trace['function'];

                $name = (new \ReflectionClass($trace['object']))->getShortName();
                $name = explode("Controller", $name, 2)[0];

                if ($action == 'store') {
                    $action = "created";
                } elseif ($action == 'update') {
                    $action = "updated";
                } elseif ($action == 'destroy') {
                    $action = "deleted";
                }

                return "$name $action successfully";
            } catch (\Throwable $th) {
                return "Success";
            }
        }

        return $message;
    }

    /**
     * @param $exception
     */
    private function logException($exception)
    {
        Log::channel('daily_all')->error('URL ' . request()->url(), request()->all());
        Log::channel('daily_all')->error($exception->getMessage(), $exception->getTrace());
    }
}
