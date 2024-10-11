<x-filament::page>
    <form wire:submit.prevent="sendEmail">
        <div class="space-y-6">
            <x-filament::card>
                <x-filament-forms::field-wrapper id="to" label="Recipient Email">
                    <x-filament-forms::text-input type="email" wire:model.defer="emailData.to" required />
                </x-filament-forms::field-wrapper>

                <x-filament-forms::field-wrapper id="subject" label="Subject">
                    <x-filament-forms::text-input type="text" wire:model.defer="emailData.subject" required />
                </x-filament-forms::field-wrapper>

                <x-filament-forms::field-wrapper id="body" label="Body">
                    <x-filament-forms::textarea wire:model.defer="emailData.body" required />
                </x-filament-forms::field-wrapper>

                <x-filament::button type="submit">
                    Send Email
                </x-filament::button>
            </x-filament::card>
        </div>
    </form>
</x-filament::page>
