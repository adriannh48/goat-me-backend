<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Services\EventService;

use App\Http\Resources\Response as ResponseResource;

use App\Exceptions\General\ValidatorException;

class EventController extends Controller
{
    private $eventService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->eventService  = new EventService();
    }

    public function create (Request $request) 
    {
        try {

            $validator = Validator::make($request->all(), [
                'name'           => 'required|string|max:50',
                'description'    => 'required|string|max:150',
                'date'           => 'required|date',
                'repeat_week'    => 'required|boolean',
                'start_time'     => 'required|date_format:H:i',
                'end_time'       => 'required|date_format:H:i',
                'interval_week'  => 'int|nullable'
            ]);
    
            if($validator->fails()) {
                throw new ValidatorException($validator->errors()->first());
            }

            $result = $this->eventService->save($validator->validated());

            return new ResponseResource($result, 201);
        }
        catch(\Exception $e) {
            return new ResponseResource($e);
        }
    }

    public function list() 
    {
        try {
            $result = $this->eventService->getAll();

            return new ResponseResource($result);   
        }
        catch(\Exception $e) {
            return new ResponseResource($e);
        }
    }
}
