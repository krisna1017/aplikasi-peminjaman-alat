<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Filament\Resources\Tickets\TicketResource;
use Filament\Resources\Pages\CreateRecord;
use Override;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    #[Override]
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['ticket_number'] = 'TIC' . now()->format('Ymd') . '-' . strtoupper(str()->random(6));
        return $data;
    }
}
