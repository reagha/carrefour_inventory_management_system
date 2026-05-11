<?php

namespace App\Mail\InboundLogistics;

use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseOrderApproved extends Mailable
{
    use Queueable, SerializesModels;

    public PurchaseOrder $purchaseOrder;

    public function __construct(PurchaseOrder $purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;
    }

    public function build()
    {
        return $this->subject('Purchase Order Approved')
            ->view('emails.inbound_logistics.purchase_orders.approved')
            ->with(['purchaseOrder' => $this->purchaseOrder]);
    }
}
