<?php

namespace Domain\Order\Processes;

use Domain\Order\Events\OrderCreated;
use Domain\Order\Models\Order;
use DomainException;
use Illuminate\Pipeline\Pipeline;
use Support\Transaction;
use Throwable;

final class OrderProcess
{
    // Тут храним все процессы, которые в него будет передаваться
    protected array $processes = [];

    public function __construct(
        // Новый заказ со статусом new
        protected Order $order
    )
    {
    }

    // Метод который будет заполнять необходимыми процессами с которыми будет взаимодействует
    public function processes(array $processes): self
    {
        $this->processes = $processes;

        return $this;
    }

    /**
     * @throws Throwable
     * Метод, который будет запускать процессы и вернёт трансформированный заказ
     */
    public function run(): Order
    {
        return Transaction::run(function (){
            return app(Pipeline::class)
                ->send($this->order)
                ->through($this->processes)
                ->thenReturn();
        },
            // Если все процессы прошли успешно, все транзакции выполнились
            function (Order $order){
                flash()->info('Good # ' . $order->id);

                event(new OrderCreated($order));
        },
            // Последний коллбек если произошла ошибка
            function (Throwable $e){
                throw new DomainException($e->getMessage());
            }
        );
    }
}
