<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentHistoryCollection;
use App\Http\Resources\PaymentHistoryResource;
use App\Models\PaymentHistory;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Payments", description="История платежей и PDF-чек")
 */
class PaymentController extends Controller
{
    /**
     * @OA\Get(path="/api/payments", tags={"Payments"}, summary="История платежей", security={{"sanctum":{}}})
     */
    public function index(Request $request)
    {
        $payments = PaymentHistory::where('user_id', $request->user()->id)->latest()->paginate(20);
        return new PaymentHistoryCollection($payments);
    }

    /**
     * @OA\Get(path="/api/payments/{id}", tags={"Payments"}, summary="Платеж по ID", security={{"sanctum":{}}})
     */
    public function show(Request $request, $id)
    {
        $payment = PaymentHistory::where('user_id', $request->user()->id)->findOrFail($id);
        return new PaymentHistoryResource($payment);
    }

    /**
     * @OA\Get(path="/api/payments/{id}/receipt", tags={"Payments"}, summary="PDF-чек", security={{"sanctum":{}}})
     */
    public function receipt(Request $request, $id)
    {
        $payment = PaymentHistory::where('user_id', $request->user()->id)->with('order.items.product')->findOrFail($id);
        $lines = [
            '%PDF-1.4',
            'TechNovaShop payment receipt',
            'Receipt: ' . $payment->receipt_number,
            'Amount: ' . $payment->amount . ' ' . $payment->currency,
            'Provider: Stripe sandbox demo',
            'Company: TechNovaShop',
            'Status: ' . $payment->status,
            '%%EOF',
        ];

        return response(implode("\n", $lines), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="receipt-' . $payment->id . '.pdf"',
        ]);
    }
}
