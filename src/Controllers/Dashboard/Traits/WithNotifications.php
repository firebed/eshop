<?php


namespace Eshop\Controllers\Dashboard\Traits;


trait WithNotifications
{
    public function showSuccessNotification(string $notification, bool $autohide = true): void
    {
        $this->showNotification('success', $notification, $autohide);
    }

    public function showWarningNotification(string $notification, bool $autohide = true): void
    {
        $this->showNotification('warning', $notification, $autohide);
    }

    public function showErrorNotification(string $notification, string $error = null, bool $autohide = true): void
    {
        if ($error) {
            $notification .= ' ' . $error;
        }
        $this->showNotification('error', $notification, $autohide);
    }

    public function showNotification(string $type, string $notification, bool $autohide = true): void
    {
        session()->flash('toast', ['type' => $type, 'body' => $notification, 'autohide' => $autohide]);
    }
}