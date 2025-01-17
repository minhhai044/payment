<?php


namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Log;

class SeatStatusChange implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $seatId;
    public $status;
    public $type_seat;

    public function __construct($seatId, $status,$type_seat)
    {
        $this->seatId = $seatId;
        $this->status = $status;
        $this->type_seat = $type_seat;
    }

    public function broadcastOn()
    {
        Log::info("SeatStatusChange event triggered for seat: {$this->seatId}, status: {$this->status}");
        return new Channel('showtime');
       
        // return ['showtime'];
    }

    public function broadcastWith()
    {
        return [
            'seatId' => $this->seatId,
            'status' => $this->status,
            'type_seat' => $this->type_seat,
        ];
    }
}



