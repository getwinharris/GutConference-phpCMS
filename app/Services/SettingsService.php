<?php
namespace App\Services;
final class SettingsService {
    public function __construct(private JsonStoreService $store = new JsonStoreService()) {}
    public function public(): array { return $this->store->read('settings')[0] ?? ['currency'=>'INR','timezone'=>'Asia/Kolkata','featured_event_slug'=>'global-gut-summit-2026']; }
    public function savePublic(array $settings): void { $this->store->write('settings', [$settings]); }
}
