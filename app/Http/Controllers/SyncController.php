<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSyncTransactionRequest;
use App\Models\Customer;
use App\Models\SyncLog;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class SyncController extends Controller
{
    public function store(StoreSyncTransactionRequest $request): JsonResponse
    {
        $user = $request->user();

        if (! $user || (! $user->isSuperAdmin() && ! $user->isBranchAdmin() && ! $user->isCashier())) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $synced = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($request->validated('transactions') as $payload) {
            $transactionId = $payload['id'];

            if (Transaction::query()->whereKey($transactionId)->exists()) {
                $skipped++;
                SyncLog::query()->create([
                    'table_name' => 'transactions',
                    'record_id' => $transactionId,
                    'status' => 'skipped',
                    'message' => 'Transaction already exists.',
                    'payload' => $payload,
                    'synced_at' => now(),
                ]);

                continue;
            }

            try {
                DB::transaction(function () use ($payload, $user, $transactionId, &$synced): void {
                    $items = $payload['items'];
                    $subtotal = collect($items)->sum(function (array $item): float {
                        return $item['weight'] * $item['price_per_gram'];
                    });

                    $additionalFee = $payload['additional_fee'] ?? 0;
                    $total = $subtotal + $additionalFee;

                    $customerId = $payload['customer_id'] ?? null;
                    $customerExists = $customerId
                        ? Customer::query()->whereKey($customerId)->exists()
                        : false;

                    $transaction = Transaction::query()->create([
                        'id' => $transactionId,
                        'branch_id' => $user->branch_id,
                        'user_id' => $user->id,
                        'customer_id' => $customerExists ? $customerId : null,
                        'type' => $payload['type'],
                        'payment_method' => $payload['payment_method'],
                        'occurred_at' => $payload['occurred_at'],
                        'subtotal' => $subtotal,
                        'additional_fee' => $additionalFee ?: null,
                        'total' => $total,
                        'notes' => $payload['notes'] ?? null,
                        'is_synced' => true,
                        'synced_at' => now(),
                    ]);

                    foreach ($items as $item) {
                        $transaction->items()->create([
                            'id' => $item['id'] ?? null,
                            'gold_level_id' => $item['gold_level_id'],
                            'product_type' => $item['product_type'],
                            'weight' => $item['weight'],
                            'price_per_gram' => $item['price_per_gram'],
                            'total' => $item['weight'] * $item['price_per_gram'],
                            'is_synced' => true,
                            'synced_at' => now(),
                        ]);
                    }

                    SyncLog::query()->create([
                        'table_name' => 'transactions',
                        'record_id' => $transactionId,
                        'status' => 'synced',
                        'message' => null,
                        'payload' => $payload,
                        'synced_at' => now(),
                    ]);

                    $synced++;
                });
            } catch (Throwable $exception) {
                $failed++;
                SyncLog::query()->create([
                    'table_name' => 'transactions',
                    'record_id' => $transactionId,
                    'status' => 'failed',
                    'message' => $exception->getMessage(),
                    'payload' => $payload,
                    'synced_at' => now(),
                ]);
            }
        }

        return response()->json([
            'synced' => $synced,
            'skipped' => $skipped,
            'failed' => $failed,
        ]);
    }
}
